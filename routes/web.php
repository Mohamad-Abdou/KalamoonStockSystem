<?php

use App\Http\Controllers\AdminActionController;
use App\Http\Controllers\AnnualRequestController;
use App\Http\Controllers\AnnualRequestFlowController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemGroupController;
use App\Http\Controllers\PeriodicRequestController;
use App\Http\Controllers\PeriodicRequestFlowController;
use App\Http\Controllers\RepoertsController;
use App\Http\Controllers\StockController;
use App\Models\Stock;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

//Admin Configration Routes
Route::controller(AdminActionController::class)->group(function () {
    Route::put('/admin/annual-requests/update-period', 'updatePeriod')->name('admin.annual-requests.update-period');
    Route::get('/admin/config', 'config')->name('app.configure');
})->middleware(['CheckAdmin', 'auth']);

// Items Controling Routes
Route::resource('/items', ItemController::class)->middleware(['auth']);
Route::resource('/item_groups', ItemGroupController::class)->only(['store', 'index'])->middleware(['auth']);

// Annual Request Routes
Route::resource('/annual-request', AnnualRequestController::class)->middleware(['auth']);
Route::resource('/annual-request-flow', AnnualRequestFlowController::class)
->middleware(['auth', 'AnnualFlow'])
->parameters(['annual-request-flow' => 'annual_request']);

Route::resource('/periodic-request', PeriodicRequestController::class)->middleware(['auth']);
Route::resource('/periodic-request-flow', PeriodicRequestFlowController::class)
->middleware(['auth', 'PeriodicFlow']);

Route::get('/archive', [AnnualRequestController::class, 'archive'])->name('annual-requests.archive')->middleware(['auth']);

// Annual Request Accessories 
Route::prefix('reports')->group(function () {
    Route::get('/annual-request', [RepoertsController::class, 'annualRequest'])->name('reports.annual-request')->middleware(['auth']);
});
Route::get('/reset-year', [AnnualRequestController::class, 'resetYear'])->name('annual-request.reset')->middleware(['auth']);
Route::get('/balance-manager', [AnnualRequestController::class, 'manageBalances'])->name('annual-request.balanes')->middleware(['auth']);

// Stock Routes
Route::resource('/stocks', StockController::class)->middleware(['auth']);
Route::prefix('stock')->group(function () {
    Route::get('/insert', [StockController::class, 'create'])->name('stock.insertQunatity')->middleware(['auth']);
    Route::get('/periodic-requests', [StockController::class, 'PeriodicRequests'])->name('stock.periodic-requests')->middleware(['auth']);
});
Route::get('/stock/insertion-confirmation', [StockController::class, 'InsertionConfirmation'])->name('stock.insertionConfirmation')->middleware(['auth']);


require __DIR__ . '/auth.php';
