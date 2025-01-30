<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    SparepartController,
    ServiceController,
    VehicleController,
    CustomerController,
    NotificationController,
    TransactionController,
    
    ForgotPasswordController,
    AutentikasiController,
    EmergencyPasswordController,
    DaftarAkunController,
    DashboardController,
    hapusAkunUserController,
    ProfileController,
};

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Guest
Route::middleware('guest')->group(function () {

    // login
    Route::get('/login', [AutentikasiController::class, 'create'])->name('login');
    Route::post('/login', [AutentikasiController::class, 'store']);
    
    // lupa password
    Route::get('/lupa-password', [ForgotPasswordController::class, 'showResetForm'])->name('lupa.password');
    Route::post('/lupa-password', [ForgotPasswordController::class, 'reset']); 
    
});

// Dashboard
// Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

/**
 * Sparepart Routes
 */
// Route::prefix('sparepart')->name('sparepart.')->group(function () {
//     Route::get('/', [SparepartController::class, 'index'])->name('index');
//     Route::get('create', [SparepartController::class, 'create'])->name('create');
//     Route::post('/', [SparepartController::class, 'store'])->name('store');
//     Route::get('{id}', [SparepartController::class, 'show'])->name('show');
//     Route::get('{id}/edit', [SparepartController::class, 'edit'])->name('edit');
//     Route::put('{id}', [SparepartController::class, 'update'])->name('update');
//     Route::delete('{id}', [SparepartController::class, 'destroy'])->name('destroy');
//     Route::get('{id}/history', [SparepartController::class, 'history'])->name('history');
    
// });

// Auth
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');


    // Logout
    Route::get('/logout', [AutentikasiController::class, 'logout'])->name('logout');
    Route::get('/confirm-logout', [AutentikasiController::class, 'confirmLogout'])->name('confirm.logout');

    // ganti emergency_password
    Route::get('/superPrograms/emergencyPassword', [EmergencyPasswordController::class, 'index'])->name('gantiEmergencyPassword');
    Route::post('/superPrograms/emergencyPassword', [EmergencyPasswordController::class, 'ganti']);
    
    // daftar akun baru melalui admin
    Route::get('/daftar', [DaftarAkunController::class, 'create'])->name('profile.daftar');
    Route::post('/daftar', [DaftarAkunController::class, 'storeByAdmin']);
    
    // hapus akun melalui the engineer & (admin??)
    Route::get('/superPrograms/hapusAkunUser', [hapusAkunUserController::class, 'index'])->name('hapusAkunUser');
    Route::post('/superPrograms/hapusAkunUser', [hapusAkunUserController::class, 'hapusAkun']);


    // edit dan hapus akun user
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Ubah Password user
    // Route::put('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('update.password');
    Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('update.password');

    // Documentation Page
    Route::get('/documentation', fn() => view('documentation'))->name('documentation');

    /**
     * Sparepart Routes
     */
    Route::prefix('sparepart')->name('sparepart.')->group(function () {
        Route::get('/', [SparepartController::class, 'index'])->name('index');
        Route::get('create', [SparepartController::class, 'create'])->name('create');
        Route::post('/', [SparepartController::class, 'store'])->name('store');
        Route::get('{id}', [SparepartController::class, 'show'])->name('show');
        Route::get('{id}/edit', [SparepartController::class, 'edit'])->name('edit');
        Route::put('{id}', [SparepartController::class, 'update'])->name('update');
        Route::delete('{id}', [SparepartController::class, 'destroy'])->name('destroy');
        Route::get('{id}/history', [SparepartController::class, 'history'])->name('history');
    });

    /**
     * Transaction Routes
     */
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('index'); // Menampilkan daftar transaksi
        Route::get('create', [TransactionController::class, 'create'])->name('create'); // Form tambah transaksi baru
        Route::post('/', [TransactionController::class, 'store'])->name('store'); // Menyimpan transaksi baru
        Route::get('{id}', [TransactionController::class, 'show'])->name('show'); // Menampilkan detail transaksi
        Route::get('{id}/edit', [TransactionController::class, 'edit'])->name('edit'); // Form edit transaksi
        Route::put('{id}', [TransactionController::class, 'update'])->name('update'); // Memperbarui transaksi
        Route::delete('{id}', [TransactionController::class, 'destroy'])->name('destroy'); // Menghapus transaksi
    });

    /**
     * Service Checklist Routes
     */
    Route::prefix('service')->name('service.')->group(function () {

        Route::get('/', [ServiceController::class, 'index'])->name('index');
        Route::get('create/{vehicle_id}', [ServiceController::class, 'create'])->name('create');

        Route::post('/', [ServiceController::class, 'store'])->name('store');
        Route::post('/', [ServiceController::class, 'store'])->name('store-2');

        Route::get('{id}', [ServiceController::class, 'show'])->name('show');
        Route::get('{id}/edit', [ServiceController::class, 'edit'])->name('edit');
        Route::put('{id}', [ServiceController::class, 'update'])->name('update');
        Route::delete('{id}', [ServiceController::class, 'destroy'])->name('destroy');

        Route::post('{id}/checklist', [ServiceController::class, 'addChecklist'])->name('addChecklist');
        Route::patch('/checklist/{id}', [ServiceController::class, 'updateChecklistStatus'])->name('updateChecklistStatus');
        Route::patch('/checklist/{id}/task', [ServiceController::class, 'updateChecklistTask'])->name('updateChecklistTask');
        Route::get('/checklist/{id}/edit', [ServiceController::class, 'editChecklist'])->name('editChecklist');
        Route::delete('/checklist/{id}', [ServiceController::class, 'deleteChecklist'])->name('deleteChecklist');
    });

    /**
     * Notifications Routes
     */
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::get('unread-count', [NotificationController::class, 'unreadCount'])->name('unreadCount');
        Route::get('{id}/edit', [NotificationController::class, 'edit'])->name('edit');
        Route::put('{id}', [NotificationController::class, 'update'])->name('update');
    });

    /**
     * Customer Routes
     */
    Route::prefix('customer')->name('customer.')->group(function () {

        // Admin & kasir
            Route::get('/', [CustomerController::class, 'index'])->name('index');
            Route::get('create', [CustomerController::class, 'create'])->name('create');
            Route::post('/', [CustomerController::class, 'store'])->name('store');
            Route::get('{id}', [CustomerController::class, 'show'])->name('show');
            Route::get('{id}/edit', [CustomerController::class, 'edit'])->name('edit');
            Route::put('{id}', [CustomerController::class, 'update'])->name('update');
            Route::delete('{id}', [CustomerController::class, 'destroy'])->name('destroy');
            Route::post('{id}/restore', [CustomerController::class, 'restore'])->name('restore');
            Route::delete('{id}/force-delete', [CustomerController::class, 'forceDelete'])->name('forceDelete');
            Route::delete('deleted/force-delete-all', [CustomerController::class, 'forceDeleteAll'])->name('forceDeleteAll');
    });

    /**
     * Vehicle Routes
     */
    Route::prefix('vehicle')->name('vehicle.')->group(function () {
        Route::get('create/{customerId}', [VehicleController::class, 'create'])->name('create');
        Route::post('store', [VehicleController::class, 'store'])->name('store');
        Route::get('edit/{id}', [VehicleController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [VehicleController::class, 'update'])->name('update');
        Route::delete('destroy/{id}', [VehicleController::class, 'destroy'])->name('destroy');
        Route::get('{id}', [VehicleController::class, 'show'])->name('show');
    });
});



