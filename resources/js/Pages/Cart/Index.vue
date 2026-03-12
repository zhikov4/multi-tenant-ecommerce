<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    cartItems: Array
});

const form = useForm({});

const removeItem = (id) => {
    form.delete(route('cart.destroy', id), {
        preserveScroll: true
    });
};

const checkout = () => {
    form.post(route('checkout.store'), {
        onFinish: () => form.reset(),
    });
};

const subtotal = props.cartItems.reduce((acc, item) => {
    return acc + (item.product.price * item.quantity);
}, 0);
</script>

<template>
    <Head title="Shopping Bag" />

    <AuthenticatedLayout>
        <div class="py-16 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto">
            <h1 class="text-3xl font-bold text-black mb-10 tracking-tight">Shopping Bag</h1>

            <div v-if="cartItems.length === 0" class="bg-white rounded-3xl p-12 text-center border border-gray-100 shadow-sm">
                <div class="mb-6 flex justify-center text-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <p class="text-gray-500 mb-8 font-medium">Your bag is currently empty.</p>
                <Link :href="route('products.index')" class="bg-black text-white px-8 py-3 rounded-2xl font-bold hover:bg-gray-800 transition-all">
                    Start Shopping
                </Link>
            </div>

            <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <div class="lg:col-span-2 space-y-6">
                    <div v-for="item in cartItems" :key="item.id" class="flex items-center justify-between p-6 bg-white border border-gray-100 rounded-3xl shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center space-x-6">
                            <div class="h-20 w-20 bg-gray-50 rounded-2xl flex items-center justify-center border border-gray-100 text-[10px] text-gray-300 font-bold uppercase tracking-widest">
                                Item
                            </div>
                            <div>
                                <h3 class="font-bold text-lg text-black">{{ item.product.name }}</h3>
                                <p class="text-sm text-gray-400">Qty: {{ item.quantity }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-lg mb-2">${{ item.product.price * item.quantity }}</p>
                            <button @click="removeItem(item.id)" class="text-xs font-black uppercase tracking-widest text-red-500 hover:text-red-700 transition-colors">
                                Remove
                            </button>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm sticky top-24">
                        <h2 class="text-xl font-bold mb-6">Summary</h2>
                        <div class="space-y-4 mb-8">
                            <div class="flex justify-between text-gray-500">
                                <span>Subtotal</span>
                                <span>${{ subtotal }}</span>
                            </div>
                            <div class="flex justify-between text-gray-500">
                                <span>Shipping</span>
                                <span class="text-green-600 font-medium uppercase text-xs tracking-widest mt-1 italic">Free</span>
                            </div>
                            <div class="pt-4 border-t flex justify-between items-center">
                                <span class="font-bold text-black text-lg">Total</span>
                                <span class="font-black text-black text-2xl tracking-tighter">${{ subtotal }}</span>
                            </div>
                        </div>

                        <button @click="checkout" 
                            :disabled="form.processing"
                            class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-bold text-lg hover:bg-indigo-700 active:scale-95 transition-all disabled:opacity-50">
                            {{ form.processing ? 'Processing...' : 'Complete Purchase' }}
                        </button>
                        
                        <p class="mt-4 text-[10px] text-center text-gray-300 uppercase tracking-widest font-medium">
                            Secure Encrypted Checkout
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>