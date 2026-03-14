<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { inject } from 'vue';

const route = inject('route');
const props = defineProps({ product: Object });

const form = useForm({
    name: props.product.name,
    description: props.product.description || '',
    price: props.product.price,
    stock: props.product.stock,
    category: props.product.category,
    sku: props.product.sku || '',
    is_active: props.product.is_active,
});

const submit = () => form.put(route('products.update', props.product.id));
</script>

<template>
    <Head title="Edit Product" />
    <AuthenticatedLayout>
        <div class="max-w-2xl mx-auto pb-20">
            <header class="mb-10">
                <Link :href="route('products.index')"
                    class="text-xs font-black text-slate-400 uppercase tracking-widest hover:text-blue-600 flex items-center gap-2 mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Products
                </Link>
                <h1 class="text-4xl font-black text-slate-900 tracking-tighter uppercase italic">Edit Product</h1>
                <p class="text-slate-500 mt-2 font-medium">Update the details for <span class="font-black text-slate-700">{{ product.name }}</span></p>
            </header>

            <form @submit.prevent="submit" class="bg-white rounded-[3rem] p-10 shadow-sm border border-slate-100 space-y-6">
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Product Name *</label>
                    <input v-model="form.name" type="text"
                        class="w-full mt-2 bg-slate-50 border border-slate-200 rounded-2xl py-4 px-6 font-bold outline-none focus:ring-2 focus:ring-blue-100" />
                    <p v-if="form.errors.name" class="text-red-500 text-xs font-black mt-1 ml-1">{{ form.errors.name }}</p>
                </div>

                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Description</label>
                    <textarea v-model="form.description" rows="3"
                        class="w-full mt-2 bg-slate-50 border border-slate-200 rounded-2xl py-4 px-6 font-bold outline-none focus:ring-2 focus:ring-blue-100"></textarea>
                    <p v-if="form.errors.description" class="text-red-500 text-xs font-black mt-1 ml-1">{{ form.errors.description }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Price (USD) *</label>
                        <div class="relative mt-2">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 font-black text-slate-400">$</span>
                            <input v-model="form.price" type="number" step="0.01" min="0"
                                class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-4 pl-9 pr-6 font-black outline-none focus:ring-2 focus:ring-blue-100" />
                        </div>
                        <p v-if="form.errors.price" class="text-red-500 text-xs font-black mt-1 ml-1">{{ form.errors.price }}</p>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Stock *</label>
                        <input v-model="form.stock" type="number" min="0"
                            class="w-full mt-2 bg-slate-50 border border-slate-200 rounded-2xl py-4 px-6 font-black outline-none focus:ring-2 focus:ring-blue-100" />
                        <p v-if="form.errors.stock" class="text-red-500 text-xs font-black mt-1 ml-1">{{ form.errors.stock }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Category *</label>
                        <select v-model="form.category"
                            class="w-full mt-2 bg-slate-50 border border-slate-200 rounded-2xl py-4 px-6 font-black outline-none focus:ring-2 focus:ring-blue-100">
                            <option>General</option>
                            <option>Electronics</option>
                            <option>Fashion & Apparel</option>
                            <option>Home Living</option>
                            <option>Food & Beverages</option>
                            <option>Sports</option>
                            <option>Books</option>
                        </select>
                        <p v-if="form.errors.category" class="text-red-500 text-xs font-black mt-1 ml-1">{{ form.errors.category }}</p>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">SKU</label>
                        <input v-model="form.sku" type="text"
                            class="w-full mt-2 bg-slate-50 border border-slate-200 rounded-2xl py-4 px-6 font-bold outline-none focus:ring-2 focus:ring-blue-100" />
                        <p v-if="form.errors.sku" class="text-red-500 text-xs font-black mt-1 ml-1">{{ form.errors.sku }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 bg-slate-50 border border-slate-200 rounded-2xl px-6 py-4">
                    <input type="checkbox" v-model="form.is_active" id="is_active"
                        class="w-5 h-5 text-blue-600 rounded-lg cursor-pointer" />
                    <label for="is_active" class="font-black text-slate-700 text-sm uppercase tracking-widest cursor-pointer">
                        Active (visible to customers)
                    </label>
                </div>

                <div class="flex gap-4 pt-4">
                    <Link :href="route('products.index')"
                        class="flex-1 bg-slate-100 text-slate-600 py-4 rounded-2xl font-black uppercase text-xs tracking-widest text-center hover:bg-slate-200 transition-all">
                        Cancel
                    </Link>
                    <button type="submit" :disabled="form.processing"
                        class="flex-1 bg-blue-600 text-white py-4 rounded-2xl font-black uppercase text-xs tracking-widest hover:bg-blue-700 transition-all flex items-center justify-center gap-2 shadow-lg shadow-blue-100">
                        <svg v-if="form.processing" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        {{ form.processing ? 'Updating...' : 'Update Product' }}
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>