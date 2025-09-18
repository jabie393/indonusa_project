<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\Guest\OrderController;
use App\Http\Controllers\Guest\KeranjangController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminPTController;


// Guest Routes
Route::get('/', function () {
    return view('guest.welcome');
});

// Route user untuk lihat daftar barang
Route::get('/order', [OrderController::class, 'index'])->name('order');
// End guest routes


Route::post('/keranjang/tambah', [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
Route::post('/keranjang/kurangi/{id}', [KeranjangController::class, 'kurangi'])->name('keranjang.kurangi');
Route::post('/keranjang/hapus/{id}', [KeranjangController::class, 'hapus'])->name('keranjang.hapus');
Route::post('/keranjang/checkout', [KeranjangController::class, 'checkout'])->name('keranjang.checkout');


Route::get('storage/{filename}', function ($filename) {
    $path = storage_path('app/public/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    return Response::make($file, 200)->header("Content-Type", $type);
});

// Admin Routes
Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/barang', [BarangController::class, function () {
    return view('admin.barang.index');
}])->name('barang.index');
Route::resource('/barang', BarangController::class);
// End Admin Routes


// Admin PT
Route::middleware(['auth'])->group(function () {
    Route::middleware(['role:admin_PT'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            // Dashboard
            Route::get('/dashboard', [AdminPTController::class, 'dashboard'])
                ->name('dashboard');

            // Orders
            Route::get('/orders/incoming', [AdminPTController::class, 'incoming'])
                ->name('orders.incoming');
            Route::get('/orders/{id}', [AdminPTController::class, 'show'])
                ->name('orders.show');
            Route::post('/orders/{id}/approve', [AdminPTController::class, 'approve'])
                ->name('orders.approve');
            Route::post('/orders/{id}/reject', [AdminPTController::class, 'reject'])
                ->name('orders.reject');
            Route::get('/orders/history', [AdminPTController::class, 'history'])
                ->name('orders.history');
        });
});



require __DIR__ . '/auth.php';
