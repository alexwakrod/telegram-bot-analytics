<template>
    <AppLayout title="Users">
        <div class="py-8 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-gray-50 via-indigo-50/30 to-purple-50/20 min-h-screen">
            <div class="max-w-7xl mx-auto space-y-8">
                <div class="bg-white/70 backdrop-blur-md rounded-2xl shadow-xl border border-white/30 p-6 transition-all duration-300 hover:shadow-2xl animate-fade-slide">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                                Dashboard Users
                            </h2>
                            <p class="text-gray-500 text-sm mt-1">Manage admin accounts and user permissions</p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>

                    <div v-if="loading" class="flex flex-col items-center justify-center py-12">
                        <div class="relative w-12 h-12"><div class="w-12 h-12 rounded-full border-4 border-indigo-50"></div><div class="w-12 h-12 rounded-full border-4 border-indigo-600 border-t-transparent animate-spin absolute top-0"></div></div>
                        <p class="mt-4 text-indigo-600 animate-pulse">Loading users...</p>
                    </div>

                    <div v-else-if="error" class="text-center py-12 bg-red-50/50 rounded-xl border border-red-100">
                        <div class="text-red-500 font-semibold">{{ error }}</div>
                        <button @click="fetchUsers" class="mt-2 px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200">↻ Retry</button>
                    </div>

                    <div v-else>
                        <div class="overflow-x-auto rounded-xl border border-gray-100">
                            <table class="min-w-full divide-y divide-gray-200/50">
                                <thead class="bg-gradient-to-r from-indigo-50/50 to-purple-50/50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Admin</th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold text-indigo-700 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white/50">
                                    <tr v-for="user in users" :key="user.id" class="hover:bg-indigo-50/30 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ user.id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ user.name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ user.email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="user.is_admin ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'" class="px-2 py-1 rounded-full text-xs font-semibold">
                                                {{ user.is_admin ? 'Yes' : 'No' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <button @click="banUser(user.id)" class="text-red-500 hover:text-red-700 transition" title="Ban user">
                                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="!users.length">
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">No users found.</td>
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

const users = ref([]);
const loading = ref(false);
const error = ref(null);

async function fetchUsers() {
    loading.value = true;
    try {
        const res = await axios.get('/api/users');
        users.value = res.data;
    } catch {
        error.value = 'Failed to load users.';
    } finally {
        loading.value = false;
    }
}

async function banUser(id) {
    if (!confirm('Ban this user? This action is reversible.')) return;
    await axios.put(`/api/users/${id}/ban`);
    alert('User has been banned (simulated).');
}

onMounted(fetchUsers);
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