<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BankController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\NotifyController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\TripayController;
use App\Http\Controllers\UpdateController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PromoteController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\FrontApiController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\SellerController;

Route::middleware(['auth:sanctum', 'auth.admin'])->group(function() {

    Route::get('userList', [UserController::class, 'userList']);
    Route::get('findUser/{key}', [UserController::class, 'findUser']);
    Route::delete('user/{id}', [UserController::class, 'destroy']);

    Route::delete('product/{id}', [ProductController::class, 'destroy']);
    Route::post('product/update', [ProductController::class, 'update']);
    Route::post('product', [ProductController::class, 'store']);
    Route::get('getAdminProducts', [ProductController::class, 'getAdminProducts']);

    Route::post('sliders', [SliderController::class, 'store']);
    Route::delete('sliders/{id}', [SliderController::class, 'destroy']);
    Route::post('update-slider-weight', [SliderController::class, 'updateSliderWeight']);

    Route::apiResource('categories', CategoryController::class)->only('store', 'update', 'destroy');

    Route::post('shop', [StoreController::class, 'update']);

    Route::apiResource('posts', PostController::class)->only('store', 'update', 'destroy');

    Route::apiResource('blocks', BlockController::class)->only('store', 'update', 'destroy');

    Route::apiResource('banks', BankController::class)->only('store', 'update', 'destroy');

    Route::delete('orders/{id}', [OrderController::class, 'destroy']);
    Route::get('orders', [OrderController::class, 'index']);
    Route::post('searchAdminOrder', [OrderController::class, 'searchAdminOrder']);
    Route::put('orders', [OrderController::class, 'update']);
    Route::post('updateStatusOrder', [OrderController::class, 'updateStatusOrder']);
    Route::post('paymentAccepted/{id}', [OrderController::class, 'paymentAccepted']);
    Route::post('inputResi', [OrderController::class, 'inputResi']);
    Route::post('cancelOrder/{id}', [OrderController::class, 'cancelOrder']);
    // Update
    Route::get('update', [UpdateController::class, 'overview']);
    Route::post('update', [UpdateController::class, 'update']);  
    Route::post('clearCache', [UpdateController::class, 'clearCache']);  
    
    Route::apiResource('discount', DiscountController::class);

    Route::apiResource('coupon', CouponController::class);

    Route::apiResource('promote', PromoteController::class);
    Route::get('getPromoDetail/{id}', [PromoteController::class, 'getPromoDetail']);

    Route::post('toggleProductPromo', [ProductController::class, 'toggleProductPromo']);
    Route::get('getProductPromo/{promoId}', [ProductController::class, 'getProductPromo']);
    Route::get('findNotDiscountProduct', [ProductController::class, 'findNotDiscountProduct']);
    Route::get('searchAdminProducts/{key}', [ProductController::class, 'searchAdminProducts']);
    // new
    Route::apiResource('promo', PromoController::class);
    Route::get('getPromoDetail/{id}', [PromoController::class, 'getPromoDetail']);
    Route::post('removeProductPromo', [PromoController::class, 'removeProductPromo']);

    Route::post('submitProductPromo', [ProductController::class, 'submitProductPromo']);
    Route::get('getProductPromo/{promoId}', [ProductController::class, 'getProductPromo']);
    Route::get('findProductWithoutPromo', [ProductController::class, 'findProductWithoutPromo']);

    Route::apiResource('sellers', SellerController::class);
    Route::get('findSeller/{key}', [SellerController::class, 'findSeller']);
    // Route::get('getSellerProduct/{id}', [SellerController::class, 'getSellerProduct']);
    
});

Route::middleware(['auth:sanctum', 'auth.seller'])->group(function() {
    Route::post('product', [ProductController::class, 'store']);
    Route::post('product/update', [ProductController::class, 'update']);
    Route::delete('product/{id}', [ProductController::class, 'destroy']);
    
    Route::delete('seller/searchProducts/{key}', [SellerController::class, 'searchProducts']);

    Route::post('updateSeller', [SellerController::class, 'updateSeller']);
    Route::get('getSeller', [SellerController::class, 'getSeller']);
    Route::get('seller/products/{seller?}', [SellerController::class, 'products']);
    Route::get('seller/orders/{seller?}', [SellerController::class, 'orders']);
});

