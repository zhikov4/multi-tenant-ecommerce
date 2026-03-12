<script setup>
import { Link, Head } from '@inertiajs/vue3';
import { ref } from 'vue';
defineProps({ tenants: Array, auth: Object });
const newStore = ref('');
</script>
<template>
    <Head title="E-Commerce Platform" />
    <div class="min-h-screen bg-gray-50 text-black">
        <nav class="bg-white border-b p-6 flex justify-between items-center shadow-sm">
            <div class="text-2xl font-black text-indigo-600">MyStorePlatform.</div>
            <div class="flex gap-4">
                <template v-if="auth.user">
                    <span class="font-bold">Hi, {{ auth.user.name }}</span>
                    <Link :href="route('logout')" method="post" as="button" class="text-red-500 font-bold">Logout</Link>
                </template>
                <Link v-else :href="route('login')" class="font-bold border-b-2 border-black">Seller Login</Link>
            </div>
        </nav>

        <div class="max-w-7xl mx-auto py-16 px-6">
            <div v-if="auth.user" class="mb-20 bg-indigo-600 rounded-3xl p-10 text-white shadow-xl">
                <h2 class="text-3xl font-black mb-2">Grow your business.</h2>
                <p class="mb-8 text-indigo-100">You can create multiple stores under one account.</p>
                <form @submit.prevent="$inertia.post(route('central.tenant.store'), { store_name: newStore })" class="flex gap-4 max-w-2xl">
                    <input v-model="newStore" placeholder="Your store name..." class="flex-1 p-4 rounded-xl text-black font-bold">
                    <button class="bg-white text-indigo-600 px-8 py-4 rounded-xl font-black hover:bg-gray-100 transition">Create Store</button>
                </form>
            </div>

            <h2 class="text-4xl font-black mb-10 tracking-tight">Discover Amazing Stores</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div v-for="t in tenants" :key="t.id" class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg mb-6 flex items-center justify-center text-indigo-600 font-bold text-xl">
                        {{ t.id.charAt(0).toUpperCase() }}
                    </div>
                    <h3 class="text-2xl font-bold mb-2 uppercase">{{ t.id }}</h3>
                    <p class="text-gray-500 mb-6">Explore products and shop from this unique store.</p>
                    <a :href="'http://' + t.domains[0].domain + ':8000'" class="block text-center bg-black text-white py-3 rounded-xl font-bold">Visit Store →</a>
                </div>
            </div>
        </div>
    </div>
</template>
