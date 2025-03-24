<?php

namespace App\Http\Controllers\Stock;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\BufferStock;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as RoutingController;

class BufferStockController extends RoutingController
{
    use AuthorizesRequests;
    public function __construct()
    {
        $this->authorizeResource(BufferStock::class);
    }

    public function index()
    {
        return view('buffer_stock.index');
    }
}
