<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RepoertsController extends Controller
{
    public function annualRequest(){
        return view('reports.annual-request');
    }
}
