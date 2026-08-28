<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function (\Illuminate\Http\Request $request) {
    return redirect()->route($request->user()->dashboardRouteName());
})->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('customers', \App\Http\Controllers\Admin\CustomerController::class);
        Route::patch('customers/{customer}/toggle-status', [\App\Http\Controllers\Admin\CustomerController::class, 'toggleStatus'])
            ->name('customers.toggle-status');

        Route::resource('collectors', \App\Http\Controllers\Admin\CollectorController::class);
        Route::patch('collectors/{collector}/toggle-status', [\App\Http\Controllers\Admin\CollectorController::class, 'toggleStatus'])
            ->name('collectors.toggle-status');
        Route::get('locations', [\App\Http\Controllers\Admin\LocationController::class, 'index'])->name('locations.index');
        Route::post('locations/provinces', [\App\Http\Controllers\Admin\LocationController::class, 'storeProvince'])->name('locations.provinces.store');
        Route::delete('locations/provinces/{province}', [\App\Http\Controllers\Admin\LocationController::class, 'destroyProvince'])->name('locations.provinces.destroy');
        Route::post('locations/cities', [\App\Http\Controllers\Admin\LocationController::class, 'storeCity'])->name('locations.cities.store');
        Route::delete('locations/cities/{city}', [\App\Http\Controllers\Admin\LocationController::class, 'destroyCity'])->name('locations.cities.destroy');
        Route::post('locations/barangays', [\App\Http\Controllers\Admin\LocationController::class, 'storeBarangay'])->name('locations.barangays.store');
        Route::delete('locations/barangays/{barangay}', [\App\Http\Controllers\Admin\LocationController::class, 'destroyBarangay'])->name('locations.barangays.destroy');
    });

Route::middleware(['auth', 'verified', 'role:customer'])->group(function () {
    Route::get('/customer/dashboard', function () {
        return view('customer.dashboard');
    })->name('customer.dashboard');
});

Route::middleware(['auth', 'verified', 'role:collector'])->group(function () {
    Route::get('/collector/dashboard', function () {
        return view('collector.dashboard');
    })->name('collector.dashboard');
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
