<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Auth;


Route::name('user.')->group(function(){
    Route::view('/home', 'home')->middleware('auth')->name('home');
    Route::view('/tables', 'tables')->middleware('auth')->name('tables');


    Route::get('/', function(){
        if(Auth::check()){
            return redirect(route('user.home'));
        }
        return view('login');
    })->name(name:'login');

    Route::post('/', [LoginController::class, 'login']);
    
    Route::get('/logout',function(){
        Auth::logout();
        return redirect(route('user.login'));
    })->name('logout');

    Route::get('/registration', function(){
        if(Auth::check()){
            return redirect(route('user.home'));
        }
        return view('register');
    })->name('register');

   Route::post('/registration', [ RegisterController::class, 'save']);
});
