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
        Route::get('waste-types', [\App\Http\Controllers\Admin\WasteTypeController::class, 'index'])->name('waste-types.index');
        Route::post('waste-types', [\App\Http\Controllers\Admin\WasteTypeController::class, 'store'])->name('waste-types.store');
        Route::put('waste-types/{waste_type}', [\App\Http\Controllers\Admin\WasteTypeController::class, 'update'])->name('waste-types.update');
        Route::patch('waste-types/{waste_type}/toggle-status', [\App\Http\Controllers\Admin\WasteTypeController::class, 'toggleStatus'])->name('waste-types.toggle-status');
        Route::get('services', [\App\Http\Controllers\Admin\ServiceController::class, 'index'])->name('services.index');
        Route::post('services', [\App\Http\Controllers\Admin\ServiceController::class, 'store'])->name('services.store');
        Route::get('services/{service}', [\App\Http\Controllers\Admin\ServiceController::class, 'show'])->name('services.show');
        Route::put('services/{service}', [\App\Http\Controllers\Admin\ServiceController::class, 'update'])->name('services.update');
        Route::patch('services/{service}/toggle-status', [\App\Http\Controllers\Admin\ServiceController::class, 'toggleStatus'])->name('services.toggle-status');
        Route::post('services/{service}/pricing-rules', [\App\Http\Controllers\Admin\ServiceController::class, 'storePricingRule'])->name('services.pricing-rules.store');
        Route::patch('services/{service}/pricing-rules/{pricingRule}/toggle-status', [\App\Http\Controllers\Admin\ServiceController::class, 'togglePricingRuleStatus'])->name('services.pricing-rules.toggle-status');
        Route::resource('schedules', \App\Http\Controllers\Admin\ScheduleController::class)->only(['index', 'create', 'store', 'edit', 'update']);
        Route::patch('schedules/{schedule}/assign-collector', [\App\Http\Controllers\Admin\ScheduleController::class, 'assignCollector'])->name('schedules.assign-collector');
        Route::patch('schedules/{schedule}/cancel', [\App\Http\Controllers\Admin\ScheduleController::class, 'cancel'])->name('schedules.cancel');
        Route::resource('bills', \App\Http\Controllers\Admin\BillController::class)->only(['index', 'create', 'store', 'show']);

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

Route::middleware(['auth', 'verified', 'role:customer'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Customer\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/schedule', [\App\Http\Controllers\Customer\ScheduleController::class, 'index'])->name('schedule.index');
        Route::get('/bills', [\App\Http\Controllers\Customer\BillController::class, 'index'])->name('bills.index');
        Route::get('/bills/{bill}', [\App\Http\Controllers\Customer\BillController::class, 'show'])->name('bills.show');
    });

Route::middleware(['auth', 'verified', 'role:collector'])
    ->prefix('collector')
    ->name('collector.')
    ->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Collector\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/schedules', [\App\Http\Controllers\Collector\ScheduleController::class, 'index'])->name('schedules.index');
        Route::get('/schedules/{schedule}', [\App\Http\Controllers\Collector\ScheduleController::class, 'show'])->name('schedules.show');
        Route::patch('/schedules/{schedule}/start', [\App\Http\Controllers\Collector\ScheduleController::class, 'start'])->name('schedules.start');
        Route::post('/schedules/{schedule}/complete', [\App\Http\Controllers\Collector\ScheduleController::class, 'complete'])->name('schedules.complete');
        Route::patch('/schedules/{schedule}/missed', [\App\Http\Controllers\Collector\ScheduleController::class, 'missed'])->name('schedules.missed');
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
