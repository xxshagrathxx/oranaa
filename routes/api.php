<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\WishlistController;
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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::prefix('v1')->group(function() {
    Route::group(['middleware' => 'auth:sanctum'], function() {

        // Wishlist
        Route::prefix('/wishlist')->group(function() {
            Route::get('/', [WishlistController::class, 'index']);
            Route::post('/add', [WishlistController::class, 'addToWishlist']);
            Route::post('/update/{id}', [WishlistController::class, 'updateQuantity']);
            Route::get('/remove/{id}', [WishlistController::class, 'removeFromWishlist']);
        });
        // ./Wishlist
    });
});

Route::prefix('/statistics')->group(function() {
    Route::get('/products-count', [StatisticsController::class, 'productsCount']);
    Route::get('/products-count-website', [StatisticsController::class, 'productsCountWebsite']);
    Route::get('/products-average', [StatisticsController::class, 'productsAveragePrice']);
    Route::get('/products-highest', [StatisticsController::class, 'productsHighestPrice']);
    Route::get('/products-month', [StatisticsController::class, 'productsTotalMonth']);
});

Route::get('/products', [ProductController::class, 'index']);
Route::post('/products', [ProductController::class, 'store']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::put('/products/{id}', [ProductController::class, 'update']);
