<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const products = Array.from({ length: 12 }, (_, i) => ({
    id: i,
    name: `Koleksi Keren #${i + 1}`,
    price: (Math.floor(Math.random() * 50) + 10) * 5000,
    image: `https://picsum.photos/seed/${i + 50}/400/400`,
    store: 'Toko Elite'
}));
</script>

<template>
    <Head title="TOKOKU - Discovery Page" />

    <div class="min-h-screen bg-white font-sans text-slate-900">
        <nav class="border-b border-slate-100 sticky top-0 bg-white/80 backdrop-blur-md z-50">
            <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
                <Link href="/" class="text-2xl font-black tracking-tighter text-blue-600">TOKOKU 🏪</Link>
                
                <div class="flex items-center gap-4">
                    <template v-if="$page.props.auth.user">
                        <Link :href="route('dashboard')" class="font-bold text-slate-600 hover:text-blue-600">Dashboard</Link>
                    </template>
                    <template v-else>
                        <Link :href="route('login')" class="font-bold text-slate-600 hover:text-blue-600 px-4">Log in</Link>
                        <Link :href="route('register')" class="bg-slate-900 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-blue-600 transition-all shadow-lg shadow-slate-200">
                            Register
                        </Link>
                    </template>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto px-6 py-12">
            <header class="mb-12">
                <h1 class="text-5xl font-black tracking-tight mb-4">Discovery Page 🌍</h1>
                <p class="text-xl text-slate-500 font-medium">Ribuan barang unik dari seller lokal di seluruh Indonesia.</p>
            </header>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <div v-for="product in products" :key="product.id" class="group cursor-pointer">
                    <div class="aspect-square rounded-[2rem] overflow-hidden bg-slate-100 mb-4 shadow-sm border border-slate-50 relative">
                        <img :src="product.image" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="product">
                        <div class="absolute top-4 left-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest text-slate-600">
                            {{ product.store }}
                        </div>
                    </div>
                    <h3 class="font-bold text-lg text-slate-800 truncate">{{ product.name }}</h3>
                    <p class="text-blue-600 font-black text-xl">Rp {{ product.price.toLocaleString('id-ID') }}</p>
                </div>
            </div>

            <div class="mt-20 py-12 border-t border-slate-100 text-center">
                <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Satu Akun untuk Semua Toko</p>
                <h2 class="text-2xl font-black mt-4">Mau Jualan Juga? Buka Toko Lu Sekarang! 🚀</h2>
                <Link :href="route('register')" class="inline-block mt-6 text-blue-600 font-black border-b-2 border-blue-600 pb-1">Daftar Jadi Seller &rarr;</Link>
            </div>
        </main>
    </div>
</template>