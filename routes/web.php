<?php

use App\Http\Controllers\DnController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CasemarkController;
use App\Http\Controllers\MatchingController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Route::get('/', function () {
//     return view('pages.dn');
// });
Route::post('/dn/import', [DnController::class, 'importDn'])->name('dn.import');
Route::get('/dn/download', [DnController::class, 'index'])->name('dn.download');
Route::get('/dn/upload', [DnController::class, 'index'])->name('dn.upload');
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/dn/data', [DnController::class, 'getDnData'])->name('dn.data');
Route::get('/dn/filtered-options', [DnController::class, 'getFilteredDnOptions'])->name('dn.filtered-options');

// Casemark routes
Route::get('/casemark/data', [CasemarkController::class, 'getCasemarkData'])->name('casemark.data');
Route::get('/casemark/filtered-options', [CasemarkController::class, 'getFilteredCasemarkOptions'])->name('casemark.filtered-options');

//Transactions routes
Route::get('/', [MatchingController::class, 'index'])->name('matching');
Route::get('/transactions/data', [MatchingController::class, 'getTransactions'])->name('transactions.data');
Route::post('/matching/store', [MatchingController::class, 'store'])->name('matching.store');
Route::post('/matching/unlock', [MatchingController::class, 'unlock'])->name('matching.unlock');
Route::post('/matching/reset', [MatchingController::class, 'resetSession'])->name('matching.reset');
Route::post('/matching/resetWithPassword', [MatchingController::class, 'resetSessionWithPassword'])->name('matching.resetWithPassword');
Route::get('/transactions/print', [TransactionController::class, 'printDN'])->name('transaction.printDn');
Route::get('export/transactions', [DnController::class, 'exportTransactions'])->name('export.transactions');

// Protected verify routes - only authenticated users can verify
Route::middleware('auth')->group(function () {
    Route::post('/dn/verify', [DnController::class, 'verify'])->name('dn.verify');
    Route::get('/dn/verify/{dn_no}', [DnController::class, 'getVerify'])->name('dn.getVerify');
});