<?php

namespace App\Http\Controllers\Stock;
use Illuminate\Routing\Controller as RoutingController;
use Illuminate\Http\Request;

class RepoertsController extends RoutingController
{
    public function annualRequest(){
        return view('reports.annual-request');
    }
}
