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
    <Head title="Products" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 text-black">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                    <h1 class="text-2xl font-bold mb-6">Inventory Management</h1>
                    <form @submit.prevent="submit" class="mb-10 p-6 bg-gray-50 rounded-xl border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-bold mb-1">Product Name</label>
                                <input v-model="form.name" type="text" class="w-full border-gray-300 rounded-md text-black" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold mb-1">Price</label>
                                <input v-model="form.price" type="number" class="w-full border-gray-300 rounded-md text-black" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold mb-1">Stock</label>
                                <input v-model="form.stock" type="number" class="w-full border-gray-300 rounded-md text-black" required>
                            </div>
                        </div>
                        <button type="submit" class="mt-4 bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold">Add Product</button>
                    </form>
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="p-3 border">Name</th>
                                <th class="p-3 border">Price</th>
                                <th class="p-3 border">Stock</th>
                                <th class="p-3 border">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="p in products" :key="p.id">
                                <td class="p-3 border">{{ p.name }}</td>
                                <td class="p-3 border">${{ p.price }}</td>
                                <td class="p-3 border">{{ p.stock }}</td>
                                <td class="p-3 border">
                                    <Link :href="route('products.destroy', p.id)" method="delete" as="button" class="text-red-600">Delete</Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
