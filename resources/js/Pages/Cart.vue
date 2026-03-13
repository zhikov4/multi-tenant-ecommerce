<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const cartItems = ref([
    { id: 1, store: 'TechStore', name: 'Mechanical Keyboard K3 Edition', price: 1200000, qty: 1, image: 'DEV_1' },
    { id: 2, store: 'KopiSenja', name: 'Arabica Coffee Beans 500g', price: 150000, qty: 2, image: 'DEV_2' },
    { id: 3, store: 'WoodWork', name: 'Minimalist Office Desk', price: 2100000, qty: 1, image: 'DEV_4' },
]);

const selectedItems = ref([]);

const checkoutTotal = computed(() => {
    return cartItems.value
        .filter(item => selectedItems.value.includes(item.id))
        .reduce((total, item) => total + (item.price * item.qty), 0);
});

const checkout = () => {
    if (selectedItems.value.length === 0) {
        alert('Please select at least one item to checkout, babe! 😘');
        return;
    }
    alert(`Proceeding to checkout ${selectedItems.value.length} items. Total: Rp ${checkoutTotal.value.toLocaleString('id-ID')}`);
};
</script>

<template>
    <Head title="My Cart" />

    <AuthenticatedLayout>
        <div class="max-w-5xl mx-auto space-y-8">
            <header class="mb-8">
                <h1 class="text-3xl sm:text-4xl font-black text-slate-900 flex items-center gap-3">
                    <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    Shopping Cart
                </h1>
                <p class="text-slate-500 mt-2 font-medium">Select the items you want to checkout today.</p>
            </header>

            <div class="flex flex-col lg:flex-row gap-8">
                <div class="lg:w-2/3 space-y-4">
                    <div v-for="item in cartItems" :key="item.id" class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-4 transition-all hover:border-blue-200">
                        <div class="flex-shrink-0">
                            <input type="checkbox" :value="item.id" v-model="selectedItems" class="w-6 h-6 text-blue-600 border-slate-300 rounded-lg focus:ring-blue-500 cursor-pointer" />
                        </div>
                        <div class="w-20 h-20 bg-slate-50 rounded-2xl flex items-center justify-center border border-slate-100 flex-shrink-0">
                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest">{{ item.store }}</p>
                            <h3 class="font-bold text-slate-800 text-base md:text-lg truncate">{{ item.name }}</h3>
                            <p class="text-lg font-black text-slate-900 mt-1">Rp {{ item.price.toLocaleString('id-ID') }}</p>
                        </div>
                        <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl p-1 hidden sm:flex">
                            <button @click="item.qty > 1 ? item.qty-- : 1" class="w-8 h-8 rounded-lg bg-white shadow-sm font-bold text-slate-500 hover:text-blue-600">-</button>
                            <span class="w-8 text-center font-black text-slate-800">{{ item.qty }}</span>
                            <button @click="item.qty++" class="w-8 h-8 rounded-lg bg-white shadow-sm font-bold text-slate-500 hover:text-blue-600">+</button>
                        </div>
                    </div>
                </div>

                <div class="lg:w-1/3">
                    <div class="bg-white p-6 sm:p-8 rounded-[2.5rem] shadow-sm border border-slate-100 sticky top-28 min-w-0">
                        <h3 class="text-xl font-black text-slate-900 mb-6 border-b border-slate-100 pb-4">Order Summary</h3>
                        
                        <div class="flex justify-between items-center mb-6 text-slate-500">
                            <span class="font-medium">Selected Items</span>
                            <span class="font-black text-slate-800 text-lg">{{ selectedItems.length }}</span>
                        </div>
                        
                        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5 mb-6">
                            <p class="font-bold text-blue-600 text-xs uppercase tracking-widest mb-1">Total Price</p>
                            <p class="text-3xl font-black text-blue-700 break-words leading-tight">Rp {{ checkoutTotal.toLocaleString('id-ID') }}</p>
                        </div>

                        <button @click="checkout" :disabled="selectedItems.length === 0" 
                            :class="selectedItems.length > 0 ? 'bg-blue-600 hover:bg-blue-700 shadow-blue-200' : 'bg-slate-200 text-slate-400 cursor-not-allowed'"
                            class="w-full text-white py-4 rounded-2xl font-black shadow-lg transition-all flex justify-center items-center gap-2">
                            Checkout Selected
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>