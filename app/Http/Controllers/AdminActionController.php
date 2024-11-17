<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CheckAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;

class AdminActionController extends Controller
{
    public function __construct()
    {
        $this->middleware(CheckAdmin::class);
    }

    public function config()
    {
        $startDate = config('request_period.start_date');
        $endDate = config('request_period.end_date');

        return view('admin.config', compact('startDate', 'endDate'));
    }

    public function updatePeriod(Request $request)
    {
        $request->validate([
            'request_start_date' => 'required|date',
            'request_end_date' => 'required|date|after_or_equal:request_start_date',
        ]);
        

        return redirect()->back()->with('عملية ناجحة', 'تم تحديد فترة الطلب السنوي بنجاح');
    }
}
