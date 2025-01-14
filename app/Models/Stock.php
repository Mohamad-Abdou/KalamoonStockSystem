<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $guarded = [];

    public static function inStock(Item $item, int $quantity, string $details)
    {
        Stock::create([
            'item_id' => $item->id,
            'user_id' => 2,
            'in_quantity' => $quantity,
            'details' => $details,
            'approved' => false,
        ]);
    }

    public static function outStock(Item $item, int $quantity, string $details)
    {
        $item = self::addStockToItem($item);
        if($item->inSotckAvalible < $quantity) {
            new \Exception('لا يوجد كمية متاحة في المستودع');
        }
        Stock::create([
            'item_id' => $item->id,
            'user_id' => 2,
            'out_quantity' => $quantity,
            'details' => $details,
            'approved' => true,
        ]);
    }

    public static function approveStock(Stock $stock)
    {
        $stock->approved = true;
        $stock->save();
    }

    public static function addBalance(Item $item, int $quantity, string $details, User $user)
    {
        Stock::create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'in_quantity' => $quantity,
            'annual_request_id' => $user->getActiveRequest()->id,
            'details' => $details,
            'approved' => true,
        ]);
    }

    public static function MoveBalance(Item $item, int $quantity, User $fromUser, User $toUser)
    {
        if (Stock::getUserBalance($fromUser, $item) < $quantity) {
            throw new \Exception('لا يوجد رصيد كافي للنقل');
        }
        Stock::create([
            'item_id' => $item->id,
            'user_id' => $fromUser->id,
            'out_quantity' => $quantity,
            'annual_request_id' => $fromUser->getActiveRequest()->id,
            'details' => 'نقل رصيد إلى ' . $toUser->role,
            'approved' => true,
        ]);
        self::addBalance($item, $quantity, 'نقل رصيد من ' . $fromUser->role, $toUser);
    }

    public static function removeBalance(Item $item, int $quantity, string $details, User $user)
    {
        Stock::create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'annual_request_id' => $user->getActiveRequest()->id,
            'out_quantity' => $quantity,
            'details' => $details,
            'approved' => true,
        ]);
    }

    public static function getExtraBalance(Item $item, User $user): int
    {
        $lastReset = AnnualRequest::getLastYearReset();
        $added = Stock::where('user_id', $user->id)
            ->where('created_at', '>=', $lastReset)
            ->where('item_id', $item->id)
            ->sum('in_quantity');
        
        $removed = Stock::where('user_id', $user->id)
        ->where('created_at', '>=', $lastReset)
        ->where('annual_request_id', null)
        ->where('item_id', $item->id)
        ->sum('out_quantity');

        return $added - $removed;
    }

    public static function addExtraBalances($annualRequest): AnnualRequest
    {
        $extraBalances = Stock::where('user_id', $annualRequest->user->id)
            ->where('annual_request_id', $annualRequest->id)
            ->whereIn('item_id', $annualRequest->items->pluck('id'))
            ->groupBy('item_id')
            ->selectRaw('item_id, SUM(in_quantity) as total_extra')
            ->pluck('total_extra', 'item_id');

        $annualRequest->items->each(function ($item) use ($extraBalances) {
            $item->extra_balance = $extraBalances[$item->id] ?? 0;
        });

        return $annualRequest;
    }



    public static function getUserBalance(User $user, Item $item): int
    {
        $requestdBalance = $user->getActiveRequest()->items->find($item->id)->pivot->quantity;
        $extraBalance = self::getExtraBalance($item, $user);
        $lastReset = AnnualRequest::getLastYearReset();
        $consumed = Stock::where('user_id', $user->id)
            ->where('created_at', '>', $lastReset)
            ->where('item_id', $item->id)
            ->sum('out_quantity');
        return $requestdBalance + $extraBalance - $consumed;
    }

    public static function addUserYearConsumed(AnnualRequest $annualRequest): AnnualRequest
    {
        $consumed = Stock::where('user_id', $annualRequest->user_id)
            ->where('annual_request_id', $annualRequest->id)
            ->groupBy('item_id')
            ->selectRaw('item_id, SUM(out_quantity) as total_consumed')
            ->pluck('total_consumed', 'item_id');

        $annualRequest->items->each(function ($item) use ($consumed) {
            $item->consumed = $consumed[$item->id] ?? 0;
        });

        return $annualRequest;
    }

    public static function addUserBalances(AnnualRequest $annualRequest): AnnualRequest
    {
        $consumed = Stock::where('user_id', $annualRequest->user->id)
            ->where('annual_request_id', $annualRequest->id)
            ->whereIn('item_id', $annualRequest->items->pluck('id'))
            ->groupBy('item_id')
            ->selectRaw('item_id, SUM(out_quantity) as total_consumed')
            ->pluck('total_consumed', 'item_id');

        $annualRequest = self::addExtraBalances($annualRequest);

        $annualRequest->items->each(function ($item) use ($consumed) {
            $item->balance = ($item->pivot->quantity + ($item->extra_balance ?? 0)) - ($consumed[$item->id] ?? 0);
        });

        return $annualRequest;
    }

    public static function getUserBalances(User $user, $items)
    {
        $requestedBalances = $user->getActiveRequest()->items->pluck('pivot.quantity', 'id');
        $lastReset = AnnualRequest::getLastYearReset();

        $consumed = Stock::where('user_id', $user->id)
            ->where('created_at', '>=', $lastReset)
            ->whereIn('item_id', $items->pluck('id'))
            ->groupBy('item_id')
            ->selectRaw('item_id, SUM(out_quantity) as total_consumed')
            ->pluck('total_consumed', 'item_id');

        $extraBalances = self::addExtraBalances($user, $items);

        return $items->mapWithKeys(function ($item) use ($requestedBalances, $consumed, $extraBalances) {
            return [$item->id => $requestedBalances[$item->id] + $extraBalances[$item->id] - ($consumed[$item->id] ?? 0)];
        });
    }

    public static function addStockToItem(Item $item): Item
    {
        $lastReset = AnnualRequest::getLastYearReset();
        $stock = Stock::where('user_id', 2)->where('created_at', '>=', $lastReset)->where('item_id', $item->id)->get();
        $totalIn = $stock->sum('in_quantity');
        $totalOut = $stock->sum('out_quantity');
        $item->inStockAvalible = $totalIn - $totalOut;
        return $item;
    }

    public static function mainInStock(Item $item): int
    {
        return Stock::where('created_at', '>=', AnnualRequest::getLastYearReset())->where('user_id', 2)->where('item_id', $item->id)->sum('in_quantity');
    }

    public static function NeededStock(Item $item): int
    {
        $lastReset = AnnualRequest::getLastYearReset();
        $totalRequested = AnnualRequestItem::where('created_at', '>=' ,$lastReset)->where('item_id', $item->id)->where('frozen', false)->sum('quantity');

        return $totalRequested;
    }

    public static function addAllowedQuantityToRequest(Item $item, User $user) : Item
    {
        $item = Stock::addStockToItem($item);
        $totalRequested = self::NeededStock($item);
        $userRequestedQuantity = $user->getActiveRequest()->items->find($item->id)->pivot->quantity + Stock::getExtraBalance($item, $user);
        $userBalance = Stock::getUserBalance($user, $item);
        $avalible = $item->inStockAvalible;
        $consumed = Stock::where('user_id', $user->id)
            ->where('annual_request_id', $user->getActiveRequest()->id)
            ->where('item_id', $item->id)
            ->sum('out_quantity');
        $mainInStock = Stock::mainInStock($item);   
        
        if ($mainInStock >= $totalRequested) {
            $item->AllowedQuantityToRequest = $userBalance;    
        }
        else
        {
            $item->AllowedQuantityToRequest = round(($userRequestedQuantity / $totalRequested) * $mainInStock) - $consumed; 
        }
        
        return $item;
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
