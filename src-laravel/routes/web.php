<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MyWatchController;
use App\Http\Controllers\MarketTrendController; // 💡 もし個別のコントローラーがあればインポート
use Illuminate\Support\Facades\Route;

// 🔹 誰でもアクセスできるルート（未ログインでもOK）
Route::get('/', [MyWatchController::class, 'index'])->name('my-watches.index');

// 💡 エラーの原因だったルートをここで定義（とりあえず詳細画面の定義を追加）
// もし専用の MarketTrendController があればそちらを指定し、なければ一旦 MyWatchController などで仮受けするか、クロージャで対応します。
// ここでは、ルーティングのエラーを解消するために名前付きルートを定義します。
Route::get('/market-trends/{id}', [MarketTrendController::class, 'show'])->name('market-trends.show');


// 🔹 ログイン中（auth）のみアクセスできるルート
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['verified'])->name('dashboard');

    // 所有時計の管理（CRUD）
    Route::post('/my-watches', [MyWatchController::class, 'store'])->name('my-watches.store');
    Route::get('/my-watches/{my_watch}/edit', [MyWatchController::class, 'edit'])->name('my-watches.edit');
    Route::put('/my-watches/{my_watch}', [MyWatchController::class, 'update'])->name('my-watches.update');
    Route::delete('/my-watches/{my_watch}', [MyWatchController::class, 'destroy'])->name('my-watches.destroy');
    Route::get('/my-watches/{my_watch}', [MyWatchController::class, 'show'])->name('my-watches.show');

    // プロフィール管理
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
