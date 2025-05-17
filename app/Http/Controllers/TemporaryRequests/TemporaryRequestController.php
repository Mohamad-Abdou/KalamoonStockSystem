<?php

namespace App\Http\Controllers\TemporaryRequests;

use App\Models\TemporaryRequest;
use Illuminate\Routing\Controller as RoutingController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class TemporaryRequestController extends RoutingController
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(TemporaryRequest::class);

    }

    public function index()
    {
        return view('temporary-requests.index');
    }

    public function create()
    {
        return view('temporary-requests.create');
    }
}
