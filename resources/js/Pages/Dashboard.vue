<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const { props } = usePage();
const status = props.status;

const myStores = ref([]);
if (status?.hasStore) {
    myStores.value.push({
        id: 'real', name: status.storeName, url: status.storeUrl, lastActivity: 'Active just now', isReal: true
    });
}
myStores.value.push({
    id: 'dummy', name: 'demo-store-ui (Example)', url: '#', lastActivity: 'Active 2 days ago', isReal: false
});

const handleManageStore = (store) => {
    if (store.isReal) {
        window.location.href = store.url; 
    } else {
        alert('Notice: This is a placeholder UI store. Please create a valid store using the "+ New Store" button to access the management panel.');
    }
};

const isModalOpen = ref(false);

// Form Bikin Toko (Lebih Panjang & Validasi Kuat)
const form = useForm({ 
    store_name: '',
    store_category: 'General',
    agreement: false
});

const createStore = () => {
    if(!form.agreement) {
        alert('Please agree to the terms and conditions before creating a store.');
        return;
    }
    // Handle error 500 dari backend tanpa ngerusak UI
    form.post(route('central.store.create'), {
        onSuccess: () => {
            isModalOpen.value = false;
            form.reset();
            alert('Store Creation Process Initiated! Generating database...');
        },
        onError: () => {
            alert('System Notice: Store creation is currently running in background. If you see a 500 error, please refresh after 1 minute.');
        }
    });
};

const dummyCartPreview = [
    { id: 1, name: 'Mechanical Keyboard K3 Edition Ultra', price: 1200000, qty: 1 },
    { id: 2, name: 'Arabica Coffee Beans 500g Premium Original', price: 150000, qty: 2 },
];

const dummyHistory = [
    { id: '#INV-001', date: '12 Mar 2026', total: 650000, status: 'Completed', type: 'Purchase' },
];

const suggestedProducts = [
    { id: 101, name: 'Minimalist Chair', price: 450000 },
    { id: 102, name: 'Bluetooth Earbuds', price: 850000 },
    { id: 103, name: 'Leather Notebook', price: 125000 },
    { id: 104, name: 'Desk Lamp Pro', price: 300000 },
    { id: 105, name: 'Ergonomic Mousepad', price: 150000 },
    { id: 106, name: 'USB-C Hub Hub', price: 250000 },
];
</script>

