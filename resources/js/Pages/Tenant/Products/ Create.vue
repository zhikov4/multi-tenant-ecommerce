<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    price: '',
    description: '',
});

const submit = () => {
    form.post(route('tenant.products.store'), {
        onSuccess: () => {
            form.reset();
            alert('Product successfully added to your store!');
        },
    });
};
</script>

<template>
    <Head title="Add New Product" />

    <AuthenticatedLayout>
        <div class="max-w-2xl mx-auto">
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight text-blue-600 flex items-center gap-2">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        Add Product
                    </h2>
                    <p class="text-gray-500 font-medium mt-1">Fill in the details of the item you want to sell.</p>
                </div>
                
                <Link :href="route('tenant.products.index')" class="bg-white border border-gray-200 text-gray-600 px-5 py-2 rounded-xl font-bold hover:bg-gray-50 transition-all flex items-center gap-2 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back
                </Link>
            </div>

            <form @submit.prevent="submit" class="bg-white p-8 rounded-[2.5rem] shadow-xl shadow-blue-50 border border-gray-100 space-y-6">
                <div>
                    <label class="text-xs font-black text-gray-400 uppercase ml-1 tracking-widest">Product Name</label>
                    <input v-model="form.name" type="text" placeholder="e.g. Limited Edition Sneakers" 
                        class="w-full mt-1 bg-gray-50 border-gray-200 rounded-2xl py-4 px-5 font-bold focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all outline-none" required />
                    <p v-if="form.errors.name" class="text-red-500 text-xs mt-2 ml-1 font-bold">{{ form.errors.name }}</p>
                </div>

                <div>
                    <label class="text-xs font-black text-gray-400 uppercase ml-1 tracking-widest">Selling Price (Rp)</label>
                    <input v-model="form.price" type="number" placeholder="e.g. 150000" 
                        class="w-full mt-1 bg-gray-50 border-gray-200 rounded-2xl py-4 px-5 font-bold focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all outline-none" required />
                    <p v-if="form.errors.price" class="text-red-500 text-xs mt-2 ml-1 font-bold">{{ form.errors.price }}</p>
                </div>

                <div>
                    <label class="text-xs font-black text-gray-400 uppercase ml-1 tracking-widest">Short Description</label>
                    <textarea v-model="form.description" rows="4" placeholder="Highlight the key features of your product..." 
                        class="w-full mt-1 bg-gray-50 border-gray-200 rounded-2xl py-4 px-5 font-bold focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all outline-none"></textarea>
                    <p v-if="form.errors.description" class="text-red-500 text-xs mt-2 ml-1 font-bold">{{ form.errors.description }}</p>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                    <Link :href="route('tenant.products.index')" 
                        class="flex-1 bg-gray-100 text-gray-600 py-4 rounded-2xl font-black text-center hover:bg-gray-200 transition-all">
                        Cancel
                    </Link>

                    <button type="submit" :disabled="form.processing" 
                        :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                        class="flex-1 bg-blue-600 text-white py-4 rounded-2xl font-black shadow-lg shadow-blue-200 hover:bg-blue-700 hover:-translate-y-1 transition-all flex justify-center items-center gap-2">
                        <svg v-if="!form.processing" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        {{ form.processing ? 'Processing...' : 'Submit Product' }}
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>