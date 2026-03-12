<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="min-h-screen bg-[#FBFBFD]">
        <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center space-x-8">
                        <Link :href="route('products.index')" class="text-xl font-bold tracking-tight text-black">
                            MyStoreTest<span class="text-indigo-600">.</span>
                        </Link>
                        <div class="hidden space-x-6 sm:flex">
                            <Link :href="route('products.index')" :class="route().current('products.*') ? 'text-black' : 'text-gray-400'" class="text-sm font-medium hover:text-black transition-colors">Shop</Link>
                            <Link :href="route('orders.index')" :class="route().current('orders.*') ? 'text-black' : 'text-gray-400'" class="text-sm font-medium hover:text-black transition-colors">Orders</Link>
                        </div>
                    </div>

                    <div class="flex items-center space-x-6">
                        <Link :href="route('cart.index')" class="relative p-2 text-gray-400 hover:text-black transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </Link>
                        
                        <div class="hidden sm:flex items-center">
                            <div class="text-right mr-3">
                                <p class="text-xs font-semibold text-black leading-none">{{ $page.props.auth.user.name }}</p>
                                <p class="text-[10px] text-gray-400 uppercase tracking-widest mt-1">Premium Member</p>
                            </div>
                            <div class="h-8 w-8 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center text-xs font-bold text-gray-500">
                                {{ $page.props.auth.user.name.charAt(0) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <main class="animate-in fade-in duration-700">
            <slot />
        </main>
    </div>
</template>