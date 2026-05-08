<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MyWatchController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/my-watches', [MyWatchController::class, 'index'])->name('my-watches.index');
