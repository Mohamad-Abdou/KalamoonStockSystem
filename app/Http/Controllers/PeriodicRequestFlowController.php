<?php

namespace App\Http\Controllers;
use Illuminate\Routing\Controller as RoutingController;


use Illuminate\Http\Request;

class PeriodicRequestFlowController extends RoutingController
{
    public function __construct()
    {
        $this->middleware('PeriodicFlow');
    }

    public function index()
    {
        return view('periodic-request-flow.index');
    }
}
