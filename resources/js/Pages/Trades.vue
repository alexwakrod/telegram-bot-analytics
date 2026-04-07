<template>
    <AppLayout title="Trades">
        <div class="py-8 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-gray-50 via-indigo-50/30 to-purple-50/20 min-h-screen">
            <div class="max-w-7xl mx-auto space-y-8">
                <div class="bg-white/70 backdrop-blur-md rounded-2xl shadow-xl border border-white/30 p-6 transition-all duration-300 hover:shadow-2xl animate-fade-slide">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                                All Trades
                            </h2>
                            <p class="text-gray-500 text-sm mt-1">User trade history and status</p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>
                    </div>

                    <div v-if="loading" class="flex flex-col items-center justify-center py-12">
                        <div class="relative w-12 h-12"><div class="w-12 h-12 rounded-full border-4 border-indigo-50"></div><div class="w-12 h-12 rounded-full border-4 border-indigo-600 border-t-transparent animate-spin absolute top-0"></div></div>
                        <p class="mt-4 text-indigo-600 animate-pulse">Loading trades...</p>
                    </div>

                    <div v-else-if="error" class="text-center py-12 bg-red-50/50 rounded-xl border border-red-100">
                        <div class="text-red-500 font-semibold">{{ error }}</div>
                        <button @click="fetchTrades" class="mt-2 px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200">↻ Retry</button>
                    </div>

                    <div v-else>
                        <div class="overflow-x-auto rounded-xl border border-gray-100">
                            <table class="min-w-full divide-y divide-gray-200/50">
                                <thead class="bg-gradient-to-r from-indigo-50/50 to-purple-50/50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Initiator</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Acceptor</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Data</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Created</th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold text-indigo-700 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white/50">
                                    <tr v-for="trade in trades" :key="trade.id" class="hover:bg-indigo-50/30 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono">{{ trade.id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ trade.initiator_id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ trade.acceptor_id || '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="trade.status === 'confirmed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'" class="px-2 py-1 rounded-full text-xs font-semibold">
                                                {{ trade.status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm"><pre class="text-xs">{{ JSON.stringify(trade.data) }}</pre></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ new Date(trade.created_at).toLocaleString() }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <button @click="deleteTrade(trade.id)" class="text-red-500 hover:text-red-700 transition" title="Delete">
                                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="!trades.length">
                                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">No trades found.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const trades = ref([]);
const loading = ref(false);
const error = ref(null);

async function fetchTrades() {
    loading.value = true;
    try {
        const res = await axios.get('/api/trades');
        trades.value = res.data;
    } catch {
        error.value = 'Failed to load trades.';
    } finally {
        loading.value = false;
    }
}

async function deleteTrade(id) {
    if (!confirm('Delete this trade?')) return;
    await axios.delete(`/api/trades/${id}`);
    fetchTrades();
}

onMounted(fetchTrades);
</script>

<style scoped>
.animate-fade-slide {
    animation: fadeSlide 0.5s ease-out forwards;
}
@keyframes fadeSlide {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>