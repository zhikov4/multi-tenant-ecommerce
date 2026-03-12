<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { useForm, Link, Head } from "@inertiajs/vue3";
defineProps({ products: Array });
const form = useForm({ name: "", price: "", stock: 0 });
const submit = () => form.post(route("products.store"), { onSuccess: () => form.reset() });
</script>
<template>
    <Head title="Products" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h1 class="text-2xl font-bold mb-6 text-gray-800">MyStoreTest - Product Management</h1>
                    
                    <form @submit.prevent="submit" class="mb-10 bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <input v-model="form.name" placeholder="Product Name" class="border p-2 rounded w-full text-black" required>
                            <input v-model="form.price" type="number" placeholder="Price" class="border p-2 rounded w-full text-black" required>
                            <input v-model="form.stock" type="number" placeholder="Stock" class="border p-2 rounded w-full text-black" required>
                        </div>
                        <button type="submit" :disabled="form.processing" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md transition font-semibold">
                            + Add New Product
                        </button>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-100 text-gray-700">
                                    <th class="border-b p-3">Name</th>
                                    <th class="border-b p-3">Price</th>
                                    <th class="border-b p-3">Stock</th>
                                    <th class="border-b p-3">Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600">
                                <tr v-for="p in products" :key="p.id" class="hover:bg-gray-50">
                                    <td class="border-b p-3 font-medium">{{ p.name }}</td>
                                    <td class="border-b p-3">${{ p.price }}</td>
                                    <td class="border-b p-3">{{ p.stock }}</td>
                                    <td class="border-b p-3">
                                        <Link :href="route('products.destroy', p.id)" method="delete" as="button" class="text-red-500 hover:text-red-700 font-medium">Delete</Link>
                                    </td>
                                </tr>
                                <tr v-if="products.length === 0">
                                    <td colspan="4" class="p-6 text-center text-gray-400 italic">No products found. Start by adding one above!</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
