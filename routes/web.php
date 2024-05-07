<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

Route::get('/home', [LoginController::class, 'home'])->name('home');

Route::get('/tables', [LoginController::class, 'tables'])->name('tables');

Route::post('/check', [LoginController::class, 'check']);

Route::get('/', [LoginController::class, 'login'])->name('login');

