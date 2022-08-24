<?php

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

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\FileController;
use App\Http\Controllers\Admin\HistoryTransferController;
use App\Http\Controllers\Admin\IndexController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;


// Auth
Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::get('/user/login', [App\Http\Controllers\Auth\UserController::class, 'showLoginForm'])->name('show_user_login_form');
    Route::post('/user/login', [App\Http\Controllers\Auth\UserController::class, 'login'])->name('user_login');
    Route::get('/user/logout', [App\Http\Controllers\Auth\UserController::class, 'logout'])->name('user_logout');
    Route::get('/user/sns/{snsProvider}', [App\Http\Controllers\Auth\UserController::class, 'snsLogin'])->name('sns_login');
    Route::get('/user/{snsProvider}/callback', [App\Http\Controllers\Auth\UserController::class, 'snsLoginCallback']);
    Route::get('/user/register', [App\Http\Controllers\Auth\UserController::class, 'showRegisterForm'])->name('show_user_register_form');
    Route::post('/user/register', [App\Http\Controllers\Auth\UserController::class, 'registerUser'])->name('register_user');
    Route::get('/user/verify-email/{token}', [App\Http\Controllers\Auth\UserController::class, 'verifyEmail'])->name('verify_user_email');
    Route::get('/user/forget-password', [App\Http\Controllers\Auth\UserController::class, 'showForgetPasswordForm'])->name('show_user_forget_password_form');
    Route::post('/user/forget-password', [App\Http\Controllers\Auth\UserController::class, 'createResetPasswordToken'])->name('create_user_reset_password_token');
    Route::get('/user/reset-password/{token}', [App\Http\Controllers\Auth\UserController::class, 'showResetPasswordForm'])->name('show_user_reset_password_form');
    Route::post('/user/reset-password/{token}', [App\Http\Controllers\Auth\UserController::class, 'resetPassword'])->name('reset_user_password');
    Route::get('/user/password', [App\Http\Controllers\Auth\UserController::class, 'password'])->name('user_password');
    Route::patch('/user/change-password', [App\Http\Controllers\Auth\UserController::class, 'changePassword'])->name('change_user_password');

    Route::get('/admin/login', [App\Http\Controllers\Auth\AdminController::class, 'showLoginForm'])->name('show_admin_login_form');
    Route::post('/admin/login', [App\Http\Controllers\Auth\AdminController::class, 'login'])->name('admin_login');
    Route::get('/admin/logout', [App\Http\Controllers\Auth\AdminController::class, 'logout'])->name('admin_logout');
    Route::patch('/admin/change-password', [App\Http\Controllers\Auth\AdminController::class, 'changePassword'])->name('change_admin_password');
    Route::get('/admin/forget-password', [App\Http\Controllers\Auth\AdminController::class, 'showForgetPasswordForm'])->name('show_admin_forget_password_form');
    Route::post('/admin/forget-password', [App\Http\Controllers\Auth\AdminController::class, 'createResetPasswordToken'])->name('create_admin_reset_password_token');
    Route::get('/admin/reset-password/{token}', [App\Http\Controllers\Auth\AdminController::class, 'showResetPasswordForm'])->name('show_admin_reset_password_form');
    Route::post('/admin/reset-password/{token}', [App\Http\Controllers\Auth\AdminController::class, 'resetPassword'])->name('reset_admin_password');
});

Route::get('', [IndexController::class, 'index'])->name('index');


// Admin area
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth:admin']], function () {
    Route::get('/', [IndexController::class, 'index'])->name('index');

    // Files
    Route::get('/files', [FileController::class, 'index'])->name('files_index');
    Route::post('/files/browser', [FileController::class, 'browser'])->name('ajax_browser_files');
    Route::post('/files', [FileController::class, 'upload'])->name('ajax_upload_file')->middleware('file_permission');
    Route::post('/files/folder', [FileController::class, 'makeDirectory'])->name('ajax_make_directory');
    Route::patch('/files/resize', [FileController::class, 'resize'])->name('ajax_resize_files')->middleware('file_permission');
    Route::patch('/files/delete', [FileController::class, 'delete'])->name('ajax_delete_files');

    //User
    Route::get('/users', [UserController::class, 'index'])->name('users_index');
    Route::get('/users/export', [UserController::class, 'export'])->name('users_export');
    Route::post('/users', [UserController::class, 'store'])->name('ajax_create_user');
    Route::patch('/users/{user}', [UserController::class, 'update'])->name('ajax_update_user');
    Route::delete('/users/{user}', [UserController::class, 'delete'])->name('ajax_delete_user');
    Route::post('/users/search', [UserController::class, 'search'])->name('ajax_search_user');
    //User
    Route::get('/banks', [BankController::class, 'index'])->name('banks_index');
    Route::post('/banks', [BankController::class, 'store'])->name('ajax_create_bank');
    Route::patch('/banks/{bank}', [BankController::class, 'update'])->name('ajax_update_bank');
    Route::delete('/banks/{bank}', [BankController::class, 'delete'])->name('ajax_delete_bank');
    Route::post('/banks/search', [BankController::class, 'search'])->name('ajax_search_bank');

    Route::get('/transfers', [HistoryTransferController::class, 'index'])->name('history_transfers_index');
    Route::post('/transfers', [HistoryTransferController::class, 'store'])->name('ajax_create_history_transfer');
    Route::patch('/transfers/{history}', [HistoryTransferController::class, 'update'])->name('ajax_update_history_transfer');
    Route::delete('/transfers/{history}', [HistoryTransferController::class, 'delete'])->name('ajax_delete_history_transfer');
    Route::post('/transfers/search', [HistoryTransferController::class, 'search'])->name('ajax_search_history_transfer');
    Route::post('/transfers/callback/{history}', [HistoryTransferController::class, 'callback'])->name('ajax_history_callback');
    Route::post('/transfers/reset/{history}', [HistoryTransferController::class, 'reset'])->name('ajax_reset_history_transfer');



    Route::get('/profile', [AdminController::class, 'profile'])->name('admin_profile');
});

// System admin area
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth:admin', 'authorize.system_admin']], function () {

    //System
    Route::get('/setting', [SettingController::class, 'index'])->name('setting_index');
    Route::patch('setting/{setting}', [SettingController::class, 'update'])->name('ajax_update_setting');

    // Role
    Route::get('/roles', [RoleController::class, 'index'])->name('roles_index');
    Route::post('roles', [RoleController::class, 'store'])->name('ajax_create_role');
    Route::patch('roles/{role}', [RoleController::class, 'update'])->name('ajax_update_role');
    Route::delete('roles/{role}', [RoleController::class, 'delete'])->name('ajax_delete_role');

    // Admin
    Route::get('/admins', [AdminController::class, 'index'])->name('admins_index');
    Route::post('admins', [AdminController::class, 'store'])->name('ajax_create_admin');
    Route::patch('admins/{admin}', [AdminController::class, 'update'])->name('ajax_update_admin');
    Route::delete('admins/{admin}', [AdminController::class, 'delete'])->name('ajax_delete_admin');


});

