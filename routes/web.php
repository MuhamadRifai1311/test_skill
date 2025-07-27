<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResepController;


Route::get('/', [ResepController::class, 'index'])->name('resep.index');
Route::get('/resep/non-racikan/create', [ResepController::class, 'create'])->name('resep.create');
Route::get('/resep/get-obat', [ResepController::class, 'getObat']);

Route::get('/resep/get-signa', [ResepController::class, 'getSigna']);
Route::post('/resep/store', [ResepController::class, 'store'])->name('resep.store');

Route::get('/resep/racikan/create', [ResepController::class, 'createRacikan'])->name('resep.racikan.create');
Route::post('/resep/racikan/store', [ResepController::class, 'storeRacikan'])->name('resep.racikan.store');
Route::get('/resep/{id}', [ResepController::class, 'show'])->name('resep.show');
Route::get('/resep/cetak/{id}', [ResepController::class, 'cetak'])->name('resep.cetak');