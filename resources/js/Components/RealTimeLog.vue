<template>
    <div class="bg-gray-900/90 backdrop-blur-sm rounded-xl font-mono text-sm h-80 overflow-y-auto p-4 shadow-inner">
        <div v-if="events.length === 0" class="flex items-center justify-center h-full text-gray-500">
            <div class="text-center">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <p>Waiting for bot events...</p>
                <p class="text-xs mt-1">Send a command to your bot on Telegram</p>
            </div>
        </div>
        <div v-else class="space-y-2">
            <div v-for="(ev, idx) in events" :key="idx" class="border-l-2 border-indigo-500 pl-3 py-1 hover:bg-gray-800/50 transition-colors">
                <div class="flex items-center gap-2 text-xs text-gray-400">
                    <span class="text-indigo-400">[{{ formatTime(ev.timestamp) }}]</span>
                    <span class="font-bold text-cyan-400">{{ ev.event }}</span>
                </div>
                <pre class="text-gray-300 text-xs mt-1 overflow-x-auto">{{ JSON.stringify(ev.data, null, 2) }}</pre>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useRealtimeStore } from '@/store/realtime';
import { storeToRefs } from 'pinia';

const store = useRealtimeStore();
const { events } = storeToRefs(store);

const formatTime = (timestamp) => {
    if (!timestamp) return new Date().toLocaleTimeString();
    return new Date(timestamp).toLocaleTimeString();
};
</script>