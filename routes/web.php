<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PeminjamanBarangController;
use App\Http\Controllers\PengadaanController;
use App\Http\Controllers\RfidController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'index']);

Route::get('/login', [AuthController::class, 'loginPage']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);

Route::middleware(['roleCheck:admin,staff'])->group(function () {
    Route::get('/barang', [BarangController::class, 'index']);
    Route::post('/barang', [BarangController::class, 'store']);
    Route::put('/barang/{barang}', [BarangController::class, 'update']);
    Route::delete('/barang/{barang}', [BarangController::class, 'destroy']);

    Route::get('/peminjaman', [PeminjamanBarangController::class, 'index']);
    Route::post('/peminjaman', [PeminjamanBarangController::class, 'store']);
    Route::put('/peminjaman/{peminjaman}', [PeminjamanBarangController::class, 'update']);

    Route::get('/rfid', [RfidController::class, 'index']);

    Route::get('/pengadaan', [PengadaanController::class, 'index']);
    Route::post('/pengadaan', [PengadaanController::class, 'store']);
});