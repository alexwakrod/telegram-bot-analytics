<template>
    <AppLayout title="Dashboard">
        <div class="py-8 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-gray-50 via-indigo-50/30 to-purple-50/20 min-h-screen">
            <div class="max-w-7xl mx-auto space-y-8">
                <!-- Real‑time Bot Events Card -->
                <div class="bg-white/70 backdrop-blur-md rounded-2xl shadow-xl border border-white/30 p-6 transition-all duration-300 hover:shadow-2xl animate-fade-slide">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                                Real‑time Bot Events
                            </h2>
                            <p class="text-gray-500 text-sm mt-1">Live command events from Telegram bot</p>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-0 rounded-full bg-green-500 animate-ping opacity-75"></div>
                            <div class="relative w-3 h-3 bg-green-500 rounded-full"></div>
                        </div>
                    </div>
                    <RealTimeLog />
                </div>

                <!-- Command Heatmap Card -->
                <div class="bg-white/70 backdrop-blur-md rounded-2xl shadow-xl border border-white/30 p-6 transition-all duration-300 hover:shadow-2xl animate-fade-slide" style="animation-delay: 0.1s">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                                Command Heatmap
                            </h2>
                            <p class="text-gray-500 text-sm mt-1">Last 365 days of command activity</p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                    </div>
                    <Heatmap />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Heatmap from '@/Components/Heatmap.vue';
import RealTimeLog from '@/Components/RealTimeLog.vue';
import { onMounted } from 'vue';
import { useRealtimeStore } from '@/store/realtime';

const store = useRealtimeStore();

onMounted(() => {
    if (window.Echo) {
        window.Echo.channel('bot-events').listen('BotEvent', (e) => {
            store.addEvent(e.data);
        });
    } else {
        console.warn('Echo not initialized. Real‑time events disabled.');
    }
});
</script>

<style scoped>
@keyframes fadeSlide {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.animate-fade-slide {
    animation: fadeSlide 0.5s ease-out forwards;
}
</style>