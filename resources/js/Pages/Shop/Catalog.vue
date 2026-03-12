<script setup>
import { Link, Head } from "@inertiajs/vue3";
defineProps({ products: Array, tenantName: String });
</script>
<template>
    <Head :title="tenantName" />
    <div class="min-h-screen bg-white">
        <nav class="p-6 border-b flex justify-between items-center">
            <h1 class="text-2xl font-black text-indigo-600">{{ tenantName }}</h1>
            <div class="space-x-4">
                <Link :href="route('login')" class="text-sm font-bold">Login Buyer</Link>
                <Link :href="route('register')" class="bg-black text-white px-4 py-2 rounded-lg text-sm">Sign Up</Link>
            </div>
        </nav>
        <div class="max-w-7xl mx-auto p-10">
            <h2 class="text-3xl font-bold mb-10">Product Catalog</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div v-for="p in products" :key="p.id" class="border rounded-xl p-4 hover:shadow-lg transition">
                    <div class="h-48 bg-gray-100 rounded-lg mb-4 flex items-center justify-center text-gray-400">No Image</div>
                    <h3 class="font-bold text-lg text-black">{{ p.name }}</h3>
                    <p class="text-indigo-600 font-black mt-1">${{ p.price }}</p>
                    <button class="w-full mt-4 bg-indigo-600 text-white py-2 rounded-lg font-bold">Add to Cart</button>
                </div>
            </div>
        </div>
    </div>
</template>
