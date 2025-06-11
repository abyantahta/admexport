<?php

use App\Http\Controllers\DnController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('pages.dn');
});
Route::get('/dn/upload', [DnController::class, 'index'])->name('pcc.upload');
Route::get('/users', [UserController::class, 'index'])->name('users.index');