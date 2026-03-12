<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { useForm, Link } from "@inertiajs/vue3";
defineProps({ products: Array });
const form = useForm({ name: "", price: "", stock: 0 });
const submit = () => form.post(route("products.store"), { onSuccess: () => form.reset() });
</script>
<template>
    <AuthenticatedLayout>
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-4 text-black">MyStoreTest - Products</h1>
            <form @submit.prevent="submit" class="mb-8 space-y-2">
                <input v-model="form.name" placeholder="Name" class="border p-2 rounded w-full text-black">
                <input v-model="form.price" type="number" placeholder="Price" class="border p-2 rounded w-full text-black">
                <input v-model="form.stock" type="number" placeholder="Stock" class="border p-2 rounded w-full text-black">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Add Product</button>
            </form>
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100 text-black">
                        <th class="border p-2">Name</th>
                        <th class="border p-2">Price</th>
                        <th class="border p-2">Stock</th>
                        <th class="border p-2">Action</th>
                    </tr>
                </thead>
                <tbody class="text-black">
                    <tr v-v-for="p in products" :key="p.id">
                        <td class="border p-2">{{ p.name }}</td>
                        <td class="border p-2">{{ p.price }}</td>
                        <td class="border p-2">{{ p.stock }}</td>
                        <td class="border p-2">
                            <Link :href="route('products.destroy', p.id)" method="delete" as="button" class="text-red-600">Delete</Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AuthenticatedLayout>
</template>
