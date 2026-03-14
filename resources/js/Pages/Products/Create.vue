<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { inject } from 'vue';

const route = inject('route');

const form = useForm({
    name: '',
    description: '',
    price: '',
    stock: '',
    category: 'Electronics',
    sku: '',
    is_active: true,
});

const submit = () => {
    form.post(route('products.store'), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Add Product" />

    <AuthenticatedLayout>
        <div class="max-w-4xl mx-auto pb-20">
            <div class="mb-8">
                <Link :href="route('products.index')" class="text-slate-400 hover:text-blue-600 font-bold text-xs uppercase tracking-widest flex items-center gap-2 mb-4 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Products
                </Link>
                <h1 class="text-4xl font-black text-slate-900 tracking-tighter uppercase italic">Add Product</h1>
            </div>

            <div class="bg-white rounded-[3rem] border border-slate-100 shadow-xl shadow-slate-200/50 p-8 md:p-12">
                <form @submit.prevent="submit" class="space-y-8">
                    
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Product Name *</label>
                        <input v-model="form.name" type="text" placeholder="e.g. MacBook Pro M3"
                            class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700" />
                        <p v-if="form.errors.name" class="mt-2 text-xs text-red-500 font-bold ml-2 italic">{{ form.errors.name }}</p>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Description</label>
                        <textarea v-model="form.description" rows="4" placeholder="Tell us more about this product..."
                            class="w-full bg-slate-50 border-none rounded-[2rem] px-6 py-4 focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Price (USD) *</label>
                            <div class="relative">
                                <span class="absolute left-6 top-1/2 -translate-y-1/2 text-slate-400 font-bold">$</span>
                                <input v-model="form.price" type="number" step="0.01"
                                    class="w-full bg-slate-50 border-none rounded-2xl pl-10 pr-6 py-4 focus:ring-2 focus:ring-blue-500 transition-all font-bold text-slate-700" />
                            </div>
                            <p v-if="form.errors.price" class="mt-2 text-xs text-red-500 font-bold ml-2 italic">{{ form.errors.price }}</p>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Stock *</label>
                            <input v-model="form.stock" type="number"
                                class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 focus:ring-2 focus:ring-blue-500 transition-all font-bold text-slate-700" />
                            <p v-if="form.errors.stock" class="mt-2 text-xs text-red-500 font-bold ml-2 italic">{{ form.errors.stock }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Category *</label>
                            <select v-model="form.category" 
                                class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 focus:ring-2 focus:ring-blue-500 transition-all font-bold text-slate-700 appearance-none">
                                <option>Electronics</option>
                                <option>Fashion</option>
                                <option>Home & Living</option>
                                <option>Others</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">SKU</label>
                            <input v-model="form.sku" type="text" placeholder="PROD-001"
                                class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 focus:ring-2 focus:ring-blue-500 transition-all font-bold text-slate-700 uppercase" />
                            <p v-if="form.errors.sku" class="mt-2 text-xs text-red-500 font-bold ml-2 italic">{{ form.errors.sku }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <input v-model="form.is_active" type="checkbox" id="is_active"
                            class="w-6 h-6 rounded-lg text-blue-600 border-slate-300 focus:ring-blue-500 transition-all cursor-pointer" />
                        <label for="is_active" class="text-xs font-black text-slate-600 uppercase tracking-widest cursor-pointer select-none">
                            Active (Visible to customers)
                        </label>
                    </div>

                    <div class="flex flex-col md:flex-row gap-4 pt-4">
                        <Link :href="route('products.index')" 
                            class="flex-1 bg-slate-100 text-slate-500 py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] text-center hover:bg-slate-200 transition-all">
                            Cancel
                        </Link>
                        <button type="submit" :disabled="form.processing"
                            class="flex-[2] bg-blue-600 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-lg shadow-blue-200 hover:bg-blue-700 hover:shadow-blue-300 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                            {{ form.processing ? 'Saving...' : 'Save Product' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>