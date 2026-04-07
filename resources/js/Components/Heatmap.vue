<template>
    <div class="w-full">
        <div v-if="loading" class="flex flex-col items-center justify-center py-12">
            <div class="relative w-12 h-12">
                <div class="w-12 h-12 rounded-full border-4 border-indigo-50"></div>
                <div class="w-12 h-12 rounded-full border-4 border-indigo-600 border-t-transparent animate-spin absolute top-0"></div>
            </div>
            <p class="mt-4 text-indigo-600 animate-pulse">Loading heatmap data...</p>
        </div>
        <div v-else-if="error" class="text-center py-12 bg-red-50/50 rounded-xl border border-red-100">
            <div class="text-red-500 font-semibold">{{ error }}</div>
            <button @click="fetchData" class="mt-2 px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200">↻ Retry</button>
        </div>
        <CalendarHeatmap v-else :start-date="startDate" :end-date="endDate" :values="heatmapData" />
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { CalendarHeatmap } from '@silverwind/vue3-calendar-heatmap';
import '@silverwind/vue3-calendar-heatmap/dist/style.css';
import axios from 'axios';

const heatmapData = ref([]);
const loading = ref(false);
const error = ref(null);

const startDate = computed(() => {
    if (heatmapData.value.length) return heatmapData.value[0].date;
    const d = new Date(); d.setFullYear(d.getFullYear() - 1);
    return d.toISOString().slice(0,10);
});
const endDate = computed(() => {
    if (heatmapData.value.length) return heatmapData.value[heatmapData.value.length-1].date;
    return new Date().toISOString().slice(0,10);
});

async function fetchData() {
    loading.value = true;
    error.value = null;
    try {
        const res = await axios.get('/api/heatmap');
        heatmapData.value = res.data;
    } catch {
        error.value = 'Failed to load heatmap.';
    } finally {
        loading.value = false;
    }
}

onMounted(fetchData);
</script>