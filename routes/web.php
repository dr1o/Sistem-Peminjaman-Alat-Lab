<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::resource('equipments', EquipmentController::class);

    // Fitur Stok
    Route::post('/equipments/{id}/decrease', [EquipmentController::class, 'decreaseStock'])->name('equipments.decrease');
    Route::post('/equipments/{id}/clear', [EquipmentController::class, 'clearStock'])->name('equipments.clear');
    Route::post('/equipments/{id}/increase', [EquipmentController::class, 'increaseStock'])->name('equipments.increase');

    // Peminjaman (MAHASISWA)
    Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');
    Route::get('/riwayat-pinjam', [LoanController::class, 'index'])->name('loans.index');
    Route::post('/loans/{id}/return', [LoanController::class, 'returnEquipment'])->name('loans.return');

    // Peminjaman (ADMIN - PERSETUJUAN)
    Route::get('/laporan-pinjam', [LoanController::class, 'adminIndex'])->name('loans.admin');
    Route::post('/loans/{id}/approve-borrow', [LoanController::class, 'approveBorrow'])->name('loans.approve_borrow');
    Route::post('/loans/{id}/approve-return', [LoanController::class, 'approveReturn'])->name('loans.approve_return');
    Route::post('/loans/{id}/reject-borrow', [LoanController::class, 'rejectBorrow'])->name('loans.reject_borrow');
    
    // Admin Equipment
    Route::delete('/equipments/{id}', [EquipmentController::class, 'destroy'])
     ->name('equipments.destroy')
     ->middleware('admin');

    // Rute Trash Bin & Soft Deletes (ADMIN ONLY)
    Route::middleware(['admin'])->group(function () {
        Route::get('/equipments-trash', [EquipmentController::class, 'trash'])->name('equipments.trash');
        Route::post('/equipments-trash/{id}/restore', [EquipmentController::class, 'restore'])->name('equipments.restore');
        Route::delete('/equipments-trash/{id}/force-delete', [EquipmentController::class, 'forceDestroy'])->name('equipments.force_destroy');
    });

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';