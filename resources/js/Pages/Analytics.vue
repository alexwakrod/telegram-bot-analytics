<template>
    <AppLayout title="Analytics">
        <div class="py-8 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-gray-50 via-indigo-50/30 to-purple-50/20 min-h-screen">
            <div class="max-w-7xl mx-auto space-y-8">
                <!-- Commands per Hour Card -->
                <div class="bg-white/70 backdrop-blur-md rounded-2xl shadow-xl border border-white/30 p-6 transition-all duration-300 hover:shadow-2xl animate-fade-slide">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                                Commands per Hour
                            </h2>
                            <p class="text-gray-500 text-sm mt-1">Real-time command frequency distribution</p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="w-full h-80">
                        <CommandChart :data="hourlyStats" />
                    </div>
                </div>

                <!-- Top Users Card -->
                <div class="bg-white/70 backdrop-blur-md rounded-2xl shadow-xl border border-white/30 p-6 transition-all duration-300 hover:shadow-2xl animate-fade-slide" style="animation-delay: 0.1s">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                                Top Users
                            </h2>
                            <p class="text-gray-500 text-sm mt-1">Most active users by command count</p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <TopUsers :users="topUsers" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import CommandChart from '@/Components/CommandChart.vue';
import TopUsers from '@/Components/TopUsers.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const hourlyStats = ref([]);
const topUsers = ref([]);
const loading = ref(false);
const error = ref(null);

onMounted(async () => {
    loading.value = true;
    try {
        const [hourly, top] = await Promise.all([
            axios.get('/api/hourly-stats'),
            axios.get('/api/top-users?limit=10')
        ]);
        hourlyStats.value = hourly.data;
        topUsers.value = top.data;
    } catch (err) {
        error.value = 'Failed to load analytics data.';
        console.error(err);
    } finally {
        loading.value = false;
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