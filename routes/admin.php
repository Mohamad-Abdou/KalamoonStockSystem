<?php

use App\Http\Controllers\AdminActionController;
use Illuminate\Support\Facades\Route;

//Admin Configration Routes
Route::controller(AdminActionController::class)->group(function () {
    Route::put('/admin/annual-requests/update-period', 'updatePeriod')->name('admin.annual-requests.update-period');
    Route::get('/admin/config', 'config')->name('app.configure');
})->middleware(['CheckAdmin', 'auth']);

Route::middleware(['auth'])->group(function () {
    Route::resource('users', App\Http\Controllers\UserController::class);
});