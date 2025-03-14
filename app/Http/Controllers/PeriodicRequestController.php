<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CheckYearState;
use App\Models\AnnualRequest;
use App\Models\Item;
use App\Models\PeriodicRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as RoutingController;

class PeriodicRequestController extends RoutingController
{

    public function __construct()
    {
        $this->middleware(CheckYearState::class)->only(['index', 'show']);
    }

    public function archive()
    {
        $userPeriodicRequests = Auth::user();
        return view('periodic-request.archive');
    }

    public function show($item_id)
    {
        return view('periodic-request.show', compact('item_id'));
    }

    public function index()
    {
        $currentSemester = AnnualRequest::getCurrentSemester();
        return view('periodic-request.index', compact('currentSemester'));
    }
}
