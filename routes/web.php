<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConvertController;
use App\Http\Controllers\GraphicsController;

Route::get('/', [ConvertController::class, 'show'])->name('main.page');

Route::get('/select-currency', [GraphicsController::class, 'select'])->name('select.page');

Route::get('/currency-graphics', [GraphicsController::class, 'show'])->name('show.graphic');

Route::post('/convert', [ConvertController::class, 'convert'])->name('convert.currency');

Route::post('/make-graphic', [GraphicsController::class, 'makeGraphic'])->name('make.graphic');