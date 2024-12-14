<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    SparepartController,
    ServiceController,
    VehicleController,
    CustomerController,
    NotificationController,
    DashboardController,
    TransactionController
};

// Documentation Page
Route::get('/documentation', fn() => view('documentation'))->name('documentation');

// Dashboard
Route::get('/', [DashboardController::class, 'index']);

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
 * Service Checklist Routes
 */
Route::prefix('service')->name('service.')->group(function () {
    Route::get('/', [ServiceController::class, 'index'])->name('index');
    Route::get('create/{vehicle_id}', [ServiceController::class, 'create'])->name('create');
    Route::post('/', [ServiceController::class, 'store'])->name('store');
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