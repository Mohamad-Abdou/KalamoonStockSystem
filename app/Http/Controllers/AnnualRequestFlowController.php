<?php

namespace App\Http\Controllers;

use App\Models\AnnualRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as RoutingController;
use Livewire\WithPagination;

class AnnualRequestFlowController extends RoutingController
{
    use WithPagination;
    public function __construct()
    {
        $this->middleware('AnnualFlow');
    }

    public function index()
    {
        $user = Auth::user();
        $incoming_requests = AnnualRequest::where('state', $user->id)->with('user')->get();
        return view('annual-request-flow.index', ['incoming_requests' => $incoming_requests]);
    }


    public function show(AnnualRequest $annualRequest)
    {
        $annualRequest = $annualRequest->load(['user', 'items']);

        $previous_annual_request = AnnualRequest::with('items')
            ->where('user_id', $annualRequest->user_id)
            ->where('id', '!=', $annualRequest->id)
            ->orderBy('id', 'DESC')
            ->first();

        $annualRequest->items->each(function ($item) use ($previous_annual_request) {
            $prev_item = $previous_annual_request?->items->firstWhere('id', $item->id);
            $item->prev = ['quantity' => $prev_item ? $prev_item->pivot->quantity : 0];
        });

        return view('annual-request-flow.show', [
            'annual_request' => $annualRequest,
            'previous_annual_request' => $previous_annual_request
        ]);
    }
}
