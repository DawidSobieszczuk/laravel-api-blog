<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\MenuItemController;
use App\Http\Controllers\Api\OptionController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\SocialController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

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
    Route::post('/login', [AuthController::class, 'login']);
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('/logout', [AuthController::class, 'logout']);

        Route::get('/user', [UserController::class, 'showCurrentUser']);
        Route::get('/user/permissions', [UserController::class, 'showCurrentUserPermissions']);
        Route::get('/user/can/{slug}', [UserController::class, 'showCurrentUserHasPermission']);
        Route::put('/user', [UserController::class, 'updateCurrentUser']);
    });

    Route::get('/articles', [ArticleController::class, 'index']);
    Route::get('/articles/paginate', [ArticleController::class, 'paginate']);
    Route::get('/articles/{id}', [ArticleController::class, 'show']);
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('/articles', [ArticleController::class, 'store'])->middleware('permission:create articles');
        Route::put('/articles/{id}', [ArticleController::class, 'update'])->middleware('permission:edit articles');
        Route::delete('/articles/{id}', [ArticleController::class, 'destroy'])->middleware('permission:delete articles');

        Route::put('/articles/publish/{id}', [ArticleController::class, 'publish'])->middleware('permission:publish articles');
        Route::put('/articles/unpublish/{id}', [ArticleController::class, 'unpublish'])->middleware('permission:unpublish articles');
    });

    Route::get('/articles/category/{slug}', [SearchController::class, 'category']);
    Route::get('/articles/category/{slug}/paginate', [SearchController::class, 'categoryPaginate']);
    Route::get('/articles/tag/{slug}', [SearchController::class, 'tag']);
    Route::get('/articles/tag/{slug}/paginate', [SearchController::class, 'tagPaginate']);
    Route::get('/articles/search/{slug}', [SearchController::class, 'search']);
    Route::get('/articles/search/{slug}/paginate', [SearchController::class, 'searchPaginate']);

    Route::get('/options', [OptionController::class, 'index']);
    Route::get('/options/{id}', [OptionController::class, 'show']);
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('/options', [OptionController::class, 'store'])->middleware('permission:create options');
        Route::put('/options/{id}', [OptionController::class, 'update'])->middleware('permission:edit options');
        Route::delete('/options/{id}', [OptionController::class, 'destroy'])->middleware('permission:delete options');
    });

    Route::get('/socials', [SocialController::class, 'index']);
    Route::get('/socials/{id}', [SocialController::class, 'show']);
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('/socials', [SocialController::class, 'store'])->middleware('permission:create socials');
        Route::put('/socials/{id}', [SocialController::class, 'update'])->middleware('permission:edit socials');
        Route::delete('/socials/{id}', [SocialController::class, 'destroy'])->middleware('permission:delete socials');
    });

    Route::get('/menus', [MenuController::class, 'index']);
    Route::get('/menus/{id}', [MenuController::class, 'show'])->where('id', '[0-9]+');
    Route::get('/menus/{slug}', [MenuController::class, 'showByName']);
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('/menus', [MenuController::class, 'store'])->middleware('permission:create menus');
        Route::put('/menus/{id}', [MenuController::class, 'update'])->middleware('permission:update menus');
        Route::delete('/menus/{id}', [MenuController::class, 'destroy'])->middleware('permission:delete menus');
    });

    Route::get('/menuitems', [MenuItemController::class, 'index']);
    Route::get('/menuitems/{id}', [MenuItemController::class, 'show']);
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('/menuitems', [MenuItemController::class, 'store'])->middleware('permission:create menus');
        Route::put('/menuitems/{id}', [MenuItemController::class, 'update'])->middleware('permission:update menus');
        Route::delete('/menuitems/{id}', [MenuItemController::class, 'destroy'])->middleware('permission:delete menus');
    });
});
