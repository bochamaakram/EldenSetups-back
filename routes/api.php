<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\OrderItemController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\TrackingHistoryController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CartItemController;
use App\Http\Controllers\API\DeliveryController;
use App\Http\Controllers\API\DeliveryItemController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\ContactController;

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

// Public routes
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/products/featured', [ProductController::class, 'featured']);

Route::get('/orders', [OrderController::class, 'index']);
Route::get('/orders/{id}', [OrderController::class, 'show']);

Route::get('/order-items', [OrderItemController::class, 'index']);
Route::get('/order-items/{id}', [OrderItemController::class, 'show']);

Route::get('/tracking-history', [TrackingHistoryController::class, 'index']);
Route::get('/tracking-history/{id}', [TrackingHistoryController::class, 'show']);

Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);

Route::get('/carts', [CartController::class, 'index']);
Route::get('/carts/{id}', [CartController::class, 'show']);

Route::get('/cart-items', [CartItemController::class, 'index']);
Route::get('/cart-items/{id}', [CartItemController::class, 'show']);

Route::get('/deliveries', [DeliveryController::class, 'index']);
Route::get('/deliveries/{id}', [DeliveryController::class, 'show']);

Route::get('/delivery-items', [DeliveryItemController::class, 'index']);
Route::get('/delivery-items/{id}', [DeliveryItemController::class, 'show']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    Route::post('/orders', [OrderController::class, 'store']);
    Route::put('/orders/{id}', [OrderController::class, 'update']);
    Route::delete('/orders/{id}', [OrderController::class, 'destroy']);

    Route::post('/order-items', [OrderItemController::class, 'store']);
    Route::put('/order-items/{id}', [OrderItemController::class, 'update']);
    Route::delete('/order-items/{id}', [OrderItemController::class, 'destroy']);

    Route::post('/tracking-history', [TrackingHistoryController::class, 'store']);
    Route::put('/tracking-history/{id}', [TrackingHistoryController::class, 'update']);
    Route::delete('/tracking-history/{id}', [TrackingHistoryController::class, 'destroy']);

    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    Route::post('/carts', [CartController::class, 'store']);
    Route::put('/carts/{id}', [CartController::class, 'update']);
    Route::delete('/carts/{id}', [CartController::class, 'destroy']);

    Route::post('/cart-items', [CartItemController::class, 'store']);
    Route::put('/cart-items/{id}', [CartItemController::class, 'update']);
    Route::delete('/cart-items/{id}', [CartItemController::class, 'destroy']);

    Route::post('/deliveries', [DeliveryController::class, 'store']);
    Route::put('/deliveries/{id}', [DeliveryController::class, 'update']);
    Route::delete('/deliveries/{id}', [DeliveryController::class, 'destroy']);

    Route::post('/delivery-items', [DeliveryItemController::class, 'store']);
    Route::put('/delivery-items/{id}', [DeliveryItemController::class, 'update']);
    Route::delete('/delivery-items/{id}', [DeliveryItemController::class, 'destroy']);
});

// Authentication routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json([
        'user' => $request->user(),
        'token' => $request->bearerToken()
    ]);
});

Route::middleware(['api'])->group(function () {
    // Public endpoints
    Route::post('/contact', [ContactController::class, 'store']);
    
    // Protected endpoints (require authentication)
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/contact', [ContactController::class, 'index']);
        Route::get('/contact/{id}', [ContactController::class, 'show']);
        Route::put('/contact/{id}', [ContactController::class, 'update']);
        Route::delete('/contact/{id}', [ContactController::class, 'destroy']);
        Route::patch('/contact/{id}/read', [ContactController::class, 'markAsRead']);
    });
});