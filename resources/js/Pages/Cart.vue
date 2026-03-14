<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, computed, inject } from 'vue';

const route = inject('route');

const props = defineProps({
    cartItems: { type: Array, default: () => [] },
});

const formatUSD = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val);

const selectedIds = ref([]);

const total = computed(() =>
    props.cartItems
        .filter(i => selectedIds.value.includes(i.id))
        .reduce((sum, i) => sum + i.product_price * i.quantity, 0)
);

const updateQty = (item, delta) => {
    const newQty = item.quantity + delta;
    if (newQty < 1) return;
    router.patch(route('cart.update', item.id), { quantity: newQty }, { preserveScroll: true });
};

const removeItem = (item) => {
    if (confirm('Remove this item?')) {
        router.delete(route('cart.destroy', item.id), { preserveScroll: true });
        selectedIds.value = selectedIds.value.filter(id => id !== item.id);
    }
};

const clearCart = () => {
    if (confirm('Remove all items from cart?')) {
        router.delete(route('cart.clear'), { preserveScroll: true });
        selectedIds.value = [];
    }
};

const checkout = () => {
    if (selectedIds.value.length === 0) {
        alert('Select at least one item to checkout.');
        return;
    }
    alert(`Checkout ${selectedIds.value.length} item(s) — Total: ${formatUSD(total.value)}`);
};
</script>

<template>
    <Head title="My Cart" />
    <AuthenticatedLayout>
        <div class="max-w-5xl mx-auto">
            <header class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-black text-slate-900 flex items-center gap-3">
                        <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        Shopping Cart
                    </h1>
                    <p class="text-slate-500 mt-1">{{ cartItems.length }} item(s) in your cart</p>
                </div>
                <button v-if="cartItems.length > 0" @click="clearCart" class="text-xs font-black uppercase tracking-widest text-red-400 hover:text-red-600 transition-colors">
                    Clear All
                </button>
            </header>

            <!-- Empty -->
            <div v-if="cartItems.length === 0" class="text-center py-24 bg-white rounded-3xl border-2 border-dashed border-slate-200">
                <svg class="w-16 h-16 text-slate-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <p class="text-slate-400 font-black uppercase tracking-widest text-sm">Your cart is empty</p>
            </div>

            <div v-else class="flex flex-col lg:flex-row gap-8">
                <!-- Items -->
                <div class="lg:w-2/3 space-y-4">
                    <div v-for="item in cartItems" :key="item.id"
                        class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4 hover:border-blue-200 transition-all group">
                        <input type="checkbox" :value="item.id" v-model="selectedIds" class="w-5 h-5 text-blue-600 rounded-lg flex-shrink-0" />
                        <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center border border-slate-100 flex-shrink-0">
                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[10px] font-black uppercase tracking-widest text-blue-500">{{ item.product_store }}</p>
                            <h3 class="font-bold text-slate-800 truncate">{{ item.product_name }}</h3>
                            <p class="font-black text-slate-900">{{ formatUSD(item.product_price) }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-1 bg-slate-50 border border-slate-200 rounded-xl p-1">
                                <button @click="updateQty(item, -1)" class="w-8 h-8 rounded-lg bg-white shadow-sm font-bold text-slate-500 hover:text-blue-600">-</button>
                                <span class="w-8 text-center font-black text-sm">{{ item.quantity }}</span>
                                <button @click="updateQty(item, 1)" class="w-8 h-8 rounded-lg bg-white shadow-sm font-bold text-slate-500 hover:text-blue-600">+</button>
                            </div>
                            <button @click="removeItem(item)" class="w-9 h-9 rounded-xl bg-red-50 text-red-400 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center opacity-0 group-hover:opacity-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Summary -->
                <div class="lg:w-1/3">
                    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm sticky top-28">
                        <h3 class="text-xl font-black text-slate-900 mb-6 pb-4 border-b border-slate-100">Order Summary</h3>
                        <div class="flex justify-between text-slate-500 mb-2">
                            <span>Items in cart</span>
                            <span class="font-black text-slate-800">{{ cartItems.length }}</span>
                        </div>
                        <div class="flex justify-between text-slate-500 mb-6">
                            <span>Selected</span>
                            <span class="font-black text-slate-800">{{ selectedIds.length }}</span>
                        </div>
                        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5 mb-6">
                            <p class="text-xs font-bold text-blue-600 uppercase tracking-widest mb-1">Total</p>
                            <p class="text-3xl font-black text-blue-700">{{ formatUSD(total) }}</p>
                        </div>
                        <button @click="checkout"
                            :disabled="selectedIds.length === 0"
                            :class="selectedIds.length > 0 ? 'bg-blue-600 hover:bg-blue-700 text-white shadow-lg shadow-blue-200' : 'bg-slate-100 text-slate-400 cursor-not-allowed'"
                            class="w-full py-4 rounded-2xl font-black transition-all flex justify-center items-center gap-2">
                            Checkout
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>