Route::middleware('auth:sanctum')->group(function() {

    Route::get('adminConfig', [ConfigController::class, 'adminConfig']);
    Route::post('config', [ConfigController::class, 'update']);
    
    Route::get('user', [UserController::class, 'index']);
    Route::post('user/logout', [UserController::class, 'logout']);
    Route::post('user/update', [UserController::class, 'update']);
    
    Route::post('filterOrder', [OrderController::class, 'filterOrder']);
    Route::get('getCustomerOrders', [OrderController::class, 'getCustomerOrders']);
    Route::post('refreshToken', [UserController::class, 'refreshToken']);
    
});

Route::post('orders', [OrderController::class, 'store']);

Route::apiResource('posts', PostController::class)->only('index', 'show');
Route::get('post/{slug}', [PostController::class, 'getPostBySlug']);

Route::get('product/{slug}', [ProductController::class, 'show']);
Route::get('productById/{id}', [ProductController::class, 'productById']);

Route::post('addProductReview', [ProductController::class, 'addProductReview']);
Route::get('loadProductReview/{id}', [ProductController::class, 'loadProductReview']);
Route::get('products', [ProductController::class, 'index']);
Route::post('getProductsFavorites', [ProductController::class, 'getProductsFavorites']);
Route::get('getProductsByCategory/{id}', [ProductController::class, 'getProductsByCategory']);
Route::get('search/{key}', [ProductController::class, 'search']);

Route::get('sliders', [SliderController::class, 'index']);
Route::get('shop', [StoreController::class, 'index']);

Route::get('category', [CategoryController::class, 'index']);
Route::get('category-all', [CategoryController::class, 'all']);
Route::get('category/{id}', [CategoryController::class, 'show']);

Route::post('user/login', [UserController::class, 'login']);
Route::post('user/register', [UserController::class, 'register']);

Route::get('blocks',[BlockController::class, 'index']);
Route::get('blocks/{id}',[BlockController::class, 'show']);

Route::get('config',[ConfigController::class, 'show']);

Route::get('orders/{orderRef}', [OrderController::class, 'show']);
Route::get('transaction/detail',[TransactionController::class, 'show']);

Route::get('banks', [BankController::class, 'index']);

Route::post('searchOrder', [OrderController::class, 'searchOrder']);
Route::get('getRandomOrder', [OrderController::class, 'getRandomOrder']);

Route::get('shipping/getProvince', [ShippingController::class, 'getProvince']);
Route::get('shipping/getCity/{province_id}', [ShippingController::class, 'getCity']);
Route::get('shipping/getSubdistict/{city_id}', [ShippingController::class, 'getSubdistrict']);
Route::post('shipping/getCost', [ShippingController::class, 'getCost']);
Route::post('shipping/waybill', [ShippingController::class, 'waybill']);
Route::get('shipping/findSubdistrict/{key}', [ShippingController::class, 'findSubdistrict']);

Route::get('tripay/payment-chanel',[TripayController::class, 'getPaymentChanels']);
Route::post('tripay/callback',[TripayController::class, 'callback'])->name('tripay.callback');

Route::get('carts/{sessId}', [CartController::class, 'get']);
Route::post('carts/{sessId}', [CartController::class, 'store']);
Route::put('carts/{sessId}', [CartController::class, 'update']);
Route::post('cart/delete', [CartController::class, 'destroy']);
Route::post('clearCart', [CartController::class, 'clear']);

Route::post('sendNotify', [NotifyController::class, 'sendNotify']);

Route::post('requestPasswordToken', [PasswordResetController::class, 'requestPasswordToken']);
Route::get('validateToken/{token}', [PasswordResetController::class, 'validateToken']);
Route::post('resetPassword', [PasswordResetController::class, 'resetPassword']);

Route::get('coupons/getByCode/{code}', [CouponController::class, 'getByCode']);
Route::post('redeemCoupon', [CouponController::class, 'redeemCoupon']);

Route::get('getInitialData', [FrontApiController::class, 'home']);