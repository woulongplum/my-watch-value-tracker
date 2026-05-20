<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MyWatchController;
use App\Http\Controllers\MarketTrendController;


Route::get('/', function () {
    return view('welcome');
});

//自分の時計コレクションのルート
Route::resource('my-watches', MyWatchController::class);

//楽天APIのデータ詳細ページのルート
Route::get('market-trends/{id}', [MarketTrendController::class, 'show'])->name('market-trends.show');
