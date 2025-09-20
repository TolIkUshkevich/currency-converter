<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConvertController;
use App\Http\Controllers\GraphicsController;

Route::get('/', [ConvertController::class, 'show'])->name('main.page');

Route::get('/select-currency', [GraphicsController::class, 'select'])->name('select.page');

Route::get('/currency-graphics', [GraphicsController::class, 'show'])->name('show.graphic');

Route::post('/convert', [ConvertController::class, 'convert'])->name('convert.currency');

Route::post('/make-graphic', [GraphicsController::class, 'makeGraphic'])->name('make.graphic');

Route::get('/__debug_assets', function () {
    $buildDir = public_path('build');
    $assets = is_dir($buildDir) ? array_values(array_diff(scandir($buildDir), ['.', '..'])) : null;
    $assetsAssets = is_dir($buildDir . '/assets') ? array_values(array_diff(scandir($buildDir . '/assets'), ['.', '..'])) : null;
    $manifestPath = $buildDir . '/manifest.json';
    $manifest = file_exists($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : null;

    return response()->json([
        'public_exists' => file_exists(public_path('index.php')),
        'build_dir_exists' => is_dir($buildDir),
        'build_listing' => $assets,
        'build_assets_listing' => $assetsAssets,
        'manifest_exists' => (bool) $manifest,
        'manifest_keys' => $manifest ? array_slice(array_keys($manifest), 0, 20) : null,
        'manifest_sample' => $manifest ? array_slice($manifest, 0, 5) : null,
    ]);
});