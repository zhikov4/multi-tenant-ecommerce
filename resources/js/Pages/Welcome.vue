<script setup>
import { Link, Head } from '@inertiajs/vue3';
defineProps({ tenants: Array, user: Object });
</script>
<template>
    <Head title="Platform eCommerce" />
    <div class="min-h-screen bg-gray-50">
        <nav class="bg-white p-6 shadow-sm flex justify-between items-center">
            <h1 class="text-2xl font-black text-indigo-600">MyStorePlatform.</h1>
            <div v-if="user" class="flex items-center gap-4">
                <span class="text-sm font-bold text-gray-700">Hello, {{ user.name }}</span>
                <Link :href="route('logout')" method="post" as="button" class="text-red-500 text-sm">Logout</Link>
            </div>
            <div v-else class="space-x-4">
                <Link :href="route('login')" class="text-sm font-bold">Login Seller</Link>
            </div>
        </nav>

        <div class="max-w-7xl mx-auto py-12 px-6">
            <div v-if="user" class="mb-12 bg-indigo-600 p-8 rounded-2xl text-white">
                <h2 class="text-2xl font-bold mb-4">Launch a New Store</h2>
                <form @submit.prevent="$inertia.post(route('central.tenant.store'), { store_name: newStore })" class="flex gap-4">
                    <input v-model="newStore" placeholder="Enter store name..." class="flex-1 p-3 rounded-lg text-black">
                    <button class="bg-white text-indigo-600 px-6 py-3 rounded-lg font-black">Create Store</button>
                </form>
            </div>

            <h2 class="text-3xl font-black mb-8">Discover Stores</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div v-for="t in tenants" :key="t.id" class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="text-xl font-bold text-gray-900 uppercase">{{ t.id }}</h3>
                    <p class="text-gray-500 text-sm mb-4">Visit this store to see amazing products.</p>
                    <a :href="'http://' + t.domains[0].domain + ':8000'" class="inline-block bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-bold">Visit Store →</a>
                </div>
            </div>
        </div>
    </div>
</template>
