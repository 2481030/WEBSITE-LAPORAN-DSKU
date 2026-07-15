<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

use App\Http\Controllers\LaporanController;

Route::post('/laporan/simpan', [LaporanController::class, 'store'])->name('laporan.simpan');