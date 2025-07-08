<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::any('/', [HomeController::class, 'index']);
