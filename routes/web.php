<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PickupController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ServiceController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function() {
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/master/customers', [CustomerController::class, 'index']);
    Route::post('/master/customers', [CustomerController::class, 'store']);

    Route::get('/master/users', [UserController::class, 'index']);
    Route::get('/master/users/create', [UserController::class, 'create']);
    Route::post('/master/users', [UserController::class, 'store']);
    Route::get('/master/users/{id}/edit', [UserController::class, 'edit']);
    Route::put('/master/users/{id}', [UserController::class, 'update']);
    Route::delete('/master/users/{id}', [UserController::class, 'destroy']);

    Route::get('/master/services', [ServiceController::class, 'index']);
    Route::get('/master/services/create', [ServiceController::class, 'create']);
    Route::post('/master/services', [ServiceController::class, 'store']);
    Route::get('/master/services/{id}/edit', [ServiceController::class, 'edit']);
    Route::put('/master/services/{id}', [ServiceController::class, 'update']);
    Route::delete('/master/services/{id}', [ServiceController::class, 'destroy']);

    Route::get('/master/vouchers', [VoucherController::class, 'index']);
    Route::get('/master/vouchers/create', [VoucherController::class, 'create']);
    Route::post('/master/vouchers', [VoucherController::class, 'store']);
    Route::post('/master/vouchers/generate', [VoucherController::class, 'generate'])->name('vouchers.generate');
    Route::post('/master/vouchers/check', [VoucherController::class, 'check'])->name('vouchers.check');

    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::get('/transactions/create', [TransactionController::class, 'create']);
    Route::post('/transactions', [TransactionController::class, 'store']);

    Route::get('/pickups', [PickupController::class, 'index']);
    Route::get('/pickups/create', [PickupController::class, 'create']);
    Route::post('/pickups', [PickupController::class, 'store']);

    Route::get('/reports', [ReportController::class, 'index']);
});
