<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\Access\ForgoutController;
use App\Http\Controllers\Access\LoginController;
use App\Http\Controllers\Access\RegisterController;
use App\Http\Controllers\File\FileController;
use App\Http\Controllers\Finance\InvoiceController;
use App\Http\Controllers\Finance\WalletController;
use App\Http\Controllers\Folder\FolderController;
use App\Http\Controllers\Plan\PlanController;
use App\Http\Controllers\Trash\TrashController;
use App\Http\Controllers\User\UserController;

use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class, 'login']);
Route::get('/login', [LoginController::class, 'login'])->name('login');

Route::get('register/{plan_id?}', [RegisterController::class, 'register'])->name('register');
Route::get('forgout-password/{code?}', [ForgoutController::class, 'forgout'])->name('forgout-password');

Route::post('logon', [LoginController::class, 'logon'])->name('logon');
Route::post('create-register', [RegisterController::class, 'createUser'])->name('create-register');
Route::post('send-recovery', [ForgoutController::class, 'sendRecovery'])->name('send-recovery');
Route::post('recovery-password', [ForgoutController::class, 'recoveryPassword'])->name('recovery-password');

Route::middleware(['auth'])->group(function () {

    Route::get('/invoices', [InvoiceController::class, 'invoices'])->name('invoices');
    Route::get('confirm-invoice/{id}', [InvoiceController::class, 'confirmInvoice'])->name('confirm-invoice');
    Route::get('/plans', [PlanController::class, 'plansPay'])->name('plans');
    Route::post('delete-invoice', [InvoiceController::class, 'deleteInvoice'])->name('delete-invoice');

    Route::get('/wallet', [WalletController::class, 'wallet'])->name('wallet');
    Route::post('withdraw-send', [WalletController::class, 'withdrawSend'])->name('withdraw-send');

    Route::middleware(['month'])->group(function () {

        Route::get('/app', [AppController::class, 'app'])->name('app');
        Route::get('/shared', [AppController::class, 'shared'])->name('shared');
        Route::get('/protecte', [AppController::class, 'protecte'])->name('protecte');
        Route::get('/folder/{id}', [FolderController::class, 'viewFolder'])->name('folder');
        Route::get('/edit-folder/{id}', [FolderController::class, 'editFolder'])->name('edit-folder');
        Route::post('create-folder', [FolderController::class, 'createFolder'])->name('create-folder');
        Route::post('update-folder', [FolderController::class, 'updateFolder'])->name('update-folder');
        Route::post('delete-folder', [FolderController::class, 'deleteFolder'])->name('delete-folder');

        Route::get('/file/{id}/{donwload?}', [FileController::class, 'file'])->name('file');
        Route::get('/edit-file/{id}', [FileController::class, 'editFile'])->name('edit-file');
        Route::post('update-file', [FileController::class, 'updateFile'])->name('update-file');
        Route::post('delete-file', [FileController::class, 'deleteFile'])->name('delete-file');

        Route::get('/trash', [TrashController::class, 'trash'])->name('trash');
        Route::get('/restore-folder/{id}', [TrashController::class, 'restoreFolder'])->name('restore-folder');
        Route::get('/restore-file/{id}', [TrashController::class, 'restoreFile'])->name('restore-file');
        Route::get('/restore-user/{id}', [TrashController::class, 'restoreUser'])->name('restore-user');
        Route::post('trash-clear', [TrashController::class, 'trashClear'])->name('trash-clear');

        Route::get('/list-plans', [PlanController::class, 'plans'])->name('list-plans');
        Route::post('pay-plan', [PlanController::class, 'payPlan'])->name('pay-plan');
        Route::post('create-plan', [PlanController::class, 'createPlan'])->name('create-plan');
        Route::post('update-plan', [PlanController::class, 'updatePlan'])->name('update-plan');
        Route::post('delete-plan', [PlanController::class, 'deletePlan'])->name('delete-plan');

        Route::get('/profile', [UserController::class, 'profile'])->name('profile');
        Route::get('/list-users/{role?}', [UserController::class, 'listUsers'])->name('list-users');
        Route::post('create-user', [UserController::class, 'createUser'])->name('create-user');
        Route::post('update-user', [UserController::class, 'updateUser'])->name('update-user');
        Route::post('delete-user', [UserController::class, 'deleteUser'])->name('delete-user');
    });

    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});