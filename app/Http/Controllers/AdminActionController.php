<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CheckAdmin;
use App\Models\AnnualRequest;
use App\Models\AppConfiguration;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AdminActionController extends Controller
{
    public function __construct()
    {
        $this->middleware(CheckAdmin::class);
    }
        
    public function config()
    {
        $period = AnnualRequest::getPeriod();
        $startDate = $period['start'];
        $endDate = $period['end'];
        return view('admin.config', ['startDate' => $startDate, 'endDate' => $endDate]);
    }

    public function updatePeriod(Request $request)
    {
        if(AnnualRequest::isActiveRequestPeriod()) {
            session()->flash('message', 'لا يمكن تعديل الفترة الزمنية إن كانت السنة فعالة');
            return redirect()->back();
        }

        $validated = $request->validate([
            'request_start_date' => 'required|date',
            'request_end_date' => 'required|date|after_or_equal:request_start_date|after_or_equal:today',
        ]);

        // تخديث تاريخ البداية
        AppConfiguration::updateOrCreate(
            ['name' => 'AnnualRequestPeriod', 'key' => 'start'],
            ['value' => $validated['request_start_date']]
        );

        // تحديث تاريخ النهاية
        AppConfiguration::updateOrCreate(
            ['name' => 'AnnualRequestPeriod', 'key' => 'end'],
            ['value' => $validated['request_end_date']]
        );



        return redirect()->back()->with('message', 'تم تحديد فترة الطلب السنوي بنجاح');
    }
}
