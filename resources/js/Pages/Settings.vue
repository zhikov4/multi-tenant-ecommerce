<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Settings" />

    <AuthenticatedLayout>
        <div class="max-w-4xl mx-auto space-y-8">
            <header class="mb-8">
                <h1 class="text-3xl sm:text-4xl font-black text-slate-900 flex items-center gap-3">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Global Settings
                </h1>
                <p class="text-slate-500 mt-2 font-medium">Manage your notification and security preferences here.</p>
            </header>

            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                <div class="space-y-6">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-6">
                        <div>
                            <h3 class="font-bold text-lg text-slate-800">Email Notifications</h3>
                            <p class="text-sm text-slate-500">Receive emails for orders and promotions.</p>
                        </div>
                        <input type="checkbox" checked class="w-12 h-6 bg-slate-200 rounded-full appearance-none checked:bg-blue-600 transition-colors cursor-pointer relative after:absolute after:top-1 after:left-1 after:w-4 after:h-4 after:bg-white after:rounded-full checked:after:translate-x-6 after:transition-transform" />
                    </div>

                    <div class="flex items-center justify-between border-b border-slate-100 pb-6">
                        <div>
                            <h3 class="font-bold text-lg text-slate-800">Two-Factor Authentication</h3>
                            <p class="text-sm text-slate-500">Add an extra layer of security to your account.</p>
                        </div>
                        <button class="px-4 py-2 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200">Enable 2FA</button>
                    </div>

                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-lg text-red-600">Danger Zone</h3>
                            <p class="text-sm text-slate-500">Permanently delete your account and all data.</p>
                        </div>
                        <button class="px-4 py-2 border-2 border-red-100 text-red-600 font-bold rounded-xl hover:bg-red-50">Delete Account</button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>