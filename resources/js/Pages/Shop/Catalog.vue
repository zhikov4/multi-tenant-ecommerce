<script setup>
import { Head, Link } from "@inertiajs/vue3";
defineProps({ products: Array, tenantId: String, auth: Object });
</script>
<template>
    <Head :title="'Welcome to ' + tenantId" />
    <div class="min-h-screen bg-gray-50 text-black">
        <nav class="p-6 bg-white border-b flex justify-between items-center shadow-sm sticky top-0">
            <h1 class="text-2xl font-black text-indigo-600 uppercase">{{ tenantId }}</h1>
            <div class="flex gap-4 items-center">
                <template v-if="auth.user">
                    <span class="text-sm font-bold">Hi, {{ auth.user.name }}</span>
                    <Link :href="route('logout')" method="post" as="button" class="text-red-600 text-sm">Logout</Link>
                </template>
                <template v-else>
                    <Link :href="route('login')" class="text-sm font-bold">Buyer Login</Link>
                    <Link :href="route('register')" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-bold">Join Store</Link>
                </template>
            </div>
        </nav>
        <div class="max-w-7xl mx-auto py-12 px-6">
            <div class="mb-10">
                <h2 class="text-4xl font-black tracking-tight">Our Collection</h2>
                <p class="text-gray-500">Discover quality products curated just for you by {{ tenantId }}.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div v-for="p in products" :key="p.id" class="bg-white border rounded-2xl p-4 hover:shadow-xl transition group">
                    <div class="aspect-square bg-gray-100 rounded-xl mb-4 flex items-center justify-center text-gray-400 group-hover:bg-gray-200 transition">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="font-bold text-lg leading-tight">{{ p.name }}</h3>
                    <div class="mt-4 flex justify-between items-center">
                        <span class="text-2xl font-black text-indigo-600">${{ p.price }}</span>
                        <button class="bg-black text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-gray-800">Add to Cart</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
