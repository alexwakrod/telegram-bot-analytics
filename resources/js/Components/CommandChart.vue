<template>
    <apexchart type="line" height="350" :options="chartOptions" :series="series" />
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({ data: Array });
const series = computed(() => [{ name: 'Commands', data: props.data.map(d => d.total) }]);
const chartOptions = computed(() => ({
    chart: { toolbar: { show: false } },
    xaxis: { categories: props.data.map(d => `${d.hour}:00`) },
    title: { text: 'Commands per Hour' },
    stroke: { curve: 'smooth' },
}));
</script>