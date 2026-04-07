<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ModerationController;
use App\Http\Controllers\TradeController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\TelegramOAuthController;
use Illuminate\Support\Facades\Route;

// Login page – uses controller to pass bot username
Route::get('/login', [TelegramOAuthController::class, 'redirectToTelegram'])->name('login');

// Telegram OAuth endpoints
Route::get('/auth/telegram', [TelegramOAuthController::class, 'redirectToTelegram'])->name('telegram.redirect');
Route::post('/auth/telegram/callback', [TelegramOAuthController::class, 'handleCallback'])->name('telegram.callback');

// Protected admin routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']); // optional alias
    Route::get('/analytics', fn() => inertia('Analytics'))->name('analytics');
    Route::get('/moderation', fn() => inertia('Moderation'))->name('moderation');
    Route::get('/trades', fn() => inertia('Trades'))->name('trades');
    Route::get('/invites', fn() => inertia('Invites'))->name('invites');
    Route::get('/users', fn() => inertia('Users'))->name('users');

    Route::prefix('api')->group(function () {
        Route::get('/heatmap', [AnalyticsController::class, 'heatmap']);
        Route::get('/hourly-stats', [AnalyticsController::class, 'hourlyStats']);
        Route::get('/top-users', [AnalyticsController::class, 'topUsers']);
        Route::apiResource('moderation-rules', ModerationController::class);
        Route::apiResource('trades', TradeController::class);
        Route::apiResource('invites', InviteController::class);
        Route::get('/users', [UserController::class, 'index']);
        Route::put('/users/{id}/ban', [UserController::class, 'ban']);
    });
});