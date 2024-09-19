<?php

use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StoredataController;
use App\Http\Controllers\Admin\TerminalController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Agents\AuthController;
use App\Http\Controllers\Agents\BillsController;
use App\Http\Controllers\Agents\PosTransactionController;
use App\Http\Controllers\Agents\TransferController;
use App\Http\Controllers\Agents\VirtualAccountController;
use Illuminate\Support\Facades\Route;




Route::post('create-account-dymamic', [VirtualAccountController::class, 'create_account_dymamic']);
Route::any('session-check', [TransactionController::class, 'session_check']);
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
Route::any('pos-logs', [PosTransactionController::class, 'PosLogs']);
Route::any('pos', [PosTransactionController::class, 'pos']);
Route::any('eod', [PosTransactionController::class, 'eod_transactions']);


Route::group(['prefix' => 'agent'], function () {
    Route::post('phone-login', [AuthController::class, 'phone_login']);
    Route::post('email-login', [AuthController::class, 'email_login']);
    Route::post('pin-login', [AuthController::class, 'pin_login']);

    Route::group(['middleware' => ['auth:api', 'acess']], function () {
        Route::post('verify-pin', [AuthController::class, 'verify_pin']);


        //VIRTAL ACCOUNT
        Route::post('create-account', [VirtualAccountController::class, 'create_virtual_account']);

        //Bills Payment
        Route::get('get-categories', [BillsController::class, 'get_categories']);
        Route::post('get-biller', [BillsController::class, 'get_biller']);
        Route::post('get-biller-type', [BillsController::class, 'get_biller_type']);
        Route::post('validate', [BillsController::class, 'validate_biller']);
        Route::post('pay-bill', [BillsController::class, 'pay_bill']);
        Route::post('buy-airtime', [BillsController::class, 'buy_airtime']);
        Route::post('buy-data', [BillsController::class, 'buy_data']);
        Route::post('get-data-plans', [BillsController::class, 'get_data_plans']);

        Route::get('contact', [AuthController::class, 'contact']);








        //FUnds Transfer
        Route::get('transfer-properties', [TransferController::class, 'transfer_properties']);
        Route::post('resolve-account', [TransferController::class, 'validate_account']);
        Route::post('bank-transfer', [TransferController::class, 'transfer']);



        //Transaction
        Route::get('all-transaction', [App\Http\Controllers\Agents\TransactionController::class, 'all_transaction']);
        Route::post('transaction-status', [App\Http\Controllers\Agents\TransactionController::class, 'status_transaction']);
        Route::post('transaction-history', [App\Http\Controllers\Agents\TransactionController::class, 'transaction_his']);











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
        Route::get('user-info', [UserController::class, 'user_info']);





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



        //Profile
        Route::get('user-info', [AuthController::class, 'user_info']);
        Route::post('delete-user', [AuthController::class, 'delete_user']);
        Route::post('forgot-pin', [AuthController::class, 'forgot_pin']);
        Route::post('forgot-password', [AuthController::class, 'forgot_password']);
        Route::any('get-beneficiary', [AuthController::class, 'get_beneficary']);
        Route::any('update-beneficiary', [AuthController::class, 'update_beneficary']);
        Route::any('delete-beneficiary', [AuthController::class, 'delete_beneficary']);
        Route::post('update-kyc', [AuthController::class, 'update_user']);
        Route::post('verify-info', [AuthController::class, 'verify_info']);
        Route::post('update-business', [AuthController::class, 'update_business']);
        Route::post('update-account-info', [AuthController::class, 'update_account_info']);
        Route::post('update-bank-info', [AuthController::class, 'update_bank_info']);
        Route::post('verify-identity', [AuthController::class, 'verify_identity']);
        Route::post('upload-identity', [AuthController::class, 'upload_identity']);


       //Transaction
       // Route::any('transaction-history', [TransactionController::class, 'transaction_history']);


    });


});



