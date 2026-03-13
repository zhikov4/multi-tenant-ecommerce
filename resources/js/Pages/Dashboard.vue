<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    status: Object,
    discoveryProducts: Array
});

const isModalOpen = ref(false);

const form = useForm({
    store_name: '',
});

const createStore = () => {
    form.post(route('central.store.create'), {
        onSuccess: () => {
            isModalOpen.value = false;
            form.reset();
        },
    });
};

// Dummy data tetap ada buat Discovery Page
const dummyProducts = Array.from({ length: 8 }, (_, i) => ({
    id: i + 1,
    name: `Produk Unggulan #${i + 1}`,
    price: (Math.floor(Math.random() * 100) + 1) * 10000,
    store: 'Toko Contoh',
    image: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&q=80&w=400'
}));
</script>

<template>
    <Head title="Marketplace Discovery" />

    <AuthenticatedLayout>
        <div class="space-y-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">Discovery Page 🌍</h2>
                    <p class="text-gray-500 font-medium">Satu akun untuk belanja dan jualan.</p>
                </div>

                <div v-if="!status?.hasStore">
                    <button @click="isModalOpen = true" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-2xl font-bold shadow-lg shadow-blue-100 transition-all">
                        🚀 Buka Toko Sekarang
                    </button>
                </div>
                <div v-else>
                    <a :href="status.storeUrl" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-2xl font-bold shadow-lg shadow-green-100 transition-all inline-block">
                        🏪 Masuk ke Toko Lu
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div v-for="product in dummyProducts" :key="product.id" class="bg-white rounded-3xl overflow-hidden border border-gray-100 group">
                    <div class="aspect-square bg-gray-100 relative overflow-hidden">
                        <img :src="product.image" class="object-cover w-full h-full group-hover:scale-105 transition-transform" alt="img" />
                    </div>
                    <div class="p-4">
                        <h4 class="font-bold text-gray-900">{{ product.name }}</h4>
                        <p class="text-blue-600 font-black mt-1">Rp {{ product.price.toLocaleString('id-ID') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white w-full max-w-md rounded-[2.5rem] p-8 shadow-2xl animate-in fade-in zoom-in duration-300">
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-black text-gray-900">Bikin Toko Lu Sendiri! 🎨</h3>
                    <p class="text-gray-500 text-sm mt-2">Pilih nama domain yang unik buat toko lu.</p>
                </div>

                <form @submit.prevent="createStore" class="space-y-5">
                    <div>
                        <label class="text-xs font-black text-gray-400 uppercase ml-1">Nama Subdomain</label>
                        <div class="relative mt-1">
                            <input v-model="form.store_name" type="text" placeholder="misal: kopi-senja" 
                                class="w-full bg-gray-50 border-gray-200 rounded-2xl py-4 px-5 font-bold focus:ring-blue-500 transition-all" required />
                            <span class="absolute right-5 top-4 font-bold text-gray-400">.localhost</span>
                        </div>
                        <p v-if="form.errors.store_name" class="text-red-500 text-xs mt-2 ml-1">{{ form.errors.store_name }}</p>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="isModalOpen = false" class="flex-1 bg-gray-100 text-gray-600 py-4 rounded-2xl font-bold hover:bg-gray-200 transition-all">
                            Batal
                        </button>
                        <button type="submit" :disabled="form.processing" class="flex-1 bg-blue-600 text-white py-4 rounded-2xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all">
                            Gas Bikin! 🚀
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>