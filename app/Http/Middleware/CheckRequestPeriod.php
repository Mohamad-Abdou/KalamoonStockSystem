<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class CheckRequestPeriod
{
    public function handle($request, Closure $next)
    {
        $today = now()->toDateString();

        // Retrieve the request period from cache or fallback to dynamic defaults
        $startDate = Cache::get('request_start_date', now()->toDateString());
        $endDate = Cache::get('request_end_date', now()->addDays(10)->toDateString());

        // Check if the current date is outside the allowed range
        if ($today < $startDate || $today > $endDate) {
            return redirect()->back()->with('error', 'Requests are not allowed at this time.');
        }

        return $next($request);
    }
}
