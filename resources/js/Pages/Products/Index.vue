<template>
    <div class="flex min-h-screen bg-gray-50 font-sans">
        <aside class="w-64 bg-white border-r shadow-sm flex flex-col fixed h-full z-50">
            <div class="p-6 font-black text-indigo-600 text-2xl border-b italic">
                <a href="http://localhost:8000/">MYSTORE</a>
            </div>
            <nav class="flex-1 mt-4">
                <a href="http://localhost:8000/dashboard" class="block px-6 py-4 text-gray-500 font-bold hover:bg-indigo-50">DASHBOARD</a>
                <a href="/products" class="block px-6 py-4 text-indigo-600 bg-indigo-50 font-black border-r-4 border-indigo-600">MARKETPLACE</a>
                <a href="http://localhost:8000/profile" class="block px-6 py-4 text-gray-500 font-bold hover:bg-indigo-50">MY PROFILE</a>
                <a href="http://localhost:8000/settings" class="block px-6 py-4 text-gray-500 font-bold hover:bg-indigo-50">SETTINGS</a>
            </nav>
            <div class="p-6">
                <button @click="handleLogout" class="w-full py-4 text-center bg-red-500 text-white rounded-xl font-black uppercase text-xs tracking-widest shadow-lg hover:bg-red-600 transition">
                    Log Out
                </button>
            </div>
        </aside>

        <main class="flex-1 ml-64 p-12">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-3xl font-black text-gray-800 tracking-tight mb-10 uppercase text-center">Inventory Management</h1>

                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 mb-12">
                    <form @submit.prevent="submit" class="flex flex-wrap gap-4 justify-center items-end">
                        <div class="text-left">
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Product Name</label>
                            <input v-model="form.name" type="text" class="w-full bg-gray-50 border-none rounded-xl p-3 focus:ring-2 focus:ring-indigo-500 font-bold">
                        </div>
                        <div class="w-32 text-left">
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Price</label>
                            <input v-model="form.price" type="number" class="w-full bg-gray-50 border-none rounded-xl p-3 focus:ring-2 focus:ring-indigo-500 font-bold">
                        </div>
                        <div class="w-24 text-left">
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Stock</label>
                            <input v-model="form.stock" type="number" class="w-full bg-gray-50 border-none rounded-xl p-3 focus:ring-2 focus:ring-indigo-500 font-bold">
                        </div>
                        <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-black hover:bg-indigo-700 shadow-xl transition">Add Product</button>
                    </form>
                </div>

                <div class="bg-white rounded-3xl shadow-sm border overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Name</th>
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Price</th>
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Stock</th>
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <tr v-for="product in products" :key="product.id" class="hover:bg-indigo-50/30 transition">
                                <td class="px-8 py-6 font-bold text-gray-700 italic">{{ product.name }}</td>
                                <td class="px-8 py-6 font-black text-indigo-600">Rp {{ Number(product.price).toLocaleString('id-ID') }}</td>
                                <td class="px-8 py-6 text-center font-bold text-gray-500">{{ product.stock }}</td>
                                <td class="px-8 py-6 text-right">
                                    <button @click="deleteProduct(product.id)" class="text-red-400 hover:text-red-600 font-black text-[10px] uppercase tracking-widest">Delete</button>
                                </td>
                            </tr>
                            <tr v-if="products.length === 0">
                                <td colspan="4" class="px-8 py-12 text-center text-gray-400 font-bold">Belum ada produk. Tambahkan produk pertamamu! 🛍️</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';

defineProps({ products: Array });

const form = useForm({ name: '', price: 0, stock: 0 });

const submit = () => {
    form.post('/products', { onSuccess: () => form.reset() });
};

const deleteProduct = (id) => {
    if (confirm('Yakin mau hapus produk ini?')) {
        form.delete('/products/' + id);
    }
};

const handleLogout = async () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    try {
        await fetch('/logout', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            credentials: 'include',
        });
    } catch (e) {}
    window.location.href = 'http://localhost:8000/';
};
</script>
