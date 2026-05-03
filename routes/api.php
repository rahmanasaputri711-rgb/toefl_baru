<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\BankSoalController;
use App\Http\Controllers\Admin\MateriController;
use App\Http\Controllers\User\KuisController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\SesiTesController;
use App\Http\Controllers\User\PendaftaranTesController;
use App\Http\Controllers\Admin\PendaftaranController;
use App\Http\Controllers\User\TesController;

// AUTH
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

// TEST
Route::get('/test-admin', function () {
    return 'Admin OK';
});

// BANK SOAL
Route::get('/admin/bank-soal', [BankSoalController::class, 'index']);
Route::post('/admin/bank-soal', [BankSoalController::class, 'store']);
Route::get('/admin/bank-soal/{id}', [BankSoalController::class, 'show']);
Route::put('/admin/bank-soal/{id}', [BankSoalController::class, 'update']);
Route::delete('/admin/bank-soal/{id}', [BankSoalController::class, 'destroy']);

// MATERI
Route::get('/admin/materi', [MateriController::class, 'index']);
Route::post('/admin/materi', [MateriController::class, 'store']);
Route::get('/admin/materi/{id}', [MateriController::class, 'show']);
Route::put('/admin/materi/{id}', [MateriController::class, 'update']);
Route::delete('/admin/materi/{id}', [MateriController::class, 'destroy']);


Route::get('/sesi-tes', [SesiTesController::class, 'index']);

Route::post('/daftar-tes', [PendaftaranTesController::class, 'daftar']);
Route::get('/admin/pendaftaran', [PendaftaranController::class, 'index']);
Route::put('/admin/pendaftaran/{id}', [PendaftaranController::class, 'update']);

Route::get('/tes/mulai', [TesController::class, 'mulai']);

// Route::middleware('auth')->group(function () {

//});