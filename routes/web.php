<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/management.php';
require __DIR__ . '/stock.php';
require __DIR__ . '/annual_requests.php';
require __DIR__ . '/periodic_requests.php';
require __DIR__ . '/temporary_requests.php';

