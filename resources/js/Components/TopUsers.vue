<template>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User ID</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Commands</th></tr>
        </thead>
        <tbody>
            <tr v-for="user in users" :key="user.user_id">
                <td class="px-6 py-4">{{ user.user_id }}</td>
                <td class="px-6 py-4">{{ user.total }}</td>
            </tr>
        </tbody>
    </table>
</template>

<script setup>
defineProps(['users']);
</script>