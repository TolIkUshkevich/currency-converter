<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ConvertController;

Route::get('/', [PageController::class, 'main']);

Route::post('/convert', [ConvertController::class, 'convert']);