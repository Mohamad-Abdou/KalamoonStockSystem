<?php

use App\Http\Controllers\PeriodicRequests\PeriodicRequestController;
use App\Http\Controllers\PeriodicRequests\PeriodicRequestFlowController;

use Illuminate\Support\Facades\Route;

Route::resource('/periodic-request', PeriodicRequestController::class)->middleware(['auth']);
Route::resource('/periodic-request-flow', PeriodicRequestFlowController::class)
    ->middleware(['auth', 'PeriodicFlow']);