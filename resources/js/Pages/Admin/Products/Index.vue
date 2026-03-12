<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({ products: Array });

const form = useForm({});

const deleteProduct = (id) => {
    if (confirm('Are you sure you want to delete this product?')) {
        form.delete(route('admin.products.destroy', id));
    }
};
</script>

<template>
    <Head title="Manage Products" />
    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Manage Products</h1>
                <Link :href="route('admin.products.create')" class="bg-black text-white px-6 py-2 rounded-xl font-medium hover:bg-gray-800 transition-colors">
                    + Add New Product
                </Link>
            </div>

            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50/50 border-b border-gray-100">
                        <tr>
                            <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Product Name</th>
                            <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Stock</th>
                            <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="product in products" :key="product.id" class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-4 px-6 font-medium text-gray-900">{{ product.name }}</td>
                            <td class="py-4 px-6 text-gray-600">${{ product.price }}</td>
                            <td class="py-4 px-6 text-gray-600">{{ product.stock }}</td>
                            <td class="py-4 px-6 text-right space-x-4">
                                <Link :href="route('admin.products.edit', product.id)" class="text-indigo-600 font-medium hover:text-indigo-900">Edit</Link>
                                <button @click="deleteProduct(product.id)" class="text-red-500 font-medium hover:text-red-700">Delete</button>
                            </td>
                        </tr>
                        <tr v-if="products.length === 0">
                            <td colspan="4" class="py-8 text-center text-gray-400">No products found. Add your first product.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>