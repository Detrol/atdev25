<?php

use App\Http\Controllers\AIAssistantController;
use App\Http\Controllers\PriceCalculatorController;
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

// Price Calculator API
Route::post('/price-estimate', [PriceCalculatorController::class, 'estimate'])
    ->middleware('throttle:5,10') // 5 requests per 10 minutes
    ->name('api.price-estimate');

// Tech Stack API
Route::get('/tech-stack', function () {
    $controller = new TechStackController();
    $view = $controller->index();
    $techData = $view->getData()['techData'];
    return response()->json($techData);
})->name('api.tech-stack');