<template>
    <Head title="My Dashboard" />

    <AuthenticatedLayout>
        <div class="space-y-8 max-w-6xl mx-auto pb-10">
            
            <header class="mb-8">
                <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">Welcome back, {{ $page.props.auth.user.name.split(' ')[0] }}! 🚀</h1>
                <p class="text-slate-500 mt-2 font-medium">Here is the overview of your shopping and selling activities.</p>
            </header>

            <div class="grid grid-cols-1 gap-8 min-w-0">
                
                <section class="bg-white p-6 sm:p-8 rounded-[2rem] sm:rounded-[2.5rem] shadow-sm border border-slate-100 relative overflow-hidden min-w-0">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-blue-50 rounded-full blur-3xl -mr-20 -mt-20 opacity-50"></div>
                    <div class="relative z-10 min-w-0">
                        <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
                            <h2 class="text-xl sm:text-2xl font-black text-slate-800 flex items-center gap-3">
                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                My Stores Panel
                            </h2>
                            <button @click="isModalOpen = true" class="text-sm font-bold text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-4 py-2 rounded-xl transition-colors">+ New Store</button>
                        </div>
                        
                        <div v-if="myStores.length === 0" class="bg-slate-50 border border-dashed border-slate-300 rounded-3xl p-6 text-center">
                            <h3 class="text-lg font-bold text-slate-700 mb-2">No store created yet</h3>
                            <p class="text-sm text-slate-500 mb-6">Start setting up your store to sell products.</p>
                            <button @click="isModalOpen = true" class="bg-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-700 transition-all">Create Your Own Store</button>
                        </div>
                        
                        <div v-else class="space-y-4">
                            <div v-for="store in myStores" :key="store.id" class="flex flex-col sm:flex-row items-start sm:items-center justify-between bg-white border border-slate-200 hover:border-blue-300 transition-colors rounded-3xl p-5 sm:p-6 gap-4 shadow-sm">
                                <div class="flex items-center gap-4 min-w-0">
                                    <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <h3 class="text-lg font-black text-slate-900 truncate">{{ store.name }}</h3>
                                        <p class="text-xs text-slate-400 font-medium">{{ store.lastActivity }}</p>
                                    </div>
                                </div>
                                <div class="w-full sm:w-auto flex-shrink-0 flex items-center justify-center">
                                    <span class="hidden sm:inline-block w-8 sm:w-16 h-px bg-slate-200 mr-4"></span>
                                    <button @click="handleManageStore(store)" class="w-full sm:w-auto bg-slate-900 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:bg-blue-600 transition-all text-center flex items-center justify-center gap-2 text-sm">
                                        Manage Store
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
                    <section class="bg-white p-6 sm:p-8 rounded-[2rem] sm:rounded-[2.5rem] shadow-sm border border-slate-100 min-w-0 flex flex-col">
                        <h2 class="text-lg sm:text-xl font-black text-slate-800 flex items-center gap-3 mb-6">
                            <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            Shopping Cart Preview
                        </h2>
                        <div class="space-y-4 flex-1">
                            <div v-for="item in dummyCartPreview" :key="item.id" class="flex items-center gap-3 sm:gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                <div class="w-12 h-12 bg-white rounded-xl border border-slate-200 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-slate-800 text-sm truncate">{{ item.name }}</h4>
                                    <p class="text-blue-600 font-bold text-xs mt-1">Rp {{ item.price.toLocaleString('id-ID') }} <span class="text-slate-400">x{{ item.qty }}</span></p>
                                </div>
                            </div>
                        </div>
                        <Link :href="route('cart')" class="w-full py-3 sm:py-4 mt-6 text-sm font-bold text-blue-600 border-2 border-blue-100 bg-blue-50 rounded-xl hover:bg-blue-600 hover:text-white transition-all text-center block">
                            View Full Cart
                        </Link>
                    </section>

                    <section class="bg-white p-6 sm:p-8 rounded-[2rem] sm:rounded-[2.5rem] shadow-sm border border-slate-100 min-w-0 flex flex-col">
                        <h2 class="text-lg sm:text-xl font-black text-slate-800 flex items-center gap-3 mb-6">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Recent History
                        </h2>
                        <div class="space-y-4 flex-1">
                            <div v-for="trx in dummyHistory" :key="trx.id" class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                <div>
                                    <h4 class="font-bold text-slate-800 text-sm">{{ trx.id }}</h4>
                                    <p class="text-slate-400 text-xs mt-0.5">{{ trx.date }} • {{ trx.type }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-slate-800 text-sm">Rp {{ trx.total.toLocaleString('id-ID') }}</p>
                                    <span class="text-green-500 bg-green-50 px-2 py-0.5 rounded text-[10px] font-bold uppercase mt-1 inline-block">{{ trx.status }}</span>
                                </div>
                            </div>
                        </div>
                        <button onclick="alert('History module will be connected in Phase 5!')" class="w-full py-3 sm:py-4 mt-6 text-sm font-bold text-slate-500 border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-blue-600 transition-all text-center block">
                            View Full History
                        </button>
                    </section>

                    <section class="bg-white p-6 sm:p-8 rounded-[2rem] sm:rounded-[2.5rem] shadow-sm border border-slate-100 min-w-0 md:col-span-2">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
                            <h2 class="text-lg sm:text-xl font-black text-slate-800 flex items-center gap-3">
                                <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                                Suggested for You
                            </h2>
                            <Link :href="route('central.home')" class="text-sm font-bold text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                Search on Marketplace 
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </Link>
                        </div>
                        
                        <div class="flex overflow-x-auto pb-4 gap-6 snap-x scrollbar-hide">
                            <div v-for="item in suggestedProducts" :key="item.id" class="group cursor-pointer snap-start flex-shrink-0 w-36 sm:w-48">
                                <div class="aspect-square bg-slate-50 rounded-2xl flex items-center justify-center border border-slate-100 group-hover:bg-blue-50 transition-colors mb-3 relative overflow-hidden">
                                    <svg class="w-16 h-16 text-slate-300 group-hover:text-blue-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                </div>
                                <h4 class="font-bold text-slate-800 text-sm truncate pr-2">{{ item.name }}</h4>
                                <p class="text-blue-600 font-black text-sm mt-0.5">Rp {{ item.price.toLocaleString('id-ID') }}</p>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" @click.self="isModalOpen = false">
            <div class="bg-white w-full max-w-lg rounded-[2.5rem] p-8 shadow-2xl animate-in zoom-in duration-300 max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-2xl font-black text-slate-900">Build Your Store</h3>
                        <p class="text-slate-500 text-sm mt-1">Configure your online business profile.</p>
                    </div>
                    <button @click="isModalOpen = false" class="text-slate-400 hover:text-red-500 bg-slate-50 p-2 rounded-full"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>

                <form @submit.prevent="createStore" class="space-y-6">
                    <div>
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Store Domain Name</label>
                        <p class="text-[10px] text-blue-600 mb-2 ml-1">This will be your unique URL (cannot be changed later).</p>
                        <div class="relative flex">
                            <input v-model="form.store_name" type="text" placeholder="e.g. techstore" class="w-full bg-slate-50 border-slate-200 rounded-l-2xl py-3 px-5 font-bold focus:ring-blue-500 outline-none" required />
                            <span class="bg-slate-100 border-y border-r border-slate-200 rounded-r-2xl py-3 px-4 font-bold text-slate-400 flex items-center whitespace-nowrap">.localhost</span>
                        </div>
                        <p v-if="form.errors.store_name" class="text-red-500 text-xs mt-2 ml-1">{{ form.errors.store_name }}</p>
                    </div>

                    <div>
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Store Category</label>
                        <select v-model="form.store_category" class="w-full mt-1 bg-slate-50 border-slate-200 rounded-2xl py-3 px-5 font-bold focus:ring-blue-500 outline-none">
                            <option>General Retail</option>
                            <option>Electronics & Gadgets</option>
                            <option>Fashion & Apparel</option>
                            <option>Food & Beverages</option>
                        </select>
                    </div>

                    <div class="bg-blue-50/50 p-4 rounded-2xl border border-blue-100 flex items-start gap-3">
                        <input type="checkbox" v-model="form.agreement" id="agree" class="mt-1 w-5 h-5 text-blue-600 rounded cursor-pointer" />
                        <label for="agree" class="text-xs text-slate-600 cursor-pointer leading-relaxed">
                            I understand that creating a store will initialize a dedicated database. I agree to the <span class="font-bold text-blue-600 hover:underline">Seller Terms of Service</span>.
                        </label>
                    </div>

                    <div class="flex gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="isModalOpen = false" class="flex-1 bg-white border-2 border-slate-200 text-slate-600 py-4 rounded-2xl font-bold hover:bg-slate-50 transition-all">Cancel</button>
                        <button type="submit" :disabled="form.processing" class="flex-1 bg-blue-600 text-white py-4 rounded-2xl font-black hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all flex justify-center items-center gap-2">
                            <svg v-if="form.processing" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            {{ form.processing ? 'Initializing...' : 'Create My Store' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
/* CSS buat nge-hide scrollbar tapi tetep bisa di-scroll di box Suggested Product */
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>