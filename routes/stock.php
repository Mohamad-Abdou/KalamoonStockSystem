<?php

use App\Http\Controllers\Stock\StockController;
use App\Http\Controllers\Stock\BufferStockController;

use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Stock Routes
Route::resource('/stocks', StockController::class)->middleware(['auth']);
Route::prefix('stock')->group(function () {
    Route::get('/insert', [StockController::class, 'create'])->name('stock.insertQunatity')->middleware(['auth']);
    Route::get('/periodic-requests', [StockController::class, 'PeriodicRequests'])->name('stock.periodic-requests')->middleware(['auth']);
});

Route::get('/stock/insertion-confirmation', [StockController::class, 'InsertionConfirmation'])->name('stock.insertionConfirmation')->middleware(['auth']);
Route::get('/stock/NeededReport', [StockController::class, 'NeededReport'])->name('stock.NeededReport')
->middleware(['auth'])->can('report', Stock::class);
Route::resource('/buffer-stock', BufferStockController::class)->middleware(['auth'])->only(['index']); 


Route::get('/print-stocks', function () {
    $stocks = session('print_stocks');
    $totals = session('print_totals');

    session()->forget(['print_stocks', 'print_totals']);

    return view('print.stocks', compact('stocks', 'totals'));
})->middleware(['auth'])->can('viewAny', Stock::class)->name('print.stocks');