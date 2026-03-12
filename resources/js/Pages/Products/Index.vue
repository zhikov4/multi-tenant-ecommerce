<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { useForm, Link, Head } from "@inertiajs/vue3";
defineProps({ products: Array });
const form = useForm({ name: "", price: "", stock: 0 });
const submit = () => form.post(route("products.store"), { 
    onSuccess: () => form.reset(),
    preserveScroll: true 
});
</script>
<template>
    <Head title="Manage Products" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 text-black">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                    <h1 class="text-2xl font-bold mb-6">Store Inventory Management</h1>
                    
                    <form @submit.prevent="submit" class="mb-10 p-6 bg-gray-50 rounded-xl border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium mb-1">Product Name</label>
                                <input v-model="form.name" type="text" placeholder="e.g. iPhone 15" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Price (USD)</label>
                                <input v-model="form.price" type="number" placeholder="0.00" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Available Stock</label>
                                <input v-model="form.stock" type="number" placeholder="10" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500" required>
                            </div>
                        </div>
                        <button type="submit" :disabled="form.processing" class="mt-4 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-bold transition">
                            {{ form.processing ? 'Saving...' : 'Confirm & Add Product' }}
                        </button>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="border-b-2 border-gray-100">
                                <tr class="text-gray-500 uppercase text-xs font-bold">
                                    <th class="pb-3">Product Information</th>
                                    <th class="pb-3 text-right">Price</th>
                                    <th class="pb-3 text-center">In Stock</th>
                                    <th class="pb-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr v-for="p in products" :key="p.id" class="hover:bg-gray-50 transition">
                                    <td class="py-4 font-semibold">{{ p.name }}</td>
                                    <td class="py-4 text-right text-green-600 font-mono">${{ p.price }}</td>
                                    <td class="py-4 text-center">
                                        <span class="bg-gray-100 px-3 py-1 rounded-full text-sm">{{ p.stock }} units</span>
                                    </td>
                                    <td class="py-4 text-right">
                                        <Link :href="route('products.destroy', p.id)" method="delete" as="button" class="text-red-500 hover:underline">Remove</Link>
                                    </td>
                                </tr>
                                <tr v-if="products.length === 0">
                                    <td colspan="4" class="py-10 text-center text-gray-400">The catalog is empty.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
