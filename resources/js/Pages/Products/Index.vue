<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { inject } from 'vue';

const route = inject('route');

// Kita kasih default value supaya kalau datanya belum datang, aplikasi nggak crash
const props = defineProps({ 
    products: {
        type: Object,
        default: () => ({ data: [], links: [], last_page: 0 })
    }
});

const formatUSD = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val);

const deleteProduct = (id) => {
    if (confirm('Are you sure you want to delete this product?')) {
        router.delete(route('products.destroy', id));
    }
};
</script>

<template>
    <Head title="Products" />
    <AuthenticatedLayout>
        <div class="max-w-6xl mx-auto space-y-8 pb-20">

            <header class="flex items-end justify-between">
                <div>
                    <h1 class="text-4xl font-black text-slate-900 tracking-tighter uppercase italic">Products</h1>
                    <p class="text-slate-500 mt-2 font-medium">Manage your store's product catalog.</p>
                </div>
                <Link :href="route('products.create')"
                    class="bg-blue-600 text-white px-8 py-3.5 rounded-2xl font-black shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all uppercase text-xs tracking-widest">
                    + Add Product
                </Link>
            </header>

            <div v-if="$page.props.flash?.message"
                class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl font-bold text-sm">
                ✅ {{ $page.props.flash.message }}
            </div>

            <div v-if="(products?.data || []).length === 0"
                class="text-center py-24 bg-white rounded-[3rem] border-2 border-dashed border-slate-200">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                <p class="text-slate-400 font-black uppercase tracking-widest text-sm">No products yet</p>
                <p class="text-slate-300 font-medium text-sm mt-2">Add your first product to get started.</p>
            </div>

            <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="product in (products?.data || [])" :key="product.id"
                    class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-lg hover:border-blue-200 transition-all overflow-hidden group">
                    <div class="aspect-video bg-slate-50 flex items-center justify-center border-b border-slate-100">
                        <svg class="w-16 h-16 text-slate-300 group-hover:text-blue-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <div class="p-6">
                        <div class="flex items-start justify-between gap-2 mb-3">
                            <div class="min-w-0">
                                <p class="text-[10px] font-black text-blue-500 uppercase tracking-widest mb-1">{{ product.category }}</p>
                                <h3 class="font-black text-slate-900 text-lg leading-tight truncate">{{ product.name }}</h3>
                            </div>
                            <span :class="product.is_active ? 'bg-green-50 text-green-600 border-green-100' : 'bg-slate-100 text-slate-400 border-slate-200'"
                                class="flex-shrink-0 text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded-lg border">
                                {{ product.is_active ? 'Active' : 'Draft' }}
                            </span>
                        </div>
                        <p class="text-slate-400 text-sm font-medium line-clamp-2 mb-4 min-h-[2.5rem]">
                            {{ product.description || 'No description provided.' }}
                        </p>
                        <div class="flex justify-between items-center mb-5">
                            <p class="text-2xl font-black text-blue-600">{{ formatUSD(product.price) }}</p>
                            <p class="text-xs font-black text-slate-400 uppercase">Stock: <span class="text-slate-700">{{ product.stock }}</span></p>
                        </div>
                        <div class="flex gap-2">
                            <Link :href="route('products.edit', product.id)"
                                class="flex-1 bg-slate-50 text-slate-700 py-3 rounded-2xl font-black text-xs uppercase tracking-widest text-center hover:bg-slate-900 hover:text-white transition-all border border-slate-200">
                                Edit
                            </Link>
                            <button @click="deleteProduct(product.id)"
                                class="flex-1 bg-red-50 text-red-500 py-3 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-red-500 hover:text-white transition-all border border-red-100">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="(products?.last_page || 0) > 1" class="flex justify-center gap-2 flex-wrap">
                <Link v-for="link in (products?.links || [])" :key="link.label"
                    :href="link.url || '#'"
                    :class="[
                        'px-4 py-2 rounded-xl font-black text-xs uppercase tracking-widest transition-all',
                        link.active ? 'bg-blue-600 text-white' : 'bg-white text-slate-500 border border-slate-200 hover:border-blue-300',
                        !link.url ? 'opacity-40 pointer-events-none' : ''
                    ]"
                    v-html="link.label"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>