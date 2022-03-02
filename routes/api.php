<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\CssSelector\Node\FunctionNode;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1'], function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::get('/articles', [ArticleController::class, 'index'])->name('articles-index');
    Route::get('/articles/{id}', [ArticleController::class, 'show'])->name('articles-show');

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/user', [UserController::class, 'show'])->name('user-show');
        Route::put('/user', [UserController::class, 'update'])->name('user-update');

        Route::group(['middlewate' => 'is_admin', 'prefix' => 'admin'], function () {
            Route::post('/articles', [ArticleController::class, 'store'])->name('articles-store');
            Route::put('/articles/{id}', [ArticleController::class, 'update'])->name('articles-update');
            Route::delete('/articles/{id}', [ArticleController::class, 'destroy'])->name('articles-destroy');
        });
    });
});
