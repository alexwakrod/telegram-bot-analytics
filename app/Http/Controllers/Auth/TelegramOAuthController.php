<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class TelegramOAuthController extends Controller
{
    public function redirectToTelegram()
    {
        return inertia('Auth/Login', [
            'telegramBotUsername' => config('services.telegram.bot_username'),
        ]);
    }

    public function handleCallback(Request $request)
    {
        try {
            $telegramUser = Socialite::driver('telegram')->user();
        } catch (\Exception $e) {
            \Log::error('Telegram Socialite error: ' . $e->getMessage());
            return redirect()->route('login')->withErrors(['error' => 'Telegram authentication failed.']);
        }

        $telegramId = (string) $telegramUser->getId();
        $name = $telegramUser->getName();          // full name (e.g., "John Doe")
        $username = $telegramUser->getNickname();  // Telegram username (without @)

        // If no full name, fallback to username or "Telegram User"
        $displayName = $name ?: ($username ?: 'Telegram User');

        // Get admin IDs from config, convert to strings for safe comparison
        $adminIds = config('services.telegram.admin_ids', []);
        $adminIds = array_map('strval', $adminIds);
        $isAdmin = in_array($telegramId, $adminIds, true);

        \Log::info('Telegram login attempt', [
            'telegram_id' => $telegramId,
            'is_admin' => $isAdmin,
            'admin_list' => $adminIds,
        ]);

        $user = User::updateOrCreate(
            ['telegram_id' => $telegramId],
            [
                'name' => $displayName,
                'email' => "telegram_{$telegramId}@telegram.local",
                'password' => Hash::make(Str::random(24)),
                'is_admin' => $isAdmin,
            ]
        );

        Auth::login($user, true);
        $request->session()->regenerate();

        \Log::info('User logged in', [
            'user_id' => $user->id,
            'telegram_id' => $user->telegram_id,
            'is_admin' => $user->is_admin,
        ]);

        return redirect()->intended('/');
    }
}