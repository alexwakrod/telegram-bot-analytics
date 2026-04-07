<template>
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-white border-b border-gray-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="shrink-0 flex items-center">
                            <h1 class="text-xl font-bold text-indigo-600">Bot Dashboard</h1>
                        </div>
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <NavLink :href="route('dashboard')" :active="route().current('dashboard')">Dashboard</NavLink>
                            <NavLink :href="route('analytics')" :active="route().current('analytics')">Analytics</NavLink>
                            <NavLink :href="route('moderation')" :active="route().current('moderation')">Moderation</NavLink>
                            <NavLink :href="route('trades')" :active="route().current('trades')">Trades</NavLink>
                            <NavLink :href="route('invites')" :active="route().current('invites')">Invites</NavLink>
                            <NavLink :href="route('users')" :active="route().current('users')">Users</NavLink>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <form method="POST" action="/logout">
                            <button type="submit" class="text-gray-500 hover:text-gray-700">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
        <main class="py-6"><slot /></main>
    </div>
</template>

<script setup>
import NavLink from '@/Components/NavLink.vue';
</script>