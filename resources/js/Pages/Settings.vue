<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const emailNotif = ref(true);

const deleteAccount = () => {
    if (confirm('Are you sure you want to delete your account? This cannot be undone!')) {
        alert('Action restricted for demo purposes.');
    }
};
</script>

<template>
    <Head title="Settings" />
    <AuthenticatedLayout>
        <div class="max-w-3xl mx-auto">
            <div class="flex items-center gap-4 mb-2">
                <span class="text-3xl">⚙️</span>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">Global Settings</h1>
            </div>
            <p class="text-slate-500 font-medium mb-10">Manage your notification and security preferences here.</p>

            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-10 space-y-10">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-slate-800 text-lg">Email Notifications</h3>
                        <p class="text-sm text-slate-400">Receive emails for orders and promotions.</p>
                    </div>
                    <button @click="emailNotif = !emailNotif" :class="emailNotif ? 'bg-blue-600' : 'bg-slate-200'" class="w-12 h-6 rounded-full transition-colors relative">
                        <div :class="emailNotif ? 'translate-x-6' : 'translate-x-1'" class="absolute top-1 bg-white w-4 h-4 rounded-full transition-transform shadow-sm"></div>
                    </button>
                </div>

                <div class="flex justify-between items-center pt-6 border-t border-slate-50">
                    <div>
                        <h3 class="font-bold text-slate-800 text-lg">Two-Factor Authentication</h3>
                        <p class="text-sm text-slate-400">Add an extra layer of security to your account.</p>
                    </div>
                    <button class="bg-slate-100 px-6 py-2 rounded-xl font-bold text-slate-700 hover:bg-slate-200 transition">Enable 2FA</button>
                </div>

                <div class="flex justify-between items-center pt-10 border-t border-red-50">
                    <div>
                        <h3 class="font-bold text-red-500 text-lg italic">Danger Zone</h3>
                        <p class="text-sm text-slate-400">Permanently delete your account and all data.</p>
                    </div>
                    <button @click="deleteAccount" class="border border-red-100 bg-red-50 text-red-500 px-6 py-2 rounded-xl font-bold hover:bg-red-500 hover:text-white transition">Delete Account</button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
