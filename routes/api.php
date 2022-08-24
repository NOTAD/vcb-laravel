<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/passs', function () {
    return Hash::make("122333");
});

Route::get('/bank-list-247', [\App\Http\Controllers\Api\TransferApi::class, 'getBankList247'])->name('get_bank_list_247');

Route::get('/transaction-histories', [\App\Http\Controllers\Api\TransferApi::class, 'getTransactionHistories'])->name('get_transaction_histories');

Route::get('/get-bank-account',  [\App\Http\Controllers\Api\TransferApi::class, 'getAccountName'])->name('get_account');

Route::get('/get-tech-account',  [\App\Http\Controllers\Api\TransferApi::class, 'getTechAccountName'])->name('get_tech_account');

Route::post('/request-transfer',  [\App\Http\Controllers\Api\TransferApi::class, 'createTransfer'])->name('request_transfer');

Route::post('/callback-transfer',  [\App\Http\Controllers\Api\HistoryAPI::class, 'callbackTransfer'])->name('callback_transfer');

Route::get('/get-transfer',  [\App\Http\Controllers\Api\HistoryAPI::class, 'getTransfer'])->name('get_transfer');

Route::get('/get-list-transfer',  [\App\Http\Controllers\Api\HistoryAPI::class, 'getListTransfer'])->name('get_list_transfer');

Route::post('/handle-transfer',  [\App\Http\Controllers\Api\HistoryAPI::class, 'handleTransfer'])->name('handle_transfer');


Route::post('/confirm-otp-transfer',  [\App\Http\Controllers\Api\TransferApi::class, 'confirmOtpTransfer'])->name('confirm_otp_transfer');


Route::get('/check-blance',  [\App\Http\Controllers\Api\TransferApi::class, 'checkBlance'])->name('check_blance');

Route::post('/reset-transfer',  [\App\Http\Controllers\Api\HistoryAPI::class, 'reset'])->name('reset_transfer');
