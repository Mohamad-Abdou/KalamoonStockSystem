<?php

use App\Http\Controllers\TemporaryRequests\TemporaryRequestController;
use Illuminate\Support\Facades\Route;

Route::resource('temporary_requests', TemporaryRequestController::class)->middleware('auth')->only('index', 'create');