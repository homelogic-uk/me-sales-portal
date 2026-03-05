<?php

use App\Http\Controllers\ContractController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExternalController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\QuoteController;
use Illuminate\Support\Facades\Route;

Route::match(['GET', 'POST'], 'login', [LoginController::class, 'login'])->name('login');
// Dashboard
Route::middleware('auth')->get('/', [DashboardController::class, 'index'])->name('dashboard.index');

Route::prefix('external')->name('external.')->group(function () {
    Route::match(['GET', 'POST'], 'signing/{documentId}', [ExternalController::class, 'signing'])->name('signing');
    Route::match(['GET', 'POST'], 'complete/{documentId}', [ExternalController::class, 'complete'])->name('complete');
});

// Leads, Quotes, and Contracts grouped by lead ID
Route::middleware(['auth', 'check.for.contract'])->prefix('view/{id}')->name('leads.')->group(function () {

    // Lead view
    Route::match(['GET', 'POST'], '/', [LeadController::class, 'view'])->name('view');

    // Quotes
    Route::prefix('quote')->name('quote.')->group(function () {
        Route::match(['GET', 'POST', 'DELETE'], '/', [QuoteController::class, 'view'])->name('view');
        Route::match(['POST'], '/discount/apply', [QuoteController::class, 'addDiscount'])->name('addDiscount');
        Route::match(['DELETE'], '/discount/delete', [QuoteController::class, 'removeDiscount'])->name('removeDiscount');
        Route::match(['GET', 'POST'], '/{product}', [QuoteController::class, 'create'])->name('create');
    });

    // Contracts
    Route::prefix('contract')->name('contract.')->group(function () {
        Route::match(['GET', 'PUT'], '/', [ContractController::class, 'details'])->name('details');
        Route::match(['GET', 'PUT'], '/generate/{uuid}', [ContractController::class, 'generate'])->name('generate');
        Route::match(['GET', 'PUT', 'POST'], '/signing/{uuid}', [ContractController::class, 'signing'])->name('signing');
        Route::match(['GET', 'PUT'], '/complete/{uuid}', [ContractController::class, 'complete'])->name('complete');
        Route::match(['GET', 'PUT'], '/sent/{uuid}', [ContractController::class, 'sentToClient'])->name('sent-to-client');

        Route::get('status/{uuid}', [ContractController::class, 'status'])->name('status');
    });
});
