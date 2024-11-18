<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CheckAdmin;
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
        $period = AppConfiguration::getAnnualRequestPeriod();
        $startDate = $period['start'];
        $endDate = $period['end'];

        return view('admin.config', compact('startDate', 'endDate'));
    }

    public function updatePeriod(Request $request)
    {
        $validated = $request->validate([
            'request_start_date' => 'required|date|after_or_equal:today',
            'request_end_date' => 'required|date|after_or_equal:request_start_date',
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


        return redirect()->back()->with('عملية ناجحة', 'تم تحديد فترة الطلب السنوي بنجاح');
    }
}
