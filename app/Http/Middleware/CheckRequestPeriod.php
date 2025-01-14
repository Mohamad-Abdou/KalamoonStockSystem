<?php

namespace App\Http\Middleware;

use App\Models\AnnualRequest;
use Closure;

class CheckRequestPeriod
{
    public function handle($request, Closure $next)
    {
        // التحقق من صلاحية فترة الطلب
        if (AnnualRequest::isActiveRequestPeriod()) {
            return $next($request);
        }
        return abort(403);
    }
}
