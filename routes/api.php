<?php

use App\Models\VirtualAccount;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\TerminalopController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Agents\AuthController;
use App\Http\Controllers\Admin\TerminalController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StoredataController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Agents\PosTransactionController;
use App\Http\Controllers\Agents\VirtualAccountController;

//other database
Route::post('store-user', [StoredataController::class, 'store_user']);
Route::post('store-terminal', [StoredataController::class, 'store_terminal']);
Route::post('update-terminal', [StoredataController::class, 'update_terminal']);
Route::post('update-user', [StoredataController::class, 'update_user']);
Route::post('store-transaction', [StoredataController::class, 'store_transaction']);
Route::post('create-bank', [StoredataController::class, 'create_bank']);
Route::post('update-bank', [StoredataController::class, 'update_bank']);
Route::post('delete_bank', [StoredataController::class, 'delete_bank']);
Route::post('delete_user', [StoredataController::class, 'delete_user']);



Route::any('virtual-notification', [VirtualAccountController::class, 'virtual_notification']);
Route::any('pos-log', [PosTransactionController::class, 'PosLogs']);
Route::any('pos', [PosTransactionController::class, 'pos']);
Route::any('eod', [PosTransactionController::class, 'eod_transactions']);






Route::group(['prefix' => 'agent'], function () {
    Route::post('phone-login', [AuthController::class, 'phone_login']);
    Route::post('email-login', [AuthController::class, 'email_login']);


    
    Route::group(['middleware' => ['auth:api', 'acess']], function () {
       
          //VIRTAL ACCOUNT
          Route::post('create-virtual-account', [VirtualAccountController::class, 'create_virtual_account']);

          

    

       
       
       
        //Banks
        Route::post('create-bank', [BankController::class, 'create_bank']);
        Route::post('update-bank', [BankController::class, 'update_bank']);
        Route::post('delete-bank', [BankController::class, 'delete_bank']);
        Route::post('search-bank', [BankController::class, 'search_bank']);



        //Dashboard
        Route::get('dashboard', [DashboardController::class, 'dashboard_data']);


        //User
        Route::post('create-user', [UserController::class, 'create_user']);
        Route::post('update-user', [UserController::class, 'update_user']);
        Route::get('list-users', [UserController::class, 'get_users']);
        Route::get('list-customer-users', [UserController::class, 'get_customer_users']);
        Route::get('list-bank-users', [UserController::class, 'get_bank_users']);
        Route::get('delete-users', [UserController::class, 'delete_user']);
        Route::post('search-users', [UserController::class, 'search_user']);




        //Transaction
        Route::get('get-transactions/{limit}', [TransactionController::class, 'get_all_transactions']);
        Route::get('get-transactions-filter/{limit}', [TransactionController::class, 'get_transactions_by_filter']);
        Route::get('export-transactions', [TransactionController::class, 'export_transactions']);





        Route::post('transaction-filter', [TransactionController::class, 'get_all_transaction_by_filter']);


        //Terminal
        Route::post('create-terminal', [TerminalController::class, 'create_terminal']);
        Route::post('update-terminal', [TerminalController::class, 'update_terminal']);
        Route::get('view-terminal', [TerminalController::class, 'view_all_terminal']);
        Route::get('delete-terminal', [TerminalController::class, 'delete_terminal']);
        Route::post('search-terminal', [TerminalController::class, 'search_terminal']);





    });


});



