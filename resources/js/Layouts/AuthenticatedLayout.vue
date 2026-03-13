<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const { props } = usePage();
const isSidebarOpen = ref(false);
const showNotifications = ref(false); // State untuk Dropdown Notif

const hasStore = props.status?.hasStore || false;
const storeUrl = props.status?.storeUrl || '#';
const storeName = props.status?.storeName || '';

// Data Dummy Notifikasi
const notifications = [
    { id: 1, type: 'order', title: 'New Order Received', desc: 'Order #INV-002 needs processing.', time: '2 mins ago', unread: true },
    { id: 2, type: 'tracking', title: 'Package Shipped', desc: 'Your order #INV-001 is on the way.', time: '5 hours ago', unread: true },
    { id: 3, type: 'system', title: 'Welcome to MyStore', desc: 'Start setting up your store profile.', time: '1 day ago', unread: false },
];
</script>

<template>
    <div class="min-h-screen bg-slate-50 text-slate-900 font-sans flex flex-col">
        
        <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between sticky top-0 z-40 shadow-sm w-full">
            <div class="flex items-center gap-4">
                <button @click="isSidebarOpen = true" class="md:hidden text-slate-500 hover:text-blue-600 focus:outline-none bg-slate-50 p-2 rounded-xl border border-slate-200 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <Link :href="route('central.home')" class="text-2xl font-black text-blue-600 tracking-tighter flex items-center gap-2">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <span class="hidden sm:inline-block">MyStore</span>
                </Link>
            </div>
            
            <div class="relative">
                <button @click="showNotifications = !showNotifications" class="relative p-2 text-slate-500 hover:text-blue-600 bg-slate-50 rounded-xl border border-slate-200 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <span class="absolute top-1 right-1 flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500 border-2 border-white"></span>
                    </span>
                </button>

                <div v-if="showNotifications" class="absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden z-50 animate-in fade-in slide-in-from-top-2">
                    <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                        <h3 class="font-black text-slate-800">Notifications</h3>
                        <button class="text-xs font-bold text-blue-600 hover:text-blue-800">Mark all as read</button>
                    </div>
                    <div class="max-h-80 overflow-y-auto">
                        <div v-for="notif in notifications" :key="notif.id" :class="notif.unread ? 'bg-blue-50/50' : ''" class="p-4 border-b border-slate-50 hover:bg-slate-50 cursor-pointer transition-colors flex gap-3">
                            <div class="mt-1">
                                <svg v-if="notif.type === 'order'" class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                <svg v-else-if="notif.type === 'tracking'" class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                <svg v-else class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800">{{ notif.title }}</h4>
                                <p class="text-xs text-slate-500 mt-0.5">{{ notif.desc }}</p>
                                <p class="text-[10px] font-bold text-slate-400 mt-2">{{ notif.time }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-3 text-center border-t border-slate-100">
                        <Link href="#" class="text-xs font-bold text-slate-500 hover:text-blue-600">View all notifications</Link>
                    </div>
                </div>
            </div>
        </header>

        <div class="flex flex-1 relative w-full">
            <div v-if="isSidebarOpen" @click="isSidebarOpen = false" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 md:hidden transition-opacity"></div>

            <aside 
                :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
                class="w-72 bg-white border-r border-slate-200 flex flex-col fixed inset-y-0 left-0 z-50 transform transition-transform duration-300 ease-in-out md:sticky md:top-0 h-screen shadow-2xl md:shadow-none flex-shrink-0">
                
                <div class="p-6 md:p-8 flex items-center justify-between md:hidden">
                    <span class="text-xl font-black text-slate-800">Menu</span>
                    <button @click="isSidebarOpen = false" class="text-slate-400 hover:text-red-500 bg-slate-50 p-2 rounded-full transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <nav class="flex-1 px-4 md:px-6 space-y-2 mt-4 overflow-y-auto">
                    <Link :href="route('dashboard')" @click="isSidebarOpen = false"
                        :class="route().current('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900'"
                        class="flex items-center px-4 py-3.5 text-sm font-bold rounded-2xl transition-all break-words min-w-0">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        <span class="truncate">Dashboard</span>
                    </Link>

                    <Link :href="route('central.home')" @click="isSidebarOpen = false"
                        :class="route().current('central.home') ? 'bg-blue-50 text-blue-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900'"
                        class="flex items-center px-4 py-3.5 text-sm font-bold rounded-2xl transition-all break-words min-w-0">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="truncate">Marketplace</span>
                    </Link>

                    <template v-if="hasStore">
                        <a :href="storeUrl" @click="isSidebarOpen = false"
                            class="flex items-center px-4 py-3.5 text-sm font-bold text-slate-500 hover:bg-slate-50 hover:text-slate-900 rounded-2xl transition-all break-words min-w-0">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            <span class="truncate">Manage MyStore</span>
                        </a>
                    </template>

                    <Link :href="route('profile.edit')" @click="isSidebarOpen = false"
                        :class="route().current('profile.edit') ? 'bg-blue-50 text-blue-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900'"
                        class="flex items-center px-4 py-3.5 text-sm font-bold rounded-2xl transition-all break-words min-w-0">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <span class="truncate">My Profile</span>
                    </Link>

                    <Link :href="route('settings')" @click="isSidebarOpen = false"
                        :class="route().current('settings') ? 'bg-blue-50 text-blue-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900'"
                        class="flex items-center px-4 py-3.5 text-sm font-bold rounded-2xl transition-all break-words min-w-0">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span class="truncate">Settings</span>
                    </Link>
                </nav>

                <div class="p-6 border-t border-slate-100">
                    <Link :href="route('logout')" method="post" as="button" class="w-full flex items-center justify-center gap-2 bg-red-50 text-red-600 px-4 py-3 text-sm font-bold hover:bg-red-100 rounded-xl transition-all">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Log Out
                    </Link>
                </div>
            </aside>

            <main class="flex-1 p-4 sm:p-6 md:p-10 w-full min-w-0 overflow-x-hidden">
                <slot />
            </main>
        </div>
    </div>
</template>