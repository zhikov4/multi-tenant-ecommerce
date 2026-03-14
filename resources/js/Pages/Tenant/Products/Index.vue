<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { inject } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const route = inject('route');

const props = defineProps({
    products: Object,
    stats: Object,
});

const deleteProduct = (id) => {
    if (confirm('Delete this product?')) {
        router.delete(route('products.destroy', id));
    }
};
</script>

<template>
    <Head title="Products" />
    <AuthenticatedLayout>
        <div class="max-w-6xl mx-auto space-y-8">
            <header class="flex justify-between items-center">
                <div>
                    <h1 class="text-4xl font-black text-slate-900">Products</h1>
                    <p class="text-slate-500 mt-1">Manage your store's product catalog.</p>
                </div>
                <Link :href="route('products.create')" class="bg-blue-600 text-white px-6 py-3 rounded-2xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-200">
                    + Add Product
                </Link>
            </header>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Total Products</p>
                    <p class="text-3xl font-black text-slate-900">{{ stats.total_products }}</p>
                </div>
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Active</p>
                    <p class="text-3xl font-black text-green-600">{{ stats.active_products }}</p>
                </div>
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Total Stock</p>
                    <p class="text-3xl font-black text-blue-600">{{ stats.total_stock }}</p>
                </div>
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Inventory Value</p>
                    <p class="text-3xl font-black text-slate-900">${{ stats.total_value }}</p>
                </div>
            </div>

            <div v-if="$page.props.flash?.success" class="bg-green-50 border border-green-200 text-green-700 font-bold px-5 py-3 rounded-2xl">
                {{ $page.props.flash.success }}
            </div>

            <div v-if="products.data.length === 0" class="text-center py-20 bg-white rounded-3xl border-2 border-dashed border-slate-200">
                <svg class="w-16 h-16 text-slate-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <p class="text-slate-400 font-black uppercase tracking-widest text-sm">No products yet</p>
                <Link :href="route('products.create')" class="mt-4 inline-block text-blue-600 font-bold hover:underline">Add your first product →</Link>
            </div>

            <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="product in products.data" :key="product.id" class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm group">
                    <div class="flex justify-between items-start mb-4">
                        <span :class="product.is_active ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500'" class="text-xs font-bold px-3 py-1 rounded-full">
                            {{ product.is_active ? 'Active' : 'Inactive' }}
                        </span>
                        <button @click="deleteProduct(product.id)" class="text-slate-200 hover:text-red-500 transition-colors opacity-0 group-hover:opacity-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                    <h3 class="font-bold text-xl text-slate-900 mb-1">{{ product.name }}</h3>
                    <p v-if="product.category" class="text-xs text-blue-500 font-bold uppercase tracking-widest mb-2">{{ product.category }}</p>
                    <p class="text-slate-500 text-sm mb-4 line-clamp-2">{{ product.description || 'No description.' }}</p>
                    <div class="flex justify-between items-center mb-4">
                        <p class="text-2xl font-black text-blue-600">${{ Number(product.price).toFixed(2) }}</p>
                        <p class="text-sm text-slate-400 font-bold">{{ product.stock }} in stock</p>
                    </div>
                    <Link :href="route('products.edit', product.id)" class="block w-full text-center bg-slate-50 text-slate-700 py-2.5 rounded-2xl font-bold hover:bg-blue-50 hover:text-blue-700 transition-all border border-slate-200">
                        Edit Product
                    </Link>
                </div>
            </div>

            <div v-if="products.last_page > 1" class="flex justify-center gap-2">
                <Link v-for="link in products.links" :key="link.label"
                    :href="link.url || '#'"
                    v-html="link.label"
                    :class="[
                        'px-4 py-2 rounded-xl text-sm border font-bold',
                        link.active ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-slate-600 border-slate-200 hover:border-blue-300',
                        !link.url ? 'opacity-40 cursor-not-allowed pointer-events-none' : '',
                    ]"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>