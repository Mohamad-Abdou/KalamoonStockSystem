<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CheckRequestPeriod;
use Illuminate\Routing\Controller;


class AnnualRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware(CheckRequestPeriod::class);
    }
}
