<?php

use App\Http\Controllers\AnnualRequests\AnnualRequestController;
use App\Http\Controllers\AnnualRequests\AnnualRequestFlowController;
use App\Http\Controllers\Stock\RepoertsController;
use Illuminate\Support\Facades\Route;



// Annual Request Routes
Route::resource('/annual-request', AnnualRequestController::class)->middleware(['auth']);
Route::resource('/annual-request-flow', AnnualRequestFlowController::class)
    ->middleware(['auth', 'AnnualFlow'])
    ->parameters(['annual-request-flow' => 'annual_request']);

Route::get('/archive', [AnnualRequestController::class, 'archive'])->name('annual-requests.archive')->middleware(['auth']);

// Annual Request Accessories 
Route::prefix('reports')->group(function () {
    Route::get('/annual-request', [RepoertsController::class, 'annualRequest'])->name('reports.annual-request')->middleware(['auth']);
});
Route::get('/reset-year', [AnnualRequestController::class, 'resetYear'])->name('annual-request.reset')->middleware(['auth']);
Route::get('/balance-manager/{request?}', [AnnualRequestController::class, 'manageBalances'])->name('annual-request.balanes')->middleware(['auth']);

