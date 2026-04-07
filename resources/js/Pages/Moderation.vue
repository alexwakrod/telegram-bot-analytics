<template>
    <AppLayout title="Moderation">
        <div class="py-8 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-gray-50 via-indigo-50/30 to-purple-50/20 min-h-screen">
            <div class="max-w-7xl mx-auto space-y-8">
                <div class="bg-white/70 backdrop-blur-md rounded-2xl shadow-xl border border-white/30 p-6 transition-all duration-300 hover:shadow-2xl animate-fade-slide">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                                Auto-Moderation Rules
                            </h2>
                            <p class="text-gray-500 text-sm mt-1">Define automated triggers and safety thresholds</p>
                        </div>
                        <button @click="showForm = true" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-md shadow-indigo-200 transition-all duration-200 active:scale-95">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Rule
                        </button>
                    </div>

                    <!-- Form (Expandable) -->
                    <Transition name="expand">
                        <div v-if="showForm" class="mb-8 p-6 rounded-2xl bg-indigo-50/30 border border-indigo-100/50 backdrop-blur-sm">
                            <h3 class="text-indigo-900 font-bold mb-4 flex items-center">
                                <span class="w-2 h-2 bg-indigo-500 rounded-full mr-2"></span>
                                Create Rule
                            </h3>
                            <ModerationRuleForm :rule="null" @saved="fetchRules" @cancel="showForm = false" />
                        </div>
                    </Transition>

                    <Transition name="expand">
                        <div v-if="editingRule" class="mb-8 p-6 rounded-2xl bg-amber-50/30 border border-amber-100/50 backdrop-blur-sm">
                            <h3 class="text-amber-900 font-bold mb-4 flex items-center">
                                <span class="w-2 h-2 bg-amber-500 rounded-full mr-2"></span>
                                Edit Rule: {{ editingRule.rule_type }}
                            </h3>
                            <ModerationRuleForm :rule="editingRule" @saved="fetchRules" @cancel="editingRule = null" />
                        </div>
                    </Transition>

                    <!-- Loading -->
                    <div v-if="loading" class="flex flex-col items-center justify-center py-12">
                        <div class="relative w-12 h-12">
                            <div class="w-12 h-12 rounded-full border-4 border-indigo-50"></div>
                            <div class="w-12 h-12 rounded-full border-4 border-indigo-600 border-t-transparent animate-spin absolute top-0"></div>
                        </div>
                        <p class="mt-4 text-sm font-medium text-indigo-600 animate-pulse">Loading rules...</p>
                    </div>

                    <!-- Error -->
                    <div v-else-if="error" class="text-center py-12 bg-red-50/50 rounded-xl border border-red-100">
                        <div class="text-red-500 font-semibold">{{ error }}</div>
                        <button @click="fetchRules" class="mt-2 px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition">↻ Retry</button>
                    </div>

                    <!-- Table -->
                    <div v-else>
                        <div class="overflow-x-auto rounded-xl border border-gray-100">
                            <table class="min-w-full divide-y divide-gray-200/50">
                                <thead class="bg-gradient-to-r from-indigo-50/50 to-purple-50/50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Threshold</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Duration</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Enabled</th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold text-indigo-700 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white/50">
                                    <tr v-for="rule in rules" :key="rule.id" class="hover:bg-indigo-50/30 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase"
                                                :class="{
                                                    'bg-red-100 text-red-700': rule.rule_type === 'ban',
                                                    'bg-yellow-100 text-yellow-700': rule.rule_type === 'mute',
                                                    'bg-blue-100 text-blue-700': rule.rule_type === 'warn'
                                                }">
                                                {{ rule.rule_type }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ rule.threshold }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ rule.duration_minutes ? rule.duration_minutes + ' min' : 'Permanent' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="rule.enabled ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'" class="px-2 py-1 rounded-full text-xs font-semibold">
                                                {{ rule.enabled ? 'Active' : 'Disabled' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right space-x-3">
                                            <button @click="editRule(rule)" class="text-indigo-600 hover:text-indigo-800 transition" title="Edit">
                                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button @click="deleteRule(rule.id)" class="text-red-500 hover:text-red-700 transition" title="Delete">
                                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="!rules.length">
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">No moderation rules found.</td>
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
import ModerationRuleForm from '@/Components/ModerationRuleForm.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const rules = ref([]);
const showForm = ref(false);
const editingRule = ref(null);
const loading = ref(false);
const error = ref(null);

async function fetchRules() {
    loading.value = true;
    error.value = null;
    try {
        const res = await axios.get('/api/moderation-rules');
        rules.value = res.data;
        showForm.value = false;
        editingRule.value = null;
    } catch {
        error.value = 'Failed to load moderation rules.';
    } finally {
        loading.value = false;
    }
}

async function deleteRule(id) {
    if (!confirm('Delete this rule?')) return;
    await axios.delete(`/api/moderation-rules/${id}`);
    fetchRules();
}

function editRule(rule) {
    editingRule.value = rule;
    showForm.value = false;
}

onMounted(fetchRules);
</script>

<style scoped>
.animate-fade-slide {
    animation: fadeSlide 0.5s ease-out forwards;
}
@keyframes fadeSlide {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.expand-enter-active, .expand-leave-active {
    transition: all 0.3s ease;
    max-height: 500px;
}
.expand-enter-from, .expand-leave-to {
    opacity: 0;
    max-height: 0;
}
</style>