<?php

namespace App\Http\Controllers\TemporaryRequests;
use Illuminate\Routing\Controller as RoutingController;

use Illuminate\Http\Request;

class TemporaryRequestController extends RoutingController
{
    public function index()
    {
        return view('temporary-requests.index');
    }
}
