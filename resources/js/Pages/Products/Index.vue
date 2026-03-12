<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({ products: Array });

const form = useForm({ product_id: null, quantity: 1 });

const addToCart = (id) => {
    form.product_id = id;
    form.post(route('cart.store'), { preserveScroll: true });
};
</script>

<template>
    <Head title="Premium Collection" />
    <AuthenticatedLayout>
        <div class="py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
            <header class="mb-12">
                <h1 class="text-4xl font-extrabold text-black tracking-tight">Our Collection</h1>
                <p class="mt-2 text-gray-500">Experience the future of technology today.</p>
            </header>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <div v-for="product in products" :key="product.id" 
                    class="group relative bg-white border border-gray-100 rounded-3xl p-8 hover:shadow-2xl hover:shadow-gray-200 transition-all duration-500">
                    <div class="mb-6 h-48 bg-gray-50 rounded-2xl flex items-center justify-center group-hover:scale-105 transition-transform duration-500">
                        <span class="text-gray-300 font-bold italic">Image Preview</span>
                    </div>
                    
                    <h2 class="text-xl font-bold text-black mb-1">{{ product.name }}</h2>
                    <p class="text-sm text-gray-400 mb-6 line-clamp-2">{{ product.description }}</p>
                    
                    <div class="flex items-center justify-between mt-auto">
                        <span class="text-2xl font-black text-black">${{ product.price }}</span>
                        <button @click="addToCart(product.id)" 
                            class="bg-black text-white px-6 py-3 rounded-2xl text-sm font-bold hover:bg-indigo-600 transition-colors">
                            Add to Bag
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>