<?php

use App\Http\Controllers\LogRfidController;
use App\Http\Controllers\RfidController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/rfid', [RfidController::class, 'getIndex']);
Route::post('/rfidLogs', [LogRfidController::class, 'store']);