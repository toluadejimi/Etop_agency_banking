<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StoredataController;
use App\Http\Controllers\Admin\TerminalController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\PosTrasnactionController;
use App\Http\Controllers\TerminalopController;
use Illuminate\Support\Facades\Route;


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



Route::group(['prefix' => 'admin'], function () {
    Route::post('login', [AdminController::class, 'admin_login']);

    Route::group(['middleware' => ['auth:api', 'acess']], function () {


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


Route::group(['prefix' => 'v1'], function () {

    Route::get('get-details', [TerminalopController::class, 'get_terminal_details']);
    Route::post('initiate-transaction', [PosTrasnactionController::class, 'EtopPosLogs']);
    Route::any('get-logged-data', [PosTrasnactionController::class, 'get_logged_data']);
    Route::post('reset-pin', [TerminalopController::class, 'reset_pin']);
    Route::post('verify-pin', [TerminalopController::class, 'verify_pin']);
    Route::any('get-all-logged-data', [PosTrasnactionController::class, 'get_all_by_serial_logged_data']);
    Route::any('get-all-transactions', [PosTrasnactionController::class, 'get_all_transaction']);


    Route::any('get-all-logged-data/get-all-transactions', [PosTrasnactionController::class, 'get_all_transaction_by_filter']);
    Route::any('complete-transaction', [PosTrasnactionController::class, 'EtopPos']);


});

