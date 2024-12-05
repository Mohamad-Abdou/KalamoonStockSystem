<?php

namespace App\Models;

use Carbon\Carbon;
use DragonCode\Support\Facades\Helpers\Boolean;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Stmt\Return_;
use Illuminate\Support\Facades\Log;

/**
 * نموذج الطلب السنوي
 * 
 * يوفر هذا النموذج طرقًا لإدارة عملية الطلب السنوي، بما في ذلك:
 * - الحصول على تواريخ بداية ونهاية فترة الطلب السنوي
 * - الحصول على تاريخ آخر تصفير للسنة
 * - التحقق مما إذا كان الوقت الحالي ضمن فترة الطلب السنوي
 * - إدارة العلاقة بين الطلبات السنوية والمواد
 * - التعامل مع حالة وحالة الطلبات السنوية
 * - توجيه الطلبات للأمام والخلف
 */
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
    public function Items()
    {
        return $this->belongsToMany(Item::class, 'annual_request_item')
            ->withPivot('id', 'quantity', 'frozen', 'freeze_reason', 'objection_reason')
            ->withTimestamps();
    }

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
            'caller' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2),
            'timestamp' => now()->toDateTimeString(),
            'session_id' => session()->getId()
        ]);

        Log::info('Starting forward request', [
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
    
        Log::info('Current flow state', [
            'current_flow' => $currentFlow ? $currentFlow->toArray() : null
        ]);

        // Log next flow lookup
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

        Log::info('Next flow determined', [
            'next_flow' => $nextFlow ? $nextFlow->toArray() : null
        ]);

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
