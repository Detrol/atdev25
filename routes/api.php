<?php

use App\Http\Controllers\AIAssistantController;
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

// Tech Stack API
Route::get('/tech-stack', function () {
    $controller = new TechStackController();
    $view = $controller->index();
    $techData = $view->getData()['techData'];
    return response()->json($techData);
})->name('api.tech-stack');
