<?php

use App\Http\Controllers\Management\ItemController;
use App\Http\Controllers\Management\ItemGroupController;

use Illuminate\Support\Facades\Route;


Route::resource('/items', ItemController::class)->middleware(['auth']);
Route::resource('/item_groups', ItemGroupController::class)->only(['store', 'index'])->middleware(['auth']);