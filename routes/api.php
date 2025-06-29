<?php
use App\Http\Controllers\ProductCategory\ProductCategoryApiController;
use App\Http\Controllers\Product\ProductApiController;
use App\Http\Controllers\Cart\CartApiController;
use App\Http\Controllers\Customer\CustomerAuthApiController;
use App\Http\Controllers\Customer\CustomerAccountApiController;
use App\Http\Controllers\Order\OrderApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:custom_limit'])->group(function () {
    // ==================== User API Routes ====================
    Route::prefix('user')->group(function () {
        Route::get('/', function (Request $request) {
            return $request->user();
        });
    });

    // ==================== Product API Routes ====================
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductApiController::class, 'index'])->name('api.products.index'); // List all products
        Route::get('/search', [ProductApiController::class, 'search']); // Search products by name or description
        Route::get('/{product}', [ProductApiController::class, 'show'])->name('api.products.show'); // Show details of a single product
    });

    // ==================== Product Category API Routes ====================
    Route::prefix('product-categories')->group(function () {
        Route::get('/', [ProductCategoryApiController::class, 'index'])->name('api.product-categories.index'); // List all product categories
        Route::get('/full', [ProductCategoryApiController::class, 'categoriesWithProducts'])->name('api.product-categories.full'); // List all product categories with products
    });

    // ==================== Customer API Routes ====================
    Route::prefix('customer')->group(function () {
        Route::post('/login', [CustomerAuthApiController::class, 'login']); // Customer login
        Route::post('/register', [CustomerAuthApiController::class, 'register']); // Customer registration
        Route::post('/generate-captcha', [CustomerAuthApiController::class, 'generateCaptcha']); // Generate captcha
    });

});

// ==================== Authenticated Routes ====================
Route::middleware(['throttle:custom_limit', 'auth:api'])->group(function () {
    Route::get('/test', function () {
        return response()->json([
            'message' => 'Authenticated API is working!',
            'status' => 'success',
            'timestamp' => now(),
        ]);
    });

    // ==================== Customer Authentication API Routes ====================
    Route::prefix('customer')->group(function () {
        Route::get('/username', [CustomerAccountApiController::class, 'getUserName']);
    });

    // ==================== Cart API Routes ====================
    Route::prefix('cart')->group(function () {
        Route::post('/add', [CartApiController::class, 'addToCart'])->name('api.cart.add'); // Add product to cart
        Route::get('/', [CartApiController::class, 'getCart'])->name('api.cart.get'); // Get cart for a customer
        Route::delete('/remove', [CartApiController::class, 'removeFromCart'])->name('api.cart.remove'); // Remove product from cart
        Route::delete('/remove-quantity', [CartApiController::class, 'removeQuantityFromCart'])->name('api.cart.removeQuantity'); // Remove specific quantity from cart
    });

    // ==================== Order API Routes ====================
    Route::prefix('order')->group(function () {
        Route::post('/create', [OrderApiController::class, 'createOrder'])->name('api.orders.create'); // Create a new order
    });
});