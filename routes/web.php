<?php

use App\Http\Controllers\AdminActionController;
use App\Http\Controllers\AnnualRequestController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemGroupController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::controller(AdminActionController::class)->group(function () {
    Route::put('/admin/annual-requests/update-period', 'updatePeriod')->name('admin.annual-requests.update-period');
    Route::get('/admin/config', 'config')->name('app.configure');
})->middleware(['CheckAdmin', 'auth']);

Route::resource('/items', ItemController::class)->middleware(['auth', 'verified']);
Route::resource('/item_groups', ItemGroupController::class)->only(['store', 'index'])->middleware(['auth', 'verified']);
Route::resource('/annual-request', AnnualRequestController::class)->middleware(['auth', 'verified', 'CheckRequestPeriod']);

require __DIR__.'/auth.php';
