<template>
    <div class="flex min-h-screen bg-gray-50 font-sans">
        <aside class="w-64 bg-white border-r shadow-sm flex flex-col fixed h-full">
            <div class="p-6 font-black text-indigo-600 text-2xl border-b tracking-tighter italic">MYSTORE</div>
            <nav class="flex-1 mt-4">
                <Link href="/dashboard" class="block px-6 py-4 text-gray-500 font-bold hover:bg-indigo-50">DASHBOARD</Link>
                <Link href="/products" class="block px-6 py-4 text-gray-500 font-bold hover:bg-indigo-50">MARKETPLACE</Link>
                <Link href="/profile" class="block px-6 py-4 text-gray-500 font-bold hover:bg-indigo-50">MY PROFILE</Link>
                <Link href="/settings" class="block px-6 py-4 text-indigo-600 bg-indigo-50 font-black border-r-4 border-indigo-600">SETTINGS</Link>
            </nav>
            <div class="p-6">
                <Link href="/logout" method="post" as="button" class="w-full py-4 bg-red-500 text-white rounded-xl font-black text-xs uppercase tracking-widest">Log Out</Link>
            </div>
        </aside>

        <main class="flex-1 ml-64 p-12">
            <div class="max-w-3xl mx-auto">
                <div class="flex items-center gap-4 mb-2">
                    <span class="text-3xl">⚙️</span>
                    <h1 class="text-3xl font-black text-gray-800 tracking-tight">Global Settings</h1>
                </div>
                <p class="text-gray-500 font-medium mb-10">Manage your notification and security preferences here.</p>

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-10 space-y-10">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-gray-800 text-lg">Email Notifications</h3>
                            <p class="text-sm text-gray-400">Receive emails for orders and promotions.</p>
                        </div>
                        <button @click="emailNotif = !emailNotif" :class="emailNotif ? 'bg-blue-600' : 'bg-gray-200'" class="w-12 h-6 rounded-full transition-colors relative">
                            <div :class="emailNotif ? 'translate-x-6' : 'translate-x-1'" class="absolute top-1 bg-white w-4 h-4 rounded-full transition-transform shadow-sm"></div>
                        </button>
                    </div>

                    <div class="flex justify-between items-center pt-6 border-t border-gray-50">
                        <div>
                            <h3 class="font-bold text-gray-800 text-lg">Two-Factor Authentication</h3>
                            <p class="text-sm text-gray-400">Add an extra layer of security to your account.</p>
                        </div>
                        <button class="bg-gray-100 px-6 py-2 rounded-xl font-bold text-gray-700 hover:bg-gray-200 transition">Enable 2FA</button>
                    </div>

                    <div class="flex justify-between items-center pt-10 border-t border-red-50">
                        <div>
                            <h3 class="font-bold text-red-500 text-lg italic">Danger Zone</h3>
                            <p class="text-sm text-gray-400">Permanently delete your account and all data.</p>
                        </div>
                        <button @click="deleteAccount" class="border border-red-100 bg-red-50 text-red-500 px-6 py-2 rounded-xl font-bold hover:bg-red-500 hover:text-white transition">Delete Account</button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const emailNotif = ref(true);

const deleteAccount = () => {
    if (confirm('Are you sure you want to delete this store account? This cannot be undone!')) {
        alert('Action restricted for demo purposes, Honey! 🛡️');
    }
};
</script>