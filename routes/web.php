<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    SparepartController,
    ServiceController,
    VehicleController,
    CustomerController
};

Route::get('/', function () {
    return view('home');
});

Route::get('/sparepart', [SparepartController::class, 'index'])->name('sparepart.index');
Route::get('/sparepart/create', [SparepartController::class, 'create'])->name('sparepart.create');
Route::post('/sparepart', [SparepartController::class, 'store'])->name('sparepart.store');
Route::get('/sparepart/{id}', [SparepartController::class, 'show'])->name('sparepart.show');
Route::get('/sparepart/{id}/edit', [SparepartController::class, 'edit'])->name('sparepart.edit');
Route::put('/sparepart/{id}', [SparepartController::class, 'update'])->name('sparepart.update');
Route::delete('/sparepart/{id}', [SparepartController::class, 'destroy'])->name('sparepart.destroy');

Route::get('/customer', [CustomerController::class, 'index'])->name('customer.index');
Route::get('/customer/create', [CustomerController::class, 'create'])->name('customer.create');
Route::post('/customer', [CustomerController::class, 'store'])->name('customer.store');
Route::get('/customer/{id}', [CustomerController::class, 'show'])->name('customer.show');
Route::get('/customer/{id}/edit', [CustomerController::class, 'edit'])->name('customer.edit');
Route::put('/customer/{id}', [CustomerController::class, 'update'])->name('customer.update');
Route::delete('/customer/{id}', [CustomerController::class, 'destroy'])->name('customer.destroy');
Route::post('customer/{id}/restore', [CustomerController::class, 'restore'])->name('customer.restore');
Route::delete('customer/{id}/force-delete', [CustomerController::class, 'forceDelete'])->name('customer.forceDelete');
Route::delete('/customers/deleted/force-delete-all', [CustomerController::class, 'forceDeleteAll'])->name('customer.forceDeleteAll');

Route::prefix('vehicle')->name('vehicle.')->group(function() {
    Route::get('create/{customerId}', [VehicleController::class, 'create'])->name('create');
    Route::post('store', [VehicleController::class, 'store'])->name('store');
    Route::get('edit/{id}', [VehicleController::class, 'edit'])->name('edit');
    Route::put('update/{id}', [VehicleController::class, 'update'])->name('update');
    Route::delete('destroy/{id}', [VehicleController::class, 'destroy'])->name('destroy');
    Route::get('{id}', [VehicleController::class, 'show'])->name('show');
});

Route::get('/service', [ServiceController::class, 'index'])->name('service.index');
Route::get('/service/create/{vehicle_id}', [ServiceController::class, 'create'])->name('service.create');
Route::post('/service', [ServiceController::class, 'store'])->name('service.store');
Route::get('/service/{id}', [ServiceController::class, 'show'])->name('service.show');
Route::get('/service/{id}/edit', [ServiceController::class, 'edit'])->name('service.edit');
Route::put('/service/{id}', [ServiceController::class, 'update'])->name('service.update');
Route::delete('/service/{id}', [ServiceController::class, 'destroy'])->name('service.destroy');