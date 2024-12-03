<?php

namespace App\Models;

use Carbon\Carbon;
use DragonCode\Support\Facades\Helpers\Boolean;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Stmt\Return_;

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
        if ($this->items->contains('pivot.objection', true)) {
            session()->flash('message', 'لا يمكن تحويل الطلب في حال وجود اعتراضات');
            redirect()->back();
            return;
        }

        $current = $this->state;
        $order = RequestFlow::where('request_type', 0)->where('user_id', $current)->first()->order?? 0;
        $NextUserId = RequestFlow::where('request_type', 0)->where('order', '>', $order)->orderBy('order')->first()->user_id ?? 2;
        $this->update(['state' => $NextUserId, 'return_reason' => null]);
        if($NextUserId === $this->user_id) $this->forwardRequest();
    }

    // دالة لإرجاع الطلب للمستخدم السابق في سير العمل
    public function backwordRequest()
    {
        $current = $this->state;
        $order = RequestFlow::where('request_type', 0)->where('user_id', $current)->first()->order;
        $PreviosUserId = RequestFlow::where('request_type', 0)->where('order', '<', $order)->orderBy('order', 'DESC')->first()->user_id ?? 0;
        $this->update(['state' => $PreviosUserId]);
        if($PreviosUserId === $this->user_id) $this->backwordRequest();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
