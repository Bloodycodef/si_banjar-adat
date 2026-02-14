<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\KategoriPemasukanController;
use App\Http\Controllers\api\KategoriPengeluaranController;
use App\Http\Controllers\api\Pemasukan;
use App\Http\Controllers\Api\Pengeluaran;
use Illuminate\Support\Facades\Route;

// LOGIN (tanpa JWT)
Route::post('/login', [AuthController::class, 'login']);

// ROUTE DENGAN JWT
Route::middleware('jwt.auth')->group(function () {

    // AUTH
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);

    // KATEGORI
    Route::apiResource(
        'kategori-pemasukan',
        KategoriPemasukanController::class
    );

    Route::apiResource(
        'kategori-pengeluaran',
        KategoriPengeluaranController::class
    );

    //PEMASUKAN
    Route::apiResource('pemasukan', Pemasukan::class);
    Route::apiResource('pengeluaran', Pengeluaran::class);
});
