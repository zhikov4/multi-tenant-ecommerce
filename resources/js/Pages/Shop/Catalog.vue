<script setup>
import { Head, Link } from "@inertiajs/vue3";
defineProps({ products: Array, tenantId: String });
</script>
<template>
    <Head :title="tenantId" />
    <div class="min-h-screen bg-white text-black">
        <nav class="p-8 border-b flex justify-between items-center bg-gray-50">
            <h1 class="text-3xl font-black tracking-tighter uppercase">{{ tenantId }}</h1>
            <div class="flex gap-6 items-center">
                <Link :href="route('login')" class="text-sm font-bold">Buyer Login</Link>
                <Link :href="route('register')" class="bg-black text-white px-6 py-2 rounded-full text-sm font-bold">Sign Up</Link>
            </div>
        </nav>
        <div class="max-w-7xl mx-auto p-12">
            <h2 class="text-5xl font-black mb-16 tracking-tight">Available Items</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <div v-for="p in products" :key="p.id" class="group">
                    <div class="aspect-square bg-gray-100 rounded-3xl mb-6 flex items-center justify-center text-gray-400 group-hover:bg-gray-200 transition">Image Placeholder</div>
                    <h3 class="font-bold text-xl">{{ p.name }}</h3>
                    <p class="text-gray-500 mb-4">Quality item from {{ tenantId }}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-2xl font-black text-indigo-600">${{ p.price }}</span>
                        <button class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-sm font-bold">Add to Cart</button>
                    </div>
                </div>
            </div>
            <div v-if="products.length === 0" class="text-center py-20 text-gray-400 italic">This store hasn't added any products yet.</div>
        </div>
    </div>
</template>
