<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const { props } = usePage();
const showingNavigationDropdown = ref(false);

// Mengambil status toko dari props yang dikirim DashboardController tadi
const hasStore = props.status?.hasStore || false;
const storeUrl = props.status?.storeUrl || '#';
const storeName = props.status?.storeName || '';
</script>

<template>
    <div class="min-h-screen bg-gray-100 flex">
        <aside class="w-64 bg-white border-r border-gray-200 hidden md:flex flex-col sticky top-0 h-screen">
            <div class="p-6">
                <Link :href="route('central.home')" class="text-2xl font-black text-blue-600 tracking-tighter">
                    TOKOKU 🏪
                </Link>
            </div>

            <nav class="flex-1 px-4 space-y-2">
                <Link :href="route('central.home')" 
                    :class="route().current('central.home') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50'"
                    class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all">
                    <span class="mr-3 text-lg">🌍</span> Marketplace
                </Link>

                <template v-if="hasStore">
                    <a :href="storeUrl" 
                        class="flex items-center px-4 py-3 text-sm font-bold text-gray-600 hover:bg-gray-50 rounded-xl transition-all">
                        <span class="mr-3 text-lg">🛍️</span> My Store ({{ storeName }})
                    </a>
                </template>
                <template v-else>
                    <Link :href="route('dashboard')" 
                        class="flex items-center px-4 py-3 text-sm font-bold text-gray-600 hover:bg-gray-50 rounded-xl transition-all">
                        <span class="mr-3 text-lg">🚀</span> Buka Toko Baru
                    </Link>
                </template>

                <Link :href="route('profile.edit')" 
                    :class="route().current('profile.edit') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50'"
                    class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all">
                    <span class="mr-3 text-lg">👤</span> My Profile
                </Link>

                <Link :href="route('profile.edit')" 
                    class="flex items-center px-4 py-3 text-sm font-bold text-gray-600 hover:bg-gray-50 rounded-xl transition-all">
                    <span class="mr-3 text-lg">⚙️</span> Setting
                </Link>
            </nav>

            <div class="p-4 border-t border-gray-100">
                <div class="flex items-center px-4 py-3">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-black text-gray-900 truncate">{{ $page.props.auth.user.name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $page.props.auth.user.email }}</p>
                    </div>
                </div>
                <Link :href="route('logout')" method="post" as="button" class="w-full text-left px-4 py-2 text-xs font-bold text-red-600 hover:bg-red-50 rounded-lg transition-all">
                    Log Out
                </Link>
            </div>
        </aside>

        <div class="flex-1 flex flex-col">
            <header class="md:hidden bg-white border-b border-gray-200 p-4 flex justify-between items-center">
                <span class="font-black text-blue-600">TOKOKU</span>
                <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="text-gray-500">
                    Menu
                </button>
            </header>

            <main class="p-4 md:p-8">
                <slot />
            </main>
        </div>
    </div>
</template>