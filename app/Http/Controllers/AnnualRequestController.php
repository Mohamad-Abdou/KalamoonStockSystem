<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CheckRequestPeriod;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\AnnualRequest;
use App\Models\User;
use Illuminate\Routing\Controller as RoutingController;
use Illuminate\Support\Facades\Auth;

class AnnualRequestController extends RoutingController
{
    use AuthorizesRequests;
    public function __construct()
    {
        $this->authorizeResource(AnnualRequest::class);
        $this->middleware(CheckRequestPeriod::class)->only(['create']);
    }

    public function index()
    {
        $periodEnd = AnnualRequest::getPeriod()['end'];
        $requests = Auth::user()->annualRequests;
        return view('annual-request.index', ['requests' => $requests, 'periodActive' => AnnualRequest::isActiveRequestPeriod(), 'periodEndAt' => $periodEnd]);
    }

    public function show(AnnualRequest $annualRequest)
    {
        if ($annualRequest->state != 0) {
            $holdWith = User::find($annualRequest->state)?? null;
            return view('annual-request.show', ['request' => $annualRequest, 'requestItems' => $annualRequest->Items, 'holdWith' => $holdWith]);
        } else {
            return redirect()->route('annual-request.edit', ['annual_request' => $annualRequest]);
        }
    }

    public function create()
    {
        $request = Auth::user()->annualRequests->where('created_at', '>', AnnualRequest::getLastYearReset())->first();
        if (!$request) {
            return view('annual-request.create');
        }
        return redirect()->route('annual-request.edit', ['annual_request' => $request]);
    }

    public function edit(AnnualRequest $annualRequest)
    {
        if(!AnnualRequest::isActiveRequestPeriod() && !$annualRequest->return_reason) {
            abort(403);
        }
        if ($annualRequest->state === 0) {
            $request = AnnualRequest::find($annualRequest)->first();
            return view('annual-request.edit', ['request' => $annualRequest->id]);
        }
        else {
            return redirect()->route('annual-request.show', ['annual_request' => $annualRequest]);
        }
    }

    public function archive(){
        return view('annual-request.archive');
    }
}
