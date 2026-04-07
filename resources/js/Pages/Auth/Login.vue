<template>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-900 via-purple-900 to-pink-800 relative overflow-hidden">
        <!-- Animated background particles -->
        <div class="absolute inset-0 overflow-hidden">
            <div v-for="i in 20" :key="i" class="absolute bg-white/10 rounded-full animate-float" :style="{
                width: `${Math.random() * 6 + 2}rem`,
                height: `${Math.random() * 6 + 2}rem`,
                top: `${Math.random() * 100}%`,
                left: `${Math.random() * 100}%`,
                animationDelay: `${Math.random() * 5}s`,
                animationDuration: `${Math.random() * 10 + 10}s`
            }"></div>
        </div>

        <div class="relative z-10 w-full max-w-md px-6">
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl shadow-2xl p-8 border border-white/20 animate-fade-in-up">
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-tr from-indigo-500 to-purple-600 rounded-2xl shadow-lg mb-4">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-white">Welcome Back</h2>
                    <p class="text-indigo-200 mt-2">Sign in with Telegram to continue</p>
                </div>

                <div v-if="!telegramBotUsername" class="mb-6 p-3 bg-red-500/20 border border-red-500/30 rounded-lg text-red-100 text-sm text-center animate-pulse">
                    ⚠️ Bot username not configured. Please contact administrator.
                </div>

                <div v-else class="flex justify-center">
                    <div id="telegram-login"></div>
                </div>

                <div class="mt-8 text-center text-xs text-indigo-300">
                    <button @click="reloadWidget" class="hover:text-white transition-colors duration-200">
                        ↻ Reload widget
                    </button>
                </div>
            </div>
            <div class="text-center mt-6 text-indigo-300 text-xs">
                Powered by Telegram Login
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted } from 'vue';

const props = defineProps({
    telegramBotUsername: String
});

const reloadWidget = () => {
    const container = document.getElementById('telegram-login');
    if (container) container.innerHTML = '';
    initWidget();
};

const initWidget = () => {
    if (!props.telegramBotUsername) return;

    window.onTelegramAuth = async (user) => {
        try {
            const response = await fetch('/auth/telegram/callback', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(user)
            });
            if (response.ok) {
                window.location.href = '/dashboard';
            } else {
                const text = await response.text();
                alert('Login failed: ' + text);
            }
        } catch (err) {
            alert('Network error: ' + err.message);
        }
    };

    const container = document.getElementById('telegram-login');
    container.innerHTML = '';
    const script = document.createElement('script');
    script.src = 'https://telegram.org/js/telegram-widget.js?23';
    script.setAttribute('data-telegram-login', props.telegramBotUsername);
    script.setAttribute('data-size', 'large');
    script.setAttribute('data-onauth', 'onTelegramAuth(user)');
    script.setAttribute('data-request-access', 'write');
    script.async = true;
    container.appendChild(script);
};

onMounted(() => {
    initWidget();
});
</script>

<style scoped>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.animate-fade-in-up {
    animation: fadeInUp 0.8s ease-out forwards;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0) rotate(0deg);
    }
    50% {
        transform: translateY(-20px) rotate(5deg);
    }
}
.animate-float {
    animation: float linear infinite;
}
</style>