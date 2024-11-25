<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    SparepartController,
    ServisController,
};

// Rute untuk halaman utama
Route::get('/', function () {
    return view('home');
});

// Rute untuk halaman utama
Route::get('/detail', function () {
    return view('servis.alt');
});

// Rute untuk halaman utama
Route::get('/hasil', function () {
    return view('servis.result');
});

// Rute untuk Sparepart
Route::get('/sparepart', [SparepartController::class, 'index'])->name('sparepart.index');
Route::get('/sparepart/create', [SparepartController::class, 'create'])->name('sparepart.create');
Route::post('/sparepart', [SparepartController::class, 'store'])->name('sparepart.store');
Route::get('/sparepart/{id}', [SparepartController::class, 'show'])->name('sparepart.show');
Route::get('/sparepart/{id}/edit', [SparepartController::class, 'edit'])->name('sparepart.edit');
Route::put('/sparepart/{id}', [SparepartController::class, 'update'])->name('sparepart.update');
Route::delete('/sparepart/{id}', [SparepartController::class, 'destroy'])->name('sparepart.destroy');

// Rute untuk Servis
Route::get('/servis', [ServisController::class, 'index'])->name('servis.index');
Route::get('/servis/create', [ServisController::class, 'create'])->name('servis.create');
Route::post('/servis', [ServisController::class, 'store'])->name('servis.store');
Route::get('servis/{id}', [ServisController::class, 'show'])->name('servis.show');
Route::get('/servis/{id}/edit', [ServisController::class, 'edit'])->name('servis.edit');
Route::put('/servis/{id}', [ServisController::class, 'update'])->name('servis.update');
Route::delete('/servis/{id}', [ServisController::class, 'destroy'])->name('servis.destroy');

