<?php

use App\Http\Controllers\AiConversationController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';

Route::prefix('chatbot')->group(function() {
	Route::get('/', [AiConversationController::class, 'conversationForm'])->name('conversation-form');
});

Route::prefix('ai')->group(function() {
	Route::post('/conversation', [AiConversationController::class, 'ingest']);
});