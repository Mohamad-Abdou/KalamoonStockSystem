<?php

namespace App\Http\Middleware;

use App\Models\AnnualRequest;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckYearState
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (AnnualRequest::getYearState()) {
            return $next($request);
        }
        return abort(423);
    }
}
