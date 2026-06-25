<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ExpiryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ========== PUBLIC ROUTES ==========
Route::get('/', [HomeController::class, 'index'])->name('home');

// ========== AUTHENTICATED ROUTES ==========
Route::middleware(['auth'])->group(function () {

    // ─── Dashboard ───
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ─── Medicines ───
    Route::resource('medicines', MedicineController::class);
    Route::get('/medicines/{medicine}/edit', [MedicineController::class, 'edit'])->name('medicines.edit');
    Route::post('/medicines/import', [MedicineController::class, 'import'])->name('medicines.import');
    Route::get('/medicines/export', [MedicineController::class, 'export'])->name('medicines.export');

    // ─── Inventory ───
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::post('/inventory/stock-in', [InventoryController::class, 'stockIn'])->name('inventory.stock-in');
    Route::post('/inventory/stock-out', [InventoryController::class, 'stockOut'])->name('inventory.stock-out');
    Route::post('/inventory/adjust', [InventoryController::class, 'adjust'])->name('inventory.adjust');
    Route::post('/inventory/transfer', [InventoryController::class, 'transfer'])->name('inventory.transfer');
    Route::get('/inventory/history', [InventoryController::class, 'history'])->name('inventory.history');

    // ─── POS ───
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/add-to-cart', [PosController::class, 'addToCart'])->name('pos.add-to-cart');
    Route::post('/pos/remove-from-cart', [PosController::class, 'removeFromCart'])->name('pos.remove-from-cart');
    Route::post('/pos/update-cart', [PosController::class, 'updateCart'])->name('pos.update-cart');
    Route::post('/pos/clear-cart', [PosController::class, 'clearCart'])->name('pos.clear-cart');
    Route::post('/pos/checkout', [PosController::class, 'checkout'])->name('pos.checkout');
    Route::get('/pos/invoice/{sale}', [PosController::class, 'invoice'])->name('pos.invoice');

    // ─── Sales ───
    Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
    Route::get('/sales/{sale}', [SalesController::class, 'show'])->name('sales.show');

    // ─── Customers ───
    Route::resource('customers', CustomerController::class);
    Route::get('/customers/{customer}/purchases', [CustomerController::class, 'purchases'])->name('customers.purchases');

    // ─── Suppliers ───
    Route::resource('suppliers', SupplierController::class);

    // ─── Purchases ───
    Route::resource('purchases', PurchaseController::class);
    Route::post('/purchases/{purchase}/receive', [PurchaseController::class, 'receive'])->name('purchases.receive');

    // ─── Reports ───
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
    Route::get('/reports/export/{type}', [ReportController::class, 'export'])->name('reports.export');

    // ─── Expiry Center ───
    Route::get('/expiry', [ExpiryController::class, 'index'])->name('expiry.index');
    Route::post('/expiry/mark-expired', [ExpiryController::class, 'markExpired'])->name('expiry.mark-expired');

    // ─── Notifications ───
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-read', [NotificationController::class, 'markRead'])->name('notifications.mark-read');
    Route::post('/notifications/clear-all', [NotificationController::class, 'clearAll'])->name('notifications.clear-all');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');

    // ─── Users ───
    Route::resource('users', UserController::class);

    // ─── Settings ───
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // ─── Subscription ───
    Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription.index');
    Route::post('/subscription/upgrade', [SubscriptionController::class, 'upgrade'])->name('subscription.upgrade');

    // ─── Help Center ───
    Route::get('/help', [HelpController::class, 'index'])->name('help.index');
});

// ========== AUTH ROUTES ==========
require __DIR__.'/auth.php';