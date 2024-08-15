<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CityController;

Route::get('/', [CityController::class, 'index'])->name('city.index');

Route::post('/search', [CityController::class, 'search'])->name('city.search');

Route::get('/cities/{name}', [CityController::class, 'show'])->name('city.show');

Route::get('/city/{id}', [CityController::class, 'show'])->name('city.show');

Route::get('/autocomplete', [CityController::class, 'autocomplete'])->name('autocomplete');