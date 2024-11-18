<?php

namespace App\Http\Middleware;

use App\Models\AppConfiguration;
use Closure;

class CheckRequestPeriod
{
    public function handle($request, Closure $next)
    {
        $requestPeriod = AppConfiguration::getAnnualRequestPeriod();
        $startDate = $requestPeriod['start'];
        $endDate = $requestPeriod['end'];

        $today = now();

        // التحقق من صلاحية فترة الطلب
        if ($today->between($startDate, $endDate)) {
            return $next($request);
        }

        return abort(403);
    }
}
