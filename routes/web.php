<?php

use App\Http\Controllers\AiConversationController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('dashboard', function () {
    return redirect()->route('conversation-form');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/', function () {
    return redirect()->route('conversation-form');
})->name('home');

Route::prefix('chatbot')->group(function() {
	Route::get('/', [AiConversationController::class, 'conversationForm'])->name('conversation-form');
});

Route::prefix('ai')->group(function() {
	Route::post('/conversation', [AiConversationController::class, 'ingest']);
});