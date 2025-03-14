<?php

namespace App\Models;

use Carbon\Carbon;
use DragonCode\Support\Facades\Helpers\Boolean;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Return_;
use Illuminate\Support\Facades\Log;

class AnnualRequest extends Model
{
    // السماح بالتعبئة الجماعية لجميع الحقول
    protected $guarded = [];

    // دالة للحصول على تاريخ بداية ونهاية فترة التسجيل
    public static function getPeriod()
    {
        return [
            'start' => Carbon::parse(
                AppConfiguration::where('name', 'AnnualRequestPeriod')->where('key', 'start')->value('value')
            ),
            'end' => Carbon::parse(
                AppConfiguration::where('name', 'AnnualRequestPeriod')->where('key', 'end')->value('value')
            ),
        ];
    }

    // دالة للحصول على حالة السنة
    public static function getYearState(): bool
    {
        $value = AppConfiguration::where('name', 'Year')
            ->where('key', 'State')
            ->value('value');

        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    // دالة للحصول على تاريخ آخر تصفير للسنة
    public static function getLastYearReset()
    {
        return Carbon::parse(AppConfiguration::where('name', 'LastReset')->where('key', 'Date')->value('value'));
    }

    // دالة للتحقق من أن الوقت الحالي ضمن فترة التسجيل
    public static function isActiveRequestPeriod(): bool
    {
        $requestPeriod = AnnualRequest::getPeriod();
        $startDate = $requestPeriod['start']->startOfDay();
        $endDate = $requestPeriod['end']->endOfDay();
        $today = now();
        return $today->gte($startDate) && $today->lte($endDate);
    }

    // علاقة مع جدول المواد


    // دالة لعرض حالة الطلب كنص
    public function getRequestStateTextAttribute()
    {
        if ($this->state === 0) {
            if (! $this->return_reason) return 'مسودة';
            else return 'مرتجع';
        } elseif ($this->state === -1) return 'أرشيف';
        elseif ($this->state === 2) {
            return 'السنة الحالية - فعال';
        } else return 'قيد الدراسة';
    }
    // دالة لتحويل الطلب للمستخدم التالي في سير العمل
    public function forwardRequest()
    {
        Log::info('Forward Request Called', [
            'request_id' => $this->id,
            'current_state' => $this->state,
            'user_id' => $this->user_id
        ]);

        if ($this->items->contains('pivot.objection', true)) {
            Log::warning('Forward request blocked - objections exist', [
                'request_id' => $this->id
            ]);
            throw new \Exception('لا يمكن تحويل الطلب في حال وجود اعتراضات');
        }

        $current = $this->state;

        $currentFlow = RequestFlow::where('request_type', 0)
            ->where('user_id', $current)
            ->first();

        if (!$currentFlow) {
            Log::info('No current flow found, getting first flow');
            $nextFlow = RequestFlow::where('request_type', 0)
                ->orderBy('order')
                ->first();
        } else {
            Log::info('Finding next flow', [
                'current_order' => $currentFlow->order
            ]);
            $nextFlow = RequestFlow::where('request_type', 0)
                ->where('order', '>', $currentFlow->order)
                ->orderBy('order')
                ->first();
        }

        if ($nextFlow && $nextFlow->user_id == $this->user_id) {
            Log::info('same user requester', [
                'next_flow' => $nextFlow ? $nextFlow->toArray() : null
            ]);
            $nextFlow = RequestFlow::where('request_type', 0)
                ->where('order', '>', $nextFlow->order)
                ->orderBy('order')
                ->first();
        }

        if (!$nextFlow) {
            Log::info('Request completed - setting final state', [
                'request_id' => $this->id
            ]);
            $this->update(['state' => 2]);
        } else {
            Log::info('Updating request state', [
                'request_id' => $this->id,
                'new_state' => $nextFlow->user_id
            ]);
            $this->update([
                'state' => $nextFlow->user_id,
                'return_reason' => null
            ]);
        }
    }

    public static function resetYear()
    {
        $lastReset = self::getLastYearReset();
        $yearState = self::getYearState();

        if (!$yearState) {
            throw new \Exception('لا يمكن إعادة تدوير السنة إذا كانت السنة الحالية غير فعالة');
        }
        if ($lastReset->diffInHours(now()) < 24) {
            throw new \Exception('يجب الانتظار 24 ساعة على الأقل قبل إعادة تدوير الأرصدة');
        }
        DB::transaction(function () {

            PeriodicRequest::where('state', 0)->delete();
            PeriodicRequest::where('state', '>', -1)->update(['state' => -1]);
            AnnualRequest::whereNotIn('state', [-1, 2])->delete();
            AnnualRequest::where('state', '>', -1)->update(['state' => -1]);

            $moveToNextYear = Stock::where('user_id', 2)
                ->select('item_id')
                ->where('created_at', '>=', self::getLastYearReset())
                ->where('created_at', '>=', AnnualRequest::getLastYearReset())
                ->addSelect(DB::raw('SUM(in_quantity) as total_in'))
                ->addSelect(DB::raw('SUM(out_quantity) as total_out'))
                ->groupBy('item_id')
                ->get()
                ->map(function ($stock) {
                    $difference = $stock->total_in - $stock->total_out;
                    return [
                        'item_id' => $stock->item_id,
                        'total_in' => $stock->total_in,
                        'total_out' => $stock->total_out,
                        'difference' => $difference
                    ];
                });

            $now = Carbon::now();
            $stockData = $moveToNextYear
                ->filter(fn($item) => $item['difference'] > 0)
                ->map(fn($item) => [
                    'item_id' => $item['item_id'],
                    'in_quantity' => $item['difference'],
                    'user_id' => 2,
                    'details' => ' تدوير أرصدة الفترة من: ' . self::getLastYearReset()->format('Y-m-d') . ' إلى: ' . $now->format('Y-m-d'),
                    'created_at' => now(),
                    'updated_at' => now(),
                    'approved' => 0
                ])
                ->toArray();

            Stock::insert($stockData);

            AppConfiguration::where('name', 'LastReset')
                ->where('key', 'Date')
                ->update(['value' => $now]);

            AppConfiguration::where('name', 'Year')
                ->where('key', 'state')
                ->update(['value' => 0]);

            AppConfiguration::where('name', 'AnnualRequestPeriod')
                ->where('key', 'start')
                ->update(['value' => $now]);

            AppConfiguration::where('name', 'AnnualRequestPeriod')
                ->where('key', 'end')
                ->update(['value' => $now->copy()->addDays(10)]);
        });
    }

    public static function startYear()
    {
        $usersWithNoActiveRequest = User::whereNotIn('type', [0, 1])
            ->whereDoesntHave('annualRequests', function ($query) {
                $query->where('state', 2);
            })
            ->get();
        if ($usersWithNoActiveRequest->count() > 0) {
            if ($usersWithNoActiveRequest->count() > 0) {
                throw new \Exception($usersWithNoActiveRequest);
            }
        }

        AppConfiguration::where('name', 'Year')
            ->where('key', 'state')
            ->update(['value' => true]);
    }



    // دالة لإرجاع الطلب للمستخدم السابق في سير العمل
    public function backwordRequest()
    {
        Log::info('Starting backward request', [
            'request_id' => $this->id,
            'current_state' => $this->state
        ]);

        $current = $this->state;

        $previousFlow = RequestFlow::where('request_type', 0)
            ->where('order', '<', function ($query) use ($current) {
                $query->select('order')
                    ->from('request_flows')
                    ->where('user_id', $current)
                    ->where('request_type', 0);
            })
            ->orderBy('order', 'DESC')
            ->first();

        Log::info('Previous flow determined', [
            'previous_flow' => $previousFlow ? $previousFlow->toArray() : null
        ]);

        if ($previousFlow && $previousFlow->user_id == $this->user_id) {
            Log::info('Skipping user\'s own flow, finding earlier flow');
            $previousFlow = RequestFlow::where('request_type', 0)
                ->where('order', '<', $previousFlow->order)
                ->orderBy('order', 'DESC')
                ->first();
        }
        if (!$previousFlow) {
            Log::info('Request returned to draft state', [
                'request_id' => $this->id
            ]);
            $this->update(['state' => 0]);
        } else {
            Log::info('Updating request state', [
                'request_id' => $this->id,
                'new_state' => $previousFlow->user_id
            ]);
            $this->update(['state' => $previousFlow->user_id]);
        }
    }

    public static function getCurrentSemester()
    {
        return AppConfiguration::where('name', 'Year')->where('key', 'semester')->value('value');
    }

    public static function NextSemester()
    {
        $currentSemester = self::getCurrentSemester();
        $nextSemester = $currentSemester + 1;
        AppConfiguration::where('name', 'Year')->where('key', 'semester')->update(['value' => $nextSemester]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function Items()
    {
        return $this->belongsToMany(Item::class, 'annual_request_item')
            ->withPivot('id', 'quantity', 'first_semester_quantity', 'second_semester_quantity' ,'third_semester_quantity', 'frozen', 'freeze_reason', 'objection_reason')
            ->withTimestamps();
    }
}
