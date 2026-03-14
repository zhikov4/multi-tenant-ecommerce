<script setup>
import { ref, computed } from 'vue'
import { usePage, useForm } from '@inertiajs/vue3'

const page = usePage()
const isSidebarOpen = ref(false)
const showNotifications = ref(false)
const isStoreMenuOpen = ref(true)
const showLogoutModal = ref(false)

const isTenantDomain = computed(() => window.location.hostname !== 'localhost')

const centralUrl = (path) => {
    const port = window.location.port ? `:${window.location.port}` : ''
    return `${window.location.protocol}//localhost${port}${path}`
}

const userStores = computed(() => page.props.userStores ?? [])

const notifications = ref([
    { id: 1, title: 'Order #1024', desc: 'New payment confirmed', time: '2m ago', unread: true },
    { id: 2, title: 'System Update', desc: 'New console features enabled', time: '1h ago', unread: false },
])

const unreadCount = computed(() => notifications.value.filter(n => n.unread).length)

const logoutForm = useForm({})

const confirmLogout = async () => {
    if (isTenantDomain.value) {
        const port = window.location.port ? `:${window.location.port}` : ''
        const logoutUrl = `${window.location.protocol}//${window.location.hostname}${port}/logout`

        try {
            await fetch(logoutUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                credentials: 'include',
            })
        } catch (e) {}

        const centralPort = window.location.port ? `:${window.location.port}` : ''
        window.location.href = `${window.location.protocol}//localhost${centralPort}/`
    } else {
        logoutForm.post('/logout', {
            onSuccess: () => { showLogoutModal.value = false },
        })
    }
}
</script>

<template>
    <div class="h-screen bg-slate-50 text-slate-900 font-sans flex flex-col overflow-hidden">

        <header class="h-20 bg-white border-b border-slate-200 px-6 flex items-center justify-between sticky top-0 z-40 shadow-sm flex-shrink-0">
            <div class="flex items-center gap-4">
                <button @click="isSidebarOpen = true" class="md:hidden p-2 bg-slate-50 rounded-xl border border-slate-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <a :href="centralUrl('/')" class="text-2xl font-black text-blue-600 tracking-tighter flex items-center gap-2 uppercase">MyStore</a>
            </div>

            <div class="relative">
                <button @click="showNotifications = !showNotifications" class="relative p-2.5 text-slate-500 hover:text-blue-600 bg-slate-50 rounded-xl border border-slate-200 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <span v-if="unreadCount > 0" class="absolute top-1 right-1 flex h-3.5 w-3.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-red-500 border-2 border-white"></span>
                    </span>
                </button>
            </div>
        </header>

        <div class="flex flex-1 overflow-hidden relative">
            <div v-if="isSidebarOpen" @click="isSidebarOpen = false" class="fixed inset-0 bg-slate-900/50 z-40 md:hidden"></div>

            <aside :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'" class="w-72 bg-white border-r border-slate-200 flex flex-col fixed md:relative z-50 h-full transition-transform flex-shrink-0">
                <nav class="flex-1 px-6 space-y-2 mt-8 overflow-y-auto pb-10">

                    <a :href="centralUrl('/dashboard')" class="flex items-center px-5 py-4 text-sm font-black text-slate-500 hover:bg-blue-50 hover:text-blue-700 uppercase tracking-tight rounded-[1.25rem] transition-all">Dashboard</a>

                    <a :href="centralUrl('/')" class="flex items-center px-5 py-4 text-sm font-black text-slate-500 hover:bg-slate-50 uppercase tracking-tight rounded-[1.25rem] transition-all">Marketplace</a>

                    <div class="pt-4 border-t border-slate-50 mt-4">
                        <button @click="isStoreMenuOpen = !isStoreMenuOpen" class="w-full flex items-center justify-between px-5 py-2 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">
                            Manage MyStores
                            <svg :class="isStoreMenuOpen ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div v-if="isStoreMenuOpen" class="space-y-1 ml-4 border-l-2 border-slate-50 pl-2">
                            <a v-for="store in userStores" :key="store.id" :href="store.url" class="flex items-center px-5 py-3 text-[13px] font-bold text-slate-600 hover:text-blue-700 rounded-xl truncate hover:bg-blue-50 transition-all">{{ store.name }}</a>
                            <p v-if="userStores.length === 0" class="px-5 py-2 text-[12px] text-slate-400">No stores yet.</p>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-50 mt-4 space-y-1">
                        <a :href="centralUrl('/profile')" class="flex items-center px-5 py-4 text-sm font-black text-slate-500 hover:bg-slate-50 uppercase tracking-tight rounded-[1.25rem] transition-all">My Profile</a>
                        <a :href="centralUrl('/settings')" class="flex items-center px-5 py-4 text-sm font-black text-slate-500 hover:bg-slate-50 uppercase tracking-tight rounded-[1.25rem] transition-all">Settings</a>
                    </div>
                </nav>

                <div class="p-6 border-t border-slate-100 flex-shrink-0 bg-white">
                    <button @click="showLogoutModal = true" class="w-full flex items-center justify-center gap-3 bg-red-50 text-red-600 px-6 py-4 text-sm font-black uppercase tracking-widest hover:bg-red-100 rounded-2xl transition-all shadow-sm">
                        Log Out
                    </button>
                </div>
            </aside>

            <main class="flex-1 h-full overflow-y-auto p-6 md:p-12 scroll-smooth">
                <slot />
            </main>
        </div>

        <div v-if="showLogoutModal" class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md">
            <div class="bg-white w-full max-w-sm rounded-3xl p-8 shadow-2xl text-center">
                <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </div>
                <h2 class="text-2xl font-black text-slate-900 mb-2">Log Out?</h2>
                <p class="text-slate-500 text-sm mb-8">Are you sure you want to log out of your account?</p>
                <div class="flex gap-3">
                    <button @click="showLogoutModal = false" class="flex-1 bg-slate-100 text-slate-700 py-3 rounded-2xl font-bold hover:bg-slate-200 transition-all">
                        Cancel
                    </button>
                    <button @click="confirmLogout" :disabled="logoutForm.processing" class="flex-1 bg-red-600 text-white py-3 rounded-2xl font-bold hover:bg-red-700 transition-all disabled:opacity-50">
                        {{ logoutForm.processing ? 'Logging out...' : 'Log Out' }}
                    </button>
                </div>
            </div>
        </div>

    </div>
</template>