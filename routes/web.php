<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;

Route::get('/users-by-country', [Controller::class, 'getUsersByCountry']);
