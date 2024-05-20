<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FileUploadController;

Route::get('/users-by-country', [Controller::class, 'getUsersByCountry']);

Route::post('/upload-pdf', [FileUploadController::class, 'uploadPdf']);