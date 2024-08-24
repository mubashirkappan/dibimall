<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Customer\CustomerLoginController;
use App\Http\Controllers\Customer\CustomerRegisterController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\ReferController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\TasOrderController;
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
        Route::get('order-list', [TasOrderController::class, 'orderList']);
        Route::post('create-shop', [ShopController::class, 'create']);
        Route::controller(ShopController::class)->prefix('shop/')->group(function () {
            Route::get('/list', 'ownerShopList');
            //done      // new api for get single one with slug it will be like imagelink:slug
            //done      //free delivery minimum amount
            //done    //offer carouosla for shops limt the amount a column top image status collumn
            //more place adding allow to shop owner and make diffrent table
            //done    //item restrinct for item coutn in shops table
            //done    //item agianst price under a messahe field
            //done    //to stroe who is clicked to know trhe number of the owner of shop store for the user getting number from dibi
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
        Route::controller(OfferController::class)->prefix('offer/')->group(function () {
            Route::get('main-list', 'MainIndex');
            Route::get('/random-list', 'RandomIndex');
            Route::post('/create', 'addImage');
            Route::get('delete/{id}', 'delete');
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
    Route::post('track-phone-click', [UserController::class, 'trackPhoneClick']);
    Route::get('update-to-owner', [UserController::class, 'updateToOwner']);
    Route::get('refer', [ReferController::class, 'refer']);
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
Route::controller(OfferController::class)->prefix('offer/')->group(function () {
    Route::get('main-list', 'MainIndex');
    Route::get('/random-list', 'RandomIndex');
});
Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/items', [ItemController::class, 'index']);
Route::get('/shops', [ShopController::class, 'index']);
Route::get('/shop-image-list', [ShopController::class, 'shopImageAndSlug']);
Route::post('offer/inside-shop-list', [OfferController::class, 'InsideShopIndex']);
Route::post('order', [TasOrderController::class, 'orderFromTas']);

Route::post('customer-register', [CustomerRegisterController::class, 'register']);
Route::post('customer-login', [CustomerLoginController::class, 'login']);
Route::get('places-list', [PlaceController::class, 'list']);
