<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MahasiswaAspirationController;
use App\Http\Controllers\AdminAspirationController;

Route::view('/', 'welcome')->name('home');

// --- Autentikasi ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// --- Rute Mahasiswa (RBAC: hanya role mahasiswa) ---
Route::middleware(['auth', 'role.mahasiswa'])
    ->prefix('mahasiswa')
    ->name('mahasiswa.')
    ->group(function () {
        Route::get('/dashboard', [MahasiswaAspirationController::class, 'index'])->name('dashboard');
        Route::post('/aspirations', [MahasiswaAspirationController::class, 'store'])->name('aspirations.store');
        Route::delete('/aspirations/{id}', [MahasiswaAspirationController::class, 'destroy'])->whereNumber('id')->name('aspirations.destroy');
        Route::get('/aspirations/{id}/pdf', [MahasiswaAspirationController::class, 'exportPdf'])->whereNumber('id')->name('aspirations.pdf');
    });

// --- Rute Admin (RBAC: hanya role admin) ---
Route::middleware(['auth', 'role.admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminAspirationController::class, 'index'])->name('dashboard');
        Route::patch('/aspirations/{id}', [AdminAspirationController::class, 'updateStatus'])->whereNumber('id')->name('aspirations.update');
        Route::delete('/aspirations/{id}', [AdminAspirationController::class, 'destroy'])->whereNumber('id')->name('aspirations.destroy');
        Route::get('/export/pdf', [AdminAspirationController::class, 'exportMonthlyPdf'])->name('export.pdf');
        Route::get('/export/csv', [AdminAspirationController::class, 'exportMonthlyCsv'])->name('export.csv');
    });
