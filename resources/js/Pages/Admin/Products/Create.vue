<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    description: '',
    price: '',
    stock: ''
});

const submit = () => {
    form.post(route('admin.products.store'));
};
</script>

<template>
    <Head title="Add Product" />
    <AuthenticatedLayout>
        <div class="max-w-3xl mx-auto py-12 px-4">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Add New Product</h1>
            
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8">
                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Product Name</label>
                        <input v-model="form.name" type="text" class="w-full border-gray-200 rounded-xl focus:ring-black focus:border-black" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea v-model="form.description" rows="4" class="w-full border-gray-200 rounded-xl focus:ring-black focus:border-black"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Price ($)</label>
                            <input v-model="form.price" type="number" step="0.01" class="w-full border-gray-200 rounded-xl focus:ring-black focus:border-black" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity</label>
                            <input v-model="form.stock" type="number" class="w-full border-gray-200 rounded-xl focus:ring-black focus:border-black" required>
                        </div>
                    </div>
                    <div class="pt-4 flex justify-end space-x-4">
                        <button type="button" @click="() => window.history.back()" class="px-6 py-2 border border-gray-200 rounded-xl text-gray-600 hover:bg-gray-50 font-medium">Cancel</button>
                        <button type="submit" :disabled="form.processing" class="bg-black text-white px-8 py-2 rounded-xl font-medium hover:bg-gray-800 disabled:opacity-50">
                            Save Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>