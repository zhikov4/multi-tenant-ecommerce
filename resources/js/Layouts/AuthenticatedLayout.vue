<script setup>
import { Link } from '@inertiajs/vue3';
</script>
<template>
    <div class="min-h-screen bg-gray-50">
        <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center gap-8">
                        <div class="text-2xl font-black text-indigo-600 tracking-tighter">MyStoreTest.</div>
                        <div class="hidden sm:flex gap-6 h-full">
                            <Link :href="route('products.index')" 
                                  :class="route().current('products.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500'" 
                                  class="text-sm font-bold inline-flex items-center px-1 pt-1 border-b-2 transition">
                                Inventory
                            </Link>
                            <Link :href="route('sales.report')" 
                                  :class="route().current('sales.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500'" 
                                  class="text-sm font-bold inline-flex items-center px-1 pt-1 border-b-2 transition">
                                Sales Report
                            </Link>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
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
