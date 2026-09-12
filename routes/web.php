<?php

use App\Http\Controllers\PhotoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PhotoController::class, 'dashboard'])->name('dashboard');

Route::get('/gallery', [PhotoController::class, 'index'])->name('gallery.index');

Route::get('/photos/create', [PhotoController::class, 'create'])->name('photos.create');
Route::post('/photos', [PhotoController::class, 'store'])->name('photos.store');
Route::delete('/photos/{photo}', [PhotoController::class, 'destroy'])->name('photos.destroy');