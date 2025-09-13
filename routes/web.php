<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\Guest\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('guest.welcome');
});

// Route khusus user untuk lihat daftar barang
Route::get('/order', [OrderController::class, 'index'])->name('order');

Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/pagetwo', function () {
    return view('admin.pagetwo');
})->middleware(['auth', 'verified'])->name('pagetwo');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/barang', [BarangController::class, function(){return view ('admin.barang.index');}])->name('barang.index');
Route::resource('/barang', BarangController::class);

require __DIR__ . '/auth.php';
