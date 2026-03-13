<script setup>
import { useForm, Head, router } from '@inertiajs/vue3';

const props = defineProps({ 
    products: Array 
});

const form = useForm({
    name: '',
    price: '',
    description: '',
});

const submit = () => {
    form.post(route('tenant.products.store'), {
        onSuccess: () => form.reset(),
    });
};

const deleteProduct = (id) => {
    if (confirm('Yakin mau hapus produk ini?')) {
        router.delete(route('tenant.products.destroy', id));
    }
};
</script>

<template>
    <Head title="Seller Dashboard" />
    
    <div class="min-h-screen bg-slate-50 p-6 md:p-12 font-sans">
        <div class="max-w-5xl mx-auto">
            <header class="mb-10 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div>
                    <h1 class="text-4xl font-black text-slate-900 tracking-tight">Seller Dashboard 🏪</h1>
                    <p class="text-slate-500 mt-1">Kelola barang dagangan lu di sini.</p>
                </div>
                <div class="bg-blue-600 text-white px-6 py-2 rounded-2xl shadow-lg shadow-blue-200 font-bold self-start">
                    Tenant ID: {{ $page.props.tenant_id || 'tokoku-1' }}
                </div>
            </header>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 sticky top-8">
                        <h2 class="text-xl font-bold mb-6 text-slate-800">Tambah Produk</h2>
                        <form @submit.prevent="submit" class="space-y-4">
                            <div>
                                <label class="text-sm font-bold text-slate-600 ml-1">Nama Barang</label>
                                <input v-model="form.name" type="text" class="w-full mt-1 border-slate-200 rounded-xl focus:ring-blue-500" required />
                            </div>
                            <div>
                                <label class="text-sm font-bold text-slate-600 ml-1">Harga (Rp)</label>
                                <input v-model="form.price" type="number" class="w-full mt-1 border-slate-200 rounded-xl focus:ring-blue-500" required />
                            </div>
                            <div>
                                <label class="text-sm font-bold text-slate-600 ml-1">Deskripsi</label>
                                <textarea v-model="form.description" rows="3" class="w-full mt-1 border-slate-200 rounded-xl focus:ring-blue-500"></textarea>
                            </div>
                            <button type="submit" :disabled="form.processing" class="w-full bg-slate-900 text-white py-4 rounded-2xl font-black hover:bg-blue-600 transition-all">
                                GAS TAMBAH! 🚀
                            </button>
                        </form>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div v-if="products.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div v-for="product in products" :key="product.id" class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 group relative">
                            <button @click="deleteProduct(product.id)" class="absolute top-4 right-4 text-slate-300 hover:text-red-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>

                            <div class="flex justify-between items-start mb-4">
                                <div class="bg-slate-100 p-3 rounded-2xl group-hover:bg-blue-50 transition-colors">📦</div>
                            </div>
                            <h3 class="font-bold text-xl text-slate-900">{{ product.name }}</h3>
                            <p class="text-blue-600 font-black text-lg mt-1">Rp {{ Number(product.price).toLocaleString('id-ID') }}</p>
                            <p class="text-slate-500 text-sm mt-3 italic">"{{ product.description || 'Gak ada deskripsi.' }}"</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>