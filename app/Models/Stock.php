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
        Stock::create([
            'item_id' => $item->id,
            'user_id' => 2,
            'out_quantity' => $quantity,
            'details' => $details,
            'approved' => false,
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
            'annual_request_id' => $user->getActiveRequest()->id,
            'in_quantity' => $quantity,
            'details' => $details,
            'approved' => false,
        ]);
    }

    public static function removeBalance(Item $item, int $quantity, string $details, User $user)
    {
        Stock::create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'annual_request_id' => $user->getActiveRequest()->id,
            'out_quantity' => $quantity,
            'details' => $details,
            'approved' => false,
        ]);
    }

    public static function getExtraBalance(Item $item, User $user): int
    {
        $lastReset = AnnualRequest::getLastYearReset();
        return Stock::where('user_id', $user->id)
            ->where('created_at', '>', $lastReset)
            ->where('item_id', $item->id)
            ->sum('in_quantity');
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
            ->where('created_at', '>', $lastReset)
            ->whereIn('item_id', $items->pluck('id'))
            ->groupBy('item_id')
            ->selectRaw('item_id, SUM(out_quantity) as total_consumed')
            ->pluck('total_consumed', 'item_id');

        $extraBalances = self::addExtraBalances($user, $items);

        return $items->mapWithKeys(function ($item) use ($requestedBalances, $consumed, $extraBalances) {
            return [$item->id => $requestedBalances[$item->id] + $extraBalances[$item->id] - ($consumed[$item->id] ?? 0)];
        });
    }

    public static function getStock(Item $item): int
    {
        $lastReset = AnnualRequest::getLastYearReset();
        $stock = Stock::where('user_id', 2)->where('created_at', '>', $lastReset)->where('item_id', $item->id)->get();
        $totalIn = $stock->sum('in_quantity');
        $totalOut = $stock->sum('out_quantity');
        return $totalIn - $totalOut;
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
