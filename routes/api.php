<?php

use App\Http\Controllers\AIAssistantController;
use App\Http\Controllers\Api\GoogleReviewsController;
use App\Http\Controllers\PriceCalculatorController;
use App\Http\Controllers\SmartMenuController;
use App\Http\Controllers\TechStackController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| API routes för applikationen. Dessa routes är stateless och bör
| använda token-baserad autentisering om de behöver autentisering.
|
*/

// AI Assistant routes
Route::prefix('chat')->group(function () {
    Route::post('/', [AIAssistantController::class, 'chat'])
        ->middleware('throttle:5,1') // 5 requests per minute
        ->name('api.chat');

    Route::get('/history', [AIAssistantController::class, 'getChatHistory'])
        ->name('api.chat.history');
});

// Price Calculator API (rate limiting hanteras i controller för bättre meddelanden)
Route::post('/price-estimate', [PriceCalculatorController::class, 'estimate'])
    ->name('api.price-estimate');

// Smart Menu API (allergen analysis demo)
Route::prefix('menu')->group(function () {
    Route::post('/analyze-allergens', [SmartMenuController::class, 'analyzeAllergens'])
        ->middleware('throttle:10,1') // 10 requests per minute
        ->name('api.menu.analyze-allergens');
});

// Tech Stack API
Route::get('/tech-stack', function () {
    $controller = new TechStackController;
    $view = $controller->index();
    $techData = $view->getData()['techData'];

    return response()->json($techData);
})->name('api.tech-stack');

// Google Reviews Demo API
Route::prefix('demos/google-reviews')->group(function () {
    Route::get('/default', [GoogleReviewsController::class, 'default'])
        ->middleware('throttle:60,1') // 60 requests per minute for default example
        ->name('api.demos.google-reviews.default');

    Route::post('/search', [GoogleReviewsController::class, 'search'])
        ->middleware('throttle:20,1') // 20 searches per minute
        ->name('api.demos.google-reviews.search');

    Route::get('/{placeId}', [GoogleReviewsController::class, 'show'])
        ->middleware('throttle:30,1') // 30 requests per minute
        ->name('api.demos.google-reviews.show');
});
