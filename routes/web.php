<?php

use App\Http\Controllers\AdminActionController;
use App\Http\Controllers\AnnualRequestController;
use App\Http\Controllers\AnnualRequestFlowController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemGroupController;
use App\Http\Controllers\RequestFlowController;
use App\Http\Middleware\UserPartOfTheAnnualFlow;
use App\Models\AnnualRequest;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::controller(AdminActionController::class)->group(function () {
    Route::put('/admin/annual-requests/update-period', 'updatePeriod')->name('admin.annual-requests.update-period');
    Route::get('/admin/config', 'config')->name('app.configure');
})->middleware(['CheckAdmin', 'auth']);

Route::resource('/items', ItemController::class)->middleware(['auth']);
Route::resource('/item_groups', ItemGroupController::class)->only(['store', 'index'])->middleware(['auth']);
Route::resource('/annual-request', AnnualRequestController::class)->middleware(['auth']);

Route::resource('/annual-request-flow', AnnualRequestFlowController::class)
    ->middleware(['auth', 'AnnualFlow'])
    ->parameters(['annual-request-flow' => 'annual_request']);
Route::get('/archive', [AnnualRequestController::class, 'archive'])->name('annual-requests.archive');

require __DIR__ . '/auth.php';
