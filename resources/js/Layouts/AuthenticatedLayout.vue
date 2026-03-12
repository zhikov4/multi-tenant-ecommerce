<script setup>
import { Link } from '@inertiajs/vue3';
</script>
<template>
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center gap-8">
                        <div class="text-xl font-black text-indigo-600">MyStoreTest</div>
                        <Link :href="route('products.index')" class="text-sm font-medium text-gray-700">Products CRUD</Link>
                        <span class="text-sm text-gray-400">Sales Report</span>
                    </div>
                    <div class="flex items-center">
                        <Link :href="route('logout')" method="post" as="button" class="text-sm font-bold text-red-600 bg-red-50 px-4 py-2 rounded-lg">Logout</Link>
                    </div>
                </div>
            </div>
        </nav>
        <main><slot /></main>
    </div>
</template>
