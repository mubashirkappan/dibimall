<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Customer\CustomerLoginController;
use App\Http\Controllers\Customer\CustomerRegisterController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\ReferController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::group(['middleware' => 'is.owner'], function () {
        Route::controller(ShopController::class)->prefix('shop/')->group(function () {
            Route::get('/list', 'ownerShopList');
            Route::get('/show/{user_name}', 'showShopDetails');
            Route::get('/delete/{encrypted_id}', 'delete');
            Route::post('/update', 'update');
        });
        Route::controller(CategoryController::class)->prefix('categories/')->group(function () {
            Route::post('/list', 'index');
            Route::post('/create', 'create');
            Route::get('delete/{encrypted_id}', 'delete');
            Route::post('/update', 'update');
        });
        Route::controller(ItemsController::class)->prefix('items/')->group(function () {
            Route::post('/list', 'index');
            Route::post('/create', 'create');
            Route::get('delete/{encrypted_id}', 'delete');
            Route::post('/update', 'update');
        });
        Route::controller(CartController::class)->group(function () {
            Route::post('complete-order', 'completeOrder');
            Route::post('accept-order', 'acceptOrder');
            Route::get('get-orders', 'ordersForShop');
        });

    });

    Route::get('get-user', [UserController::class, 'getUser']);
    Route::get('update-to-owner', [UserController::class, 'updateToOwner']);
    Route::get('refer', [ReferController::class, 'refer']);
    Route::post('create-shop', [ShopController::class, 'create']);
    Route::post('check-shop-user-name', [ShopController::class, 'checkUserName']);
    Route::post('add-to-cart', [CartController::class, 'addToCart']);
    Route::get('get-cart', [CartController::class, 'getCart']);
    Route::post('delete-from-cart', [CartController::class, 'removeFromCart']);
    Route::post('confirm-order', [CartController::class, 'confirmOrder']);
    Route::get('confirm-orders-list', [CartController::class, 'listCompleteOrders']);

});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/items', [ItemController::class, 'index']);
Route::get('/shops', [ShopController::class, 'index']);
Route::get('/shop-image-list', [ShopController::class, 'shopImageAndSlug']);

Route::post('customer-register', [CustomerRegisterController::class, 'register']);
Route::post('customer-login', [CustomerLoginController::class, 'login']);
Route::get('places-list', [PlaceController::class, 'list']);
