<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';


Route::group(['prefix' => 'wallets', 'middleware' => 'auth', 'controller' => WalletController::class], function() {
    Route::get('/', 'index')->name('wallets.index');
    Route::get('/create', 'create')->name('wallets.create');
    Route::post('/store', 'store')->name('wallets.store');
    Route::get('/show/{id}', 'show')->name('wallets.show');
    Route::get('/transactions/{wallet_id}', 'listTransactions')->name('transactions.index');
    Route::get('/get-new-address/{wallet_id}', 'getNewAddress')->name('wallets.get-new-address');
});

Route::group(['prefix' => 'transactions', 'middleware' => 'auth', 'controller' => TransactionController::class], function() {
    Route::post('/batch/add', 'addBatch')->name('transactions.add-batch');
});