<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AuthController;

// =================== AUTH (CREATOR LOGIN GUNA EMAIL & OTP) ===================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'sendTac'])->name('login.send');
Route::post('/login/verify', [AuthController::class, 'verifyTac'])->name('login.verify');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =================== TRANSAKSI - KHAS UNTUK CREATOR SAHAJA (PERLU LOGIN) ===================
Route::group([], function () {
    Route::get('/create', function () {
        if (!session('auth_email')) return redirect()->route('login');
        return app(\App\Http\Controllers\TransactionController::class)->create();
    })->name('transaction.create');

    Route::post('/create', function () {
        if (!session('auth_email')) return redirect()->route('login');
        return app(\App\Http\Controllers\TransactionController::class)->store(request());
    })->name('transaction.store');

    Route::get('/create/link/{id}', function ($id) {
        if (!session('auth_email')) return redirect()->route('login');
        return app(\App\Http\Controllers\TransactionController::class)->linkForm($id);
    })->name('transaction.link.form');

    Route::post('/create/link/{id}', function ($id) {
        if (!session('auth_email')) return redirect()->route('login');
        return app(\App\Http\Controllers\TransactionController::class)->link($id, request());
    })->name('transaction.link');
});

// =================== TRANSAKSI - UNTUK AKSES TERBUKA (PIHAK KEDUA) ===================
Route::get('/tx/{id}', [TransactionController::class, 'show'])->name('transaction.view');
Route::post('/tx/{id}/verify', [TransactionController::class, 'verify'])->name('transaction.verify');

// ✅ ROUTE BARU UNTUK PEMBAYARAN (BUYER SAHAJA)
Route::post('/tx/{id}/pay', [TransactionController::class, 'pay'])->name('transaction.pay');

// 🔁 (optional) CALLBACK DARI BILLPLZ – belum dibuat tetapi boleh tambah seperti di bawah jika perlu:
// Route::post('/billplz/callback/{id}', [TransactionController::class, 'handleCallback'])->name('billplz.callback');

// =================== ROOT / DEFAULT LANDING ===================
Route::get('/', function () {
    return view('welcome');
});
