<?php

use App\Http\Controllers\File\FileController;
use App\Http\Controllers\Folder\FolderController;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;

//Helpers
Route::post('validate-password', [FolderController::class, 'validatePassword'])->name('validate-password');


Route::post('delete-folder', [FolderController::class, 'deleteFolder'])->name('delete-folder');

Route::post('upload-file', [FileController::class, 'uploadFile'])->name('upload-file');
Route::post('upload-file-to-google-drive', [FileController::class, 'uploadToGoogleDrive'])->name('upload-file-to-google-drive');
Route::post('delete-file', [FileController::class, 'deleteFile'])->name('delete-file');
