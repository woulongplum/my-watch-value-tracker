<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MyWatchController;

Route::get('/', function () {
    return view('welcome');
});


Route::resource('my-watches', MyWatchController::class);
