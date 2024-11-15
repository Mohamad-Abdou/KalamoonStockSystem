<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemsGroupController;
use App\Http\Controllers\ProfileController;
use App\Models\Items_group;
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

Route::resource('/items', ItemController::class)->middleware(['auth', 'verified']);
Route::resource('/items_groups', ItemsGroupController::class)->only(['store', 'index'])->middleware(['auth', 'verified']);
require __DIR__.'/auth.php';
