<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MedicineController;
use Illuminate\Support\Facades\Route;

// डिफल्ट रुटलाई लगइनमा पठाउने
Route::get('/', function () {
    return redirect()->route('login');
});

// प्रमाणीकरण आवश्यक पर्ने समूह
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('medicines', MedicineController::class);
    Route::post('/medicines/import', [MedicineController::class, 'import'])->name('medicines.import');
    Route::get('/medicines/export', [MedicineController::class, 'export'])->name('medicines.export');
});

// Breeze को आफ्नै auth रुटहरू
require __DIR__.'/auth.php';