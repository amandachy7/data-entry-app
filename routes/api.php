<?php

use App\Http\Controllers\Api\EntryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\ProductController;


Route::get('/stores', [EntryController::class, 'stores']);
Route::get('/stores/{store}/products', [EntryController::class, 'productsByStore']);
Route::get('/entries', [EntryController::class, 'index']);
Route::post('/entries', [EntryController::class, 'store']);
Route::get('/products', [EntryController::class, 'products']);
//stores
Route::post('/stores', [StoreController::class, 'store']);
Route::put('/stores/{store}', [StoreController::class, 'update']);
//products
Route::post('/products', [ProductController::class, 'store']);
Route::put('/products/{product}', [ProductController::class, 'update']);