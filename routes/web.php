<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TerminalController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Agents\TransferController;
use App\Http\Controllers\LoginSecurityController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Route::get('/', function () {return view('login');});

Route::get('login', [AuthController::class, 'login_form'])->name('login');


Route::get('reset-pin', [App\Http\Controllers\Agents\AuthController::class, 'reset_pin']);
Route::get('reset-password', [App\Http\Controllers\Agents\AuthController::class, 'reset_password']);


Route::post('reset-pin-now', [App\Http\Controllers\Agents\AuthController::class, 'reset_pin_now']);
Route::post('reset-password-now', [App\Http\Controllers\Agents\AuthController::class, 'reset_password_now']);


Route::get('success', [App\Http\Controllers\Agents\AuthController::class, 'success']);




Route::any('set-2fa', [AuthController::class, 'set_2fa']);
Route::any('auth_login', [AuthController::class, 'login']);
Route::any('resend_code', [AuthController::class, 'resend_code']);
Route::any('verify_code', [AuthController::class, 'verify_code']);
Route::get('code', [AuthController::class, 'code']);





Route::group(['prefix'=>'admin'], function(){

    Route::get('admin-dashboard', [DashboardController::class, 'admin_dashboard']);
    Route::get('new-users', [DashboardController::class, 'new_user']);
    Route::post('create_new_customer', [DashboardController::class, 'create_new_customer']);
    Route::get('users', [DashboardController::class, 'all_customer']);
    Route::any('search_user', [DashboardController::class, 'search_user']);
    Route::any('reverse', [TransferController::class, 'reverse']);


    Route::any('all-transactions', [TransactionController::class, 'get_all_transactions']);





    //Terminal
    Route::get('/new-terminal', [TerminalController::class, 'create_terminal_view']);
    Route::any('create_new_terminal', [TerminalController::class, 'create_new_terminal']);
    Route::any('list-terminals', [TerminalController::class, 'list_terminals']);


    Route::any('settings', [AdminController::class, 'setting']);
    Route::any('update_setting', [AdminController::class, 'update_setting']);











    Route::post('/generateSecret', [LoginSecurityController::class, 'generate2faSecret'])->name('generate2faSecret');
    Route::post('/enable2fa', [LoginSecurityController::class, 'enable2fa'])->name('enable2fa');
    Route::post('/disable2fa', [LoginSecurityController::class, 'disable2fa'])->name('disable2fa');

});


// test middleware
Route::get('/test_middleware', function () {
    return "2FA middleware work!";
})->middleware(['auth', '2fa']);




