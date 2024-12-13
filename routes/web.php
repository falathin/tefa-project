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

Route::get('/documentation', function () {
    return view('documentation');
})->name('documentation');


Route::get('/', [DashboardController::class, 'index']);

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

// Route::prefix('sparepart')->name('transactions.')->group(function() {
//     Route::get('transactions', [TransactionController::class, 'index'])->name('index');
//     Route::get('transactions/create', [TransactionController::class, 'create'])->name('create');
//     Route::post('transactions', [TransactionController::class, 'store'])->name('store');
//     Route::get('transactions/{id}', [TransactionController::class, 'show'])->name('show');
//     Route::get('transactions/{id}/edit', [TransactionController::class, 'edit'])->name('edit');
//     Route::put('transactions/{id}', [TransactionController::class, 'update'])->name('update');
//     Route::delete('transactions/{id}', [TransactionController::class, 'destroy'])->name('destroy');
// });

Route::get('/service/{id}/show', [ServiceController::class, 'show'])->name('service.show');
Route::post('/service/{id}/checklist', [ServiceController::class, 'addChecklist'])->name('service.addChecklist');
Route::patch('/service/checklist/{id}', [ServiceController::class, 'updateChecklistStatus'])->name('service.updateChecklistStatus');
Route::patch('/service/checklist/{id}/task', [ServiceController::class, 'updateChecklistTask'])->name('service.updateChecklistTask'); // Updating task text
Route::get('/service/checklist/{id}/edit', [ServiceController::class, 'editChecklist'])->name('service.editChecklist');
Route::delete('/service/checklist/{id}', [ServiceController::class, 'deleteChecklist'])->name('service.deleteChecklist');

Route::prefix('notifications')->name('notifications.')->group(function() {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::post('{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
    Route::get('unread-count', [NotificationController::class, 'unreadCount'])->name('unreadCount');
    Route::get('{id}/edit', [NotificationController::class, 'edit'])->name('edit');
    Route::put('{id}', [NotificationController::class, 'update'])->name('update');
});

Route::prefix('customer')->name('customer.')->group(function() {
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

Route::prefix('vehicle')->name('vehicle.')->group(function() {
    Route::get('create/{customerId}', [VehicleController::class, 'create'])->name('create');
    Route::post('store', [VehicleController::class, 'store'])->name('store');
    Route::get('edit/{id}', [VehicleController::class, 'edit'])->name('edit');
    Route::put('update/{id}', [VehicleController::class, 'update'])->name('update');
    Route::delete('destroy/{id}', [VehicleController::class, 'destroy'])->name('destroy');
    Route::get('{id}', [VehicleController::class, 'show'])->name('show');
});

Route::prefix('service')->name('service.')->group(function() {
    Route::get('/', [ServiceController::class, 'index'])->name('index');
    Route::get('create/{vehicle_id}', [ServiceController::class, 'create'])->name('create');
    Route::post('/', [ServiceController::class, 'store'])->name('store');
    Route::get('{id}', [ServiceController::class, 'show'])->name('show');
    Route::get('{id}/edit', [ServiceController::class, 'edit'])->name('edit');
    Route::put('{id}', [ServiceController::class, 'update'])->name('update');
    Route::delete('{id}', [ServiceController::class, 'destroy'])->name('destroy');
});