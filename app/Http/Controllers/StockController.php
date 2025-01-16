<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as RoutingController;


class StockController extends RoutingController
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Stock::class);

    }

    public function PeriodicRequests()
    {
        return view('stock.holderActions.periodicRequests');
    }

    public function index()
    {
        return view('stock.index');
    }


    // إدخال الكميات لمدير المستودع
    public function create()
    {
        return view('stock.holderActions.itemsQuantityinsert');
    }

    public function NeededReport()
    {
        return view('stock.reports.neededReport');
    }

    public function InsertionConfirmation()
    {
        $this->authorize('InsertionConfirmation', Stock::class);
        return view('stock.InsertionConfirmation');
    }
}
