<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MainContentController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminTablesController;
use Illuminate\Support\Facades\Auth;


Route::name('user.')->group(function(){
    Route::get('/tables', [ MainContentController::class, 'showTables'])->middleware('auth')->name('tables');
    Route::get('/tables/{tableName}/edit', [MainContentController::class, 'editTable'])->middleware('auth')->name('tables.edit');
    Route::put('/tables/{tableName}/update/{id}', [MainContentController::class, 'updateTable'])->middleware('auth')->name('tables.update');
    Route::get('/tables/{tableName}/edit/add', [MainContentController::class, 'addTable'])->middleware('auth')->name('tables.add');
    Route::post('/tables/{tableName}/edit/add', [MainContentController::class, 'addTable'])->middleware('auth')->name('tables.add');
    Route::delete('/tables/{tableName}/delete/{id}', [MainContentController::class, 'destroy'])->middleware('auth')->name('tables.delete');

    Route::get('/sales/{id_sale}/details', [MainContentController::class, 'getSaleDetails'])->middleware('auth')->name('sales.details');
    Route::get('/supplies/{id_supply}/details', [MainContentController::class, 'getSupplyDetails'])->middleware('auth')->name('supplies.details');
    Route::get('/product/{id_product}/details', [MainContentController::class, 'getProductDetails'])->middleware('auth')->name('product.details');

    Route::get('/home', [ MainContentController::class, 'showHome'])->middleware('auth')->name('home');
    Route::get('/profile', [ ProfileController::class, 'show'])->middleware('auth')->name('profile');

    Route::get('/supplies', [MainContentController::class, 'showSupplies'])->middleware('auth')->name('supplies');
    Route::post('/supplies', [MainContentController::class, 'storeSupply'])->middleware('auth')->name('store.supply');


    Route::get('/admintables', [ AdminTablesController::class, 'showTables'])->middleware('auth')->name('admintables');
    Route::get('/admintables/{tableName}/edit', [AdminTablesController::class, 'editTable'])->middleware('auth')->name('admintables.edit');
    Route::put('/admintables/{tableName}/update/{id}', [AdminTablesController::class, 'updateTable'])->middleware('auth')->name('admintables.update');
    Route::get('/admintables/{tableName}/edit/add', [AdminTablesController::class, 'addTable'])->middleware('auth')->name('admintables.add');
    Route::post('/admintables/{tableName}/edit/add', [AdminTablesController::class, 'addTable'])->middleware('auth')->name('admintables.add');
    Route::delete('/admintables/{tableName}/delete/{id}', [AdminTablesController::class, 'destroy'])->middleware('auth')->name('admintables.delete');

    Route::post('/profile/edit-name', [ProfileController::class, 'editName'])->middleware('auth')->name('profile.editName');
    Route::post('/profile/edit-email', [ ProfileController::class, 'editEmail'])->middleware('auth')->name('profile.editEmail');
    //Route::get('/get-updated-users', [ProfileController::class, 'getUpdatedUsers'])->name('admin.get.updated.users');

    Route::get('/user/add', [ProfileController::class, 'add'])->name('add');
    Route::post('/user/add', [ ProfileController::class, 'saveUser'])->name('saveUser');
    Route::get('/sales', [ MainContentController::class, 'showSales'])->middleware('auth')->name('sales');   
    Route::post('/sales/store', [MainContentController::class, 'storeSales'])->name('sales.store');        
    Route::get('/seller/stats', [MainContentController::class, 'showHome'])->name('seller.stats');
    
    Route::middleware('auth')->group(function () {
        Route::get('/user/edit/{id}', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/user/update/{id}', [ProfileController::class, 'update'])->name('update');
        Route::delete('/user/delete/{id}', [ProfileController::class, 'destroy'])->name('delete');
    });
    

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