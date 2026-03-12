<script setup>
import { Link } from '@inertiajs/vue3';
</script>
<template>
    <div class="min-h-screen bg-gray-50">
        <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center gap-8">
                        <div class="text-2xl font-black text-indigo-600 tracking-tighter">
                            MyStore<span class="text-gray-900">Test</span>.
                        </div>
                        <div class="hidden sm:flex gap-4">
                            <Link :href="route('tenant.home')" class="text-sm font-bold text-gray-900 border-b-2 border-indigo-500 px-1 py-4">Inventory</Link>
                            <a href="#" class="text-sm font-medium text-gray-500 px-1 py-4 hover:text-gray-900">Sales Report</a>
                            <a href="#" class="text-sm font-medium text-gray-500 px-1 py-4 hover:text-gray-900">Settings</a>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-xs text-gray-400 hidden md:inline">Admin Mode</span>
                        <Link :href="route('logout')" method="post" as="button" class="text-sm font-bold text-red-600 bg-red-50 px-4 py-2 rounded-lg hover:bg-red-100 transition">
                            Logout
                        </Link>
                    </div>
                </div>
            </div>
        </nav>
        <main><slot /></main>
    </div>
</template>
