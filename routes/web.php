<?php

use Illuminate\Support\Facades\Route;



Route::get('/register', function () { return view('auth.register'); });

use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

use App\Http\Controllers\DashboardController;

Route::get('/', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

use App\Http\Controllers\DokterController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\RuangController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\RekamMedisController;

Route::middleware('auth')->group(function () {
    Route::get('/dokter', [DokterController::class, 'index'])->name('dokter.index');
    Route::get('/pasien', [PasienController::class, 'index'])->name('pasien.index');
    Route::get('/obat', [ObatController::class, 'index'])->name('obat.index');
    Route::get('/ruang', [RuangController::class, 'index'])->name('ruang.index');
    Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna.index');
    Route::get('/rekam-medis', [RekamMedisController::class, 'index'])->name('rekam_medis.index');
});
