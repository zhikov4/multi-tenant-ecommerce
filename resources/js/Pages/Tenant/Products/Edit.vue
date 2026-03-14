<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { inject } from 'vue';

const route = inject('route');

const props = defineProps({
    product: Object,
});

const form = useForm({
    name: props.product.name,
    description: props.product.description ?? '',
    price: props.product.price,
    stock: props.product.stock,
    category: props.product.category ?? '',
    sku: props.product.sku ?? '',
    is_active: props.product.is_active,
});

const submit = () => form.put(route('products.update', props.product.id));
</script>

<template>
    <Head title="Edit Product" />
    <div class="min-h-screen bg-slate-50 p-8 font-sans">
        <div class="max-w-2xl mx-auto">
            <div class="flex items-center gap-4 mb-8">
                <Link :href="route('products.index')" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </Link>
                <h1 class="text-3xl font-black text-slate-900">Edit Product</h1>
            </div>

            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Product Name *</label>
                        <input v-model="form.name" type="text" class="w-full border border-slate-200 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 font-medium" />
                        <p v-if="form.errors.name" class="text-red-500 text-xs mt-1 font-bold">{{ form.errors.name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Description</label>
                        <textarea v-model="form.description" rows="3" class="w-full border border-slate-200 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 font-medium" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Price (USD) *</label>
                            <input v-model="form.price" type="number" step="0.01" min="0" class="w-full border border-slate-200 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 font-medium" />
                            <p v-if="form.errors.price" class="text-red-500 text-xs mt-1 font-bold">{{ form.errors.price }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Stock *</label>
                            <input v-model="form.stock" type="number" min="0" class="w-full border border-slate-200 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 font-medium" />
                            <p v-if="form.errors.stock" class="text-red-500 text-xs mt-1 font-bold">{{ form.errors.stock }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Category</label>
                            <input v-model="form.category" type="text" class="w-full border border-slate-200 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 font-medium" />
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">SKU</label>
                            <input v-model="form.sku" type="text" class="w-full border border-slate-200 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 font-medium" />
                            <p v-if="form.errors.sku" class="text-red-500 text-xs mt-1 font-bold">{{ form.errors.sku }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <input v-model="form.is_active" type="checkbox" id="is_active" class="w-5 h-5 text-blue-600 rounded-lg" />
                        <label for="is_active" class="text-sm font-bold text-slate-700">Active (visible to customers)</label>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button @click="submit" :disabled="form.processing"
                            class="flex-1 bg-blue-600 text-white py-3 rounded-2xl font-black hover:bg-blue-700 transition-all disabled:opacity-50">
                            {{ form.processing ? 'Updating...' : 'Update Product' }}
                        </button>
                        <Link :href="route('products.index')" class="flex-1 text-center bg-slate-100 text-slate-700 py-3 rounded-2xl font-bold hover:bg-slate-200 transition-all">
                            Cancel
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>