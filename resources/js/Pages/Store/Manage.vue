<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, computed, onMounted, onUnmounted } from 'vue';

const { props } = usePage();
const storeName = props.status?.storeDisplay || props.status?.storeName || 'Store';

const activeTab = ref('products'); 
const formatUSD = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val);

// 1. DYNAMIC TOP NOTIFICATION
const showTopNotification = ref(true);
const latestNotification = ref({
    type: 'Action Required',
    message: 'You have 1 new pending order awaiting review.'
});

// 2. LOGISTICS & ORDER ACTIVITY FEED (E-Commerce Focused)
const logFilter = ref('all'); // all, new, packing, delivery, clear, hold, refund

const activityFeed = ref([
    { id: 1, category: 'new', title: 'New Order Received', desc: 'Order #ORD-9921 needs review and packing.', time: '10 mins ago', icon: 'new', color: 'text-blue-600', bg: 'bg-blue-50' },
    { id: 2, category: 'packing', title: 'Order On Packing', desc: 'Order #ORD-9920 is being prepared for dispatch.', time: '1 hour ago', icon: 'packing', color: 'text-orange-500', bg: 'bg-orange-50' },
    { id: 3, category: 'delivery', title: 'On Delivery', desc: 'Order #ORD-9915 is currently out for delivery.', time: '3 hours ago', icon: 'delivery', color: 'text-indigo-500', bg: 'bg-indigo-50' },
    { id: 4, category: 'clear', title: 'Delivered & Cleared', desc: 'Order #ORD-9901 has been successfully fulfilled.', time: '1 day ago', icon: 'clear', color: 'text-green-600', bg: 'bg-green-50' },
    { id: 5, category: 'hold', title: 'Order On Hold', desc: 'Order #ORD-9899 is pending customer confirmation.', time: '2 days ago', icon: 'hold', color: 'text-yellow-600', bg: 'bg-yellow-50' },
    { id: 6, category: 'refund', title: 'Refund Processed', desc: 'Order #ORD-9880 cancellation has been fulfilled.', time: '3 days ago', icon: 'refund', color: 'text-red-500', bg: 'bg-red-50' }
]);

const filteredFeed = computed(() => {
    if (logFilter.value === 'all') return activityFeed.value;
    return activityFeed.value.filter(log => log.category === logFilter.value);
});

// 3. ORDER MANAGEMENT DATA
const storeOrders = ref([
    { id: '#ORD-9921', customer: 'Alice Johnson', date: 'Oct 24, 2026', total: 120.00, status: 'New Order' },
    { id: '#ORD-9905', customer: 'Bob Smith', date: 'Oct 23, 2026', total: 89.99, status: 'On Delivery' },
]);

// 4. INVENTORY DATA
const storeProducts = ref([]);

// MODALS STATE
const isAddEditModalOpen = ref(false);
const isPreviewModalOpen = ref(false);
const isDeleteModal1Open = ref(false);
const isDeleteModal2Open = ref(false);

// ACTION DROPDOWN LOGIC
const activeDropdown = ref(null);
const activeOrderDropdown = ref(null);

const toggleDropdown = (id) => { activeDropdown.value = activeDropdown.value === id ? null : id; activeOrderDropdown.value = null; };
const toggleOrderDropdown = (id) => { activeOrderDropdown.value = activeOrderDropdown.value === id ? null : id; activeDropdown.value = null; };
const closeDropdowns = () => { activeDropdown.value = null; activeOrderDropdown.value = null; };

onMounted(() => document.addEventListener('click', closeDropdowns));
onUnmounted(() => document.removeEventListener('click', closeDropdowns));

// CRUD STATES
const formMode = ref('add'); 
const selectedProduct = ref(null);
const productForm = ref({ id: null, name: '', stock: 0, price: 0, status: 'Active', desc: '' });

// ACTIONS
const openAddModal = () => {
    formMode.value = 'add';
    productForm.value = { id: null, name: '', stock: 0, price: 0, status: 'Active', desc: '' };
    isAddEditModalOpen.value = true;
};

const openEditModal = (product) => {
    formMode.value = 'edit';
    productForm.value = { ...product };
    isAddEditModalOpen.value = true;
};

const saveProduct = () => {
    if (!productForm.value.name) return;
    if (formMode.value === 'add') {
        storeProducts.value.push({ ...productForm.value, id: Date.now() });
    } else {
        const index = storeProducts.value.findIndex(p => p.id === productForm.value.id);
        if (index !== -1) storeProducts.value[index] = { ...productForm.value };
    }
    isAddEditModalOpen.value = false;
};

const togglePause = (product) => { product.status = product.status === 'Active' ? 'On Hold' : 'Active'; };
const openPreview = (product) => { selectedProduct.value = product; isPreviewModalOpen.value = true; };

// DOUBLE DELETE CONFIRMATION
const itemToDelete = ref(null);
const triggerDelete1 = (product) => { itemToDelete.value = product; isDeleteModal1Open.value = true; };
const triggerDelete2 = () => { isDeleteModal1Open.value = false; isDeleteModal2Open.value = true; };
const confirmDelete = () => {
    storeProducts.value = storeProducts.value.filter(p => p.id !== itemToDelete.value.id);
    isDeleteModal2Open.value = false;
    itemToDelete.value = null;
};
</script>

<template>
    <Head :title="storeName + ' Console'" />

    <AuthenticatedLayout>
        <div class="max-w-6xl mx-auto space-y-8 pb-10 relative">
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4 min-w-0">
                    <Link :href="route('dashboard')" class="bg-white border border-slate-200 p-3 rounded-2xl hover:bg-slate-50 shadow-sm flex-shrink-0"><svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg></Link>
                    <div class="min-w-0"><h1 class="text-3xl font-black text-slate-900 uppercase tracking-tight truncate">{{ storeName }} Console</h1><p class="text-slate-400 font-bold text-[10px] uppercase tracking-[0.2em] mt-1">Status: Operational</p></div>
                </div>
                <button v-if="activeTab === 'products'" @click="openAddModal" class="bg-blue-600 text-white px-8 py-4 rounded-2xl font-black shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all flex items-center justify-center gap-2 flex-shrink-0 w-full md:w-auto"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg> Add Product</button>
            </div>

            <div v-if="showTopNotification" class="bg-blue-50 border border-blue-100 rounded-[2rem] p-5 flex items-center justify-between shadow-sm animate-in fade-in slide-in-from-top-4">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center flex-shrink-0 text-blue-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg></div>
                    <div><h4 class="text-sm font-black text-blue-900 uppercase tracking-tight">{{ latestNotification.type }}</h4><p class="text-xs font-bold text-blue-700">{{ latestNotification.message }}</p></div>
                </div>
                <button @click="showTopNotification = false" class="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:underline bg-white px-4 py-2 rounded-lg transition-colors">Dismiss</button>
            </div>

            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden p-6 md:p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-sm font-black text-slate-800 uppercase tracking-widest flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Financial & Income Overview
                    </h2>
                    <button class="text-[10px] font-black text-white uppercase tracking-widest hover:bg-slate-800 bg-slate-900 px-4 py-2 rounded-lg transition-colors">Withdraw Funds</button>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="p-5 bg-slate-50 rounded-[1.5rem] border border-slate-100">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Income</p>
                        <p class="text-xl font-black text-slate-900">{{ formatUSD(12500) }}</p>
                    </div>
                    <div class="p-5 bg-green-50 rounded-[1.5rem] border border-green-100">
                        <p class="text-[10px] font-black text-green-600 uppercase tracking-widest mb-1">Available Balance</p>
                        <p class="text-xl font-black text-green-700">{{ formatUSD(4200) }}</p>
                    </div>
                    <div class="p-5 bg-orange-50 rounded-[1.5rem] border border-orange-100">
                        <p class="text-[10px] font-black text-orange-600 uppercase tracking-widest mb-1">Pending Clearance</p>
                        <p class="text-xl font-black text-orange-700">{{ formatUSD(850) }}</p>
                    </div>
                    <div class="p-5 bg-red-50 rounded-[1.5rem] border border-red-100">
                        <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mb-1">Total Refunded</p>
                        <p class="text-xl font-black text-red-700">{{ formatUSD(120) }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-100 flex flex-col justify-center">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Lifetime Items Sold</p>
                    <p class="text-3xl font-black text-slate-900">1,024 <span class="text-sm text-slate-400">Units</span></p>
                </div>
                <div class="bg-slate-900 p-6 rounded-[2.5rem] shadow-sm flex flex-col justify-between relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Sales Performance (30 Days)</p>
                        <p class="text-xl font-black text-white">+15.4% Growth</p>
                    </div>
                    <svg class="absolute bottom-0 left-0 w-full h-1/2 text-blue-500 opacity-50" viewBox="0 0 100 30" preserveAspectRatio="none"><path d="M0,30 L20,15 L40,25 L60,5 L80,15 L100,0 L100,30 Z" fill="currentColor"></path></svg>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-50 flex flex-col xl:flex-row xl:items-center justify-between bg-slate-50/50 gap-4">
                    <h2 class="text-sm font-black text-slate-800 uppercase tracking-widest flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                        Logistics & Order Activity
                    </h2>
                    
                    <div class="flex flex-wrap bg-white border border-slate-200 rounded-xl p-1 shadow-sm gap-1">
                        <button @click="logFilter = 'all'" :class="logFilter === 'all' ? 'bg-slate-900 text-white' : 'text-slate-500 hover:bg-slate-50'" class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all">All</button>
                        <button @click="logFilter = 'new'" :class="logFilter === 'new' ? 'bg-blue-100 text-blue-700' : 'text-slate-500 hover:bg-slate-50'" class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all">New Order</button>
                        <button @click="logFilter = 'packing'" :class="logFilter === 'packing' ? 'bg-orange-100 text-orange-700' : 'text-slate-500 hover:bg-slate-50'" class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all">On Packing</button>
                        <button @click="logFilter = 'delivery'" :class="logFilter === 'delivery' ? 'bg-indigo-100 text-indigo-700' : 'text-slate-500 hover:bg-slate-50'" class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all">On Delivery</button>
                        <button @click="logFilter = 'clear'" :class="logFilter === 'clear' ? 'bg-green-100 text-green-700' : 'text-slate-500 hover:bg-slate-50'" class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all">Clear/Sent</button>
                        <button @click="logFilter = 'hold'" :class="logFilter === 'hold' ? 'bg-yellow-100 text-yellow-700' : 'text-slate-500 hover:bg-slate-50'" class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all">Hold</button>
                        <button @click="logFilter = 'refund'" :class="logFilter === 'refund' ? 'bg-red-100 text-red-700' : 'text-slate-500 hover:bg-slate-50'" class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all">Refund</button>
                    </div>
                </div>
                
                <div class="max-h-80 overflow-y-auto p-2 scrollbar-hide">
                    <div v-if="filteredFeed.length === 0" class="p-8 text-center text-slate-400 font-bold italic text-sm">No logistics records found for this filter.</div>
                    <div v-for="log in filteredFeed" :key="log.id" class="flex items-center justify-between p-4 hover:bg-slate-50 rounded-[1.5rem] transition-colors cursor-pointer border-b border-slate-50 last:border-0 animate-in fade-in duration-300">
                        <div class="flex items-center gap-4">
                            <div :class="[log.bg, log.color]" class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0">
                                <svg v-if="log.icon === 'new'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                                <svg v-else-if="log.icon === 'packing'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                <svg v-else-if="log.icon === 'delivery'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                <svg v-else-if="log.icon === 'clear'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                <svg v-else-if="log.icon === 'hold'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-black text-slate-900 tracking-tight">{{ log.title }}</h4>
                                <p class="text-[11px] text-slate-500 font-bold mt-1">{{ log.desc }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">{{ log.time }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[3rem] border border-slate-100 shadow-sm overflow-hidden">
                <div class="flex border-b border-slate-100">
                    <button @click="activeTab = 'products'" :class="activeTab === 'products' ? 'text-blue-600 border-blue-600 bg-blue-50/50' : 'text-slate-400 border-transparent hover:bg-slate-50'" class="flex-1 py-6 font-black text-xs uppercase tracking-widest border-b-4 transition-all">Inventory Control</button>
                    <button @click="activeTab = 'orders'" :class="activeTab === 'orders' ? 'text-blue-600 border-blue-600 bg-blue-50/50' : 'text-slate-400 border-transparent hover:bg-slate-50'" class="flex-1 py-6 font-black text-xs uppercase tracking-widest border-b-4 transition-all">Order Management</button>
                </div>
                
                <div class="p-8 md:p-12 min-h-[400px]">
                    
                    <div v-if="activeTab === 'products'" class="space-y-6 animate-in slide-in-from-left-4 duration-300">
                        <table v-if="storeProducts.length > 0" class="w-full border-separate border-spacing-y-3 relative">
                            <thead><tr class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] px-4"><th class="pb-4 text-left pl-6">Entity</th><th class="pb-4 text-left">Status</th><th class="pb-4 text-left">Stock</th><th class="pb-4 text-left">Price</th><th class="pb-4 text-right pr-6">Options</th></tr></thead>
                            <tbody>
                                <tr v-for="p in storeProducts" :key="p.id" @click="openPreview(p)" class="cursor-pointer group">
                                    <td class="bg-slate-50 rounded-l-[1.5rem] py-5 pl-6 font-bold uppercase text-sm group-hover:bg-slate-100 transition-colors">{{ p.name }}</td>
                                    <td class="bg-slate-50 py-5 group-hover:bg-slate-100 transition-colors">
                                        <span :class="p.status === 'Active' ? 'text-green-600 bg-green-100' : 'text-orange-600 bg-orange-100'" class="px-3 py-1 rounded-lg font-black text-[10px] uppercase">{{ p.status }}</span>
                                    </td>
                                    <td class="bg-slate-50 py-5 group-hover:bg-slate-100 transition-colors"><span class="px-3 py-1 bg-white border border-slate-200 rounded-lg font-black text-[10px]">{{ p.stock }} PCS</span></td>
                                    <td class="bg-slate-50 py-5 font-black text-slate-900 group-hover:bg-slate-100 transition-colors">{{ formatUSD(p.price) }}</td>
                                    <td class="bg-slate-50 rounded-r-[1.5rem] py-5 pr-6 text-right group-hover:bg-slate-100 transition-colors relative">
                                        <button @click.stop="toggleDropdown(p.id)" class="p-2 text-slate-400 hover:text-blue-600 rounded-xl hover:bg-white transition-all shadow-sm bg-white border border-slate-100">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                                        </button>
                                        <div v-if="activeDropdown === p.id" class="absolute right-8 top-12 w-44 bg-white border border-slate-100 rounded-2xl shadow-2xl z-20 py-2 flex flex-col text-left animate-in slide-in-from-top-2">
                                            <button @click.stop="openEditModal(p); closeDropdowns()" class="px-5 py-3 text-[11px] font-black text-slate-700 hover:bg-slate-50 hover:text-blue-600 uppercase tracking-widest text-left">Edit Details</button>
                                            <button @click.stop="togglePause(p); closeDropdowns()" class="px-5 py-3 text-[11px] font-black text-slate-700 hover:bg-slate-50 hover:text-orange-600 uppercase tracking-widest text-left">{{ p.status === 'Active' ? 'Hold / Pause' : 'Activate Entity' }}</button>
                                            <div class="h-px bg-slate-100 my-1 mx-2"></div>
                                            <button @click.stop="triggerDelete1(p); closeDropdowns()" class="px-5 py-3 text-[11px] font-black text-red-500 hover:bg-red-50 uppercase tracking-widest text-left">Purge Item</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div v-else class="text-center py-20">
                            <div class="w-20 h-20 bg-slate-50 rounded-[2rem] flex items-center justify-center border-2 border-dashed border-slate-200 mx-auto mb-6"><svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg></div>
                            <h3 class="text-xl font-black text-slate-800 uppercase tracking-tight">Inventory Empty</h3>
                            <p class="text-slate-400 text-sm mt-2 max-w-xs mx-auto">No products have been registered to this console.</p>
                        </div>
                    </div>

                    <div v-if="activeTab === 'orders'" class="space-y-6 animate-in slide-in-from-right-4 duration-300">
                        <table class="w-full border-separate border-spacing-y-3 relative">
                            <thead><tr class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] px-4"><th class="pb-4 text-left pl-6">Order ID</th><th class="pb-4 text-left">Customer</th><th class="pb-4 text-left">Total</th><th class="pb-4 text-left">Status</th><th class="pb-4 text-right pr-6">Action</th></tr></thead>
                            <tbody>
                                <tr v-for="o in storeOrders" :key="o.id" class="group">
                                    <td class="bg-slate-50 rounded-l-[1.5rem] py-5 pl-6 font-black uppercase text-sm group-hover:bg-slate-100 transition-colors">{{ o.id }}</td>
                                    <td class="bg-slate-50 py-5 font-bold text-slate-700 text-sm group-hover:bg-slate-100 transition-colors">{{ o.customer }}</td>
                                    <td class="bg-slate-50 py-5 font-black text-slate-900 group-hover:bg-slate-100 transition-colors">{{ formatUSD(o.total) }}</td>
                                    <td class="bg-slate-50 py-5 group-hover:bg-slate-100 transition-colors">
                                        <span :class="o.status === 'New Order' ? 'text-blue-600 bg-blue-100' : 'text-green-600 bg-green-100'" class="px-3 py-1 rounded-lg font-black text-[10px] uppercase">{{ o.status }}</span>
                                    </td>
                                    <td class="bg-slate-50 rounded-r-[1.5rem] py-5 pr-6 text-right group-hover:bg-slate-100 transition-colors relative">
                                        <button @click.stop="toggleOrderDropdown(o.id)" class="p-2 text-slate-400 hover:text-blue-600 rounded-xl hover:bg-white transition-all shadow-sm bg-white border border-slate-100">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                                        </button>
                                        <div v-if="activeOrderDropdown === o.id" class="absolute right-8 top-12 w-44 bg-white border border-slate-100 rounded-2xl shadow-2xl z-20 py-2 flex flex-col text-left animate-in slide-in-from-top-2">
                                            <button @click="closeDropdowns" class="px-5 py-3 text-[11px] font-black text-slate-700 hover:bg-slate-50 hover:text-blue-600 uppercase tracking-widest text-left">View Details</button>
                                            <button @click="closeDropdowns" class="px-5 py-3 text-[11px] font-black text-slate-700 hover:bg-slate-50 hover:text-orange-600 uppercase tracking-widest text-left">Mark Packing</button>
                                            <button @click="closeDropdowns" class="px-5 py-3 text-[11px] font-black text-slate-700 hover:bg-slate-50 hover:text-indigo-600 uppercase tracking-widest text-left">Mark Delivery</button>
                                            <button @click="closeDropdowns" class="px-5 py-3 text-[11px] font-black text-slate-700 hover:bg-slate-50 hover:text-green-600 uppercase tracking-widest text-left">Mark Cleared</button>
                                            <div class="h-px bg-slate-100 my-1 mx-2"></div>
                                            <button @click="closeDropdowns" class="px-5 py-3 text-[11px] font-black text-red-500 hover:bg-red-50 uppercase tracking-widest text-left">Issue Refund</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

        <div v-if="isPreviewModalOpen" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md" @click.self="isPreviewModalOpen = false">
            <div class="bg-white w-full max-w-md rounded-[3rem] p-10 shadow-2xl animate-in zoom-in duration-300 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-32 bg-slate-50 border-b border-slate-100"></div>
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-20 h-20 bg-white rounded-[1.5rem] flex items-center justify-center border border-slate-200 shadow-sm"><svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg></div>
                        <button @click="isPreviewModalOpen = false" class="text-slate-300 hover:text-red-500 bg-white p-2 rounded-full shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    </div>
                    <span :class="selectedProduct.status === 'Active' ? 'text-green-600 bg-green-100' : 'text-orange-600 bg-orange-100'" class="px-3 py-1 rounded-lg font-black text-[10px] uppercase mb-3 inline-block">{{ selectedProduct.status }}</span>
                    <h3 class="text-2xl font-black text-slate-900 uppercase tracking-tighter">{{ selectedProduct.name }}</h3>
                    <p class="text-3xl font-black text-blue-600 mt-2">{{ formatUSD(selectedProduct.price) }}</p>
                    
                    <div class="mt-8 space-y-4">
                        <div><p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Available Stock</p><p class="font-bold text-slate-800">{{ selectedProduct.stock }} Units</p></div>
                        <div><p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Description</p><p class="text-sm text-slate-600 leading-relaxed font-medium">{{ selectedProduct.desc || 'No detailed description provided for this item.' }}</p></div>
                    </div>
                    <button @click="isPreviewModalOpen = false; openEditModal(selectedProduct)" class="w-full bg-slate-900 text-white py-4 rounded-2xl font-black mt-8 hover:bg-blue-600 shadow-lg uppercase text-xs tracking-widest">Edit Information</button>
                </div>
            </div>
        </div>

        <div v-if="isAddEditModalOpen" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md">
            <div class="bg-white w-full max-w-md rounded-[3rem] p-10 shadow-2xl animate-in zoom-in duration-300">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-2xl font-black text-slate-900 uppercase tracking-tighter">{{ formMode === 'add' ? 'Register Product' : 'Edit Product' }}</h3>
                    <button @click="isAddEditModalOpen = false" class="text-slate-300 hover:text-red-500"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
                <div class="space-y-6">
                    <div><label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Entity Name</label><input v-model="productForm.name" type="text" class="w-full mt-2 bg-slate-50 border-slate-200 rounded-[1.25rem] py-4 px-5 font-bold outline-none focus:ring-2 focus:ring-blue-100" /></div>
                    <div><label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Description</label><textarea v-model="productForm.desc" rows="2" class="w-full mt-2 bg-slate-50 border-slate-200 rounded-[1.25rem] py-4 px-5 font-bold outline-none focus:ring-2 focus:ring-blue-100"></textarea></div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="text-[10px] font-black text-slate-400 uppercase ml-1">Quantity</label><input v-model="productForm.stock" type="number" class="w-full mt-2 bg-slate-50 border-slate-200 rounded-[1.25rem] py-4 px-5 font-bold outline-none focus:ring-2 focus:ring-blue-100" /></div>
                        <div><label class="text-[10px] font-black text-slate-400 uppercase ml-1">Unit Price (USD)</label><input v-model="productForm.price" type="number" step="0.01" class="w-full mt-2 bg-slate-50 border-slate-200 rounded-[1.25rem] py-4 px-5 font-bold outline-none focus:ring-2 focus:ring-blue-100" /></div>
                    </div>
                    <button @click="saveProduct" class="w-full bg-blue-600 text-white py-4 rounded-2xl font-black mt-4 hover:bg-blue-700 shadow-lg shadow-blue-100 uppercase text-xs tracking-widest">{{ formMode === 'add' ? 'Commit Entry' : 'Save Changes' }}</button>
                </div>
            </div>
        </div>

        <div v-if="isDeleteModal1Open" class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white w-full max-w-sm rounded-[2.5rem] p-8 shadow-2xl animate-in zoom-in duration-200 text-center">
                <div class="w-16 h-16 bg-orange-50 text-orange-500 rounded-full flex items-center justify-center mx-auto mb-4"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg></div>
                <h3 class="text-lg font-black text-slate-900 uppercase">Warning: Deletion</h3>
                <p class="text-sm text-slate-500 mt-2 font-medium">Are you sure you want to remove <span class="font-bold text-slate-900">{{ itemToDelete?.name }}</span> from the catalog?</p>
                <div class="flex gap-3 mt-8">
                    <button @click="isDeleteModal1Open = false" class="flex-1 py-3.5 bg-slate-100 text-slate-600 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-slate-200">Cancel</button>
                    <button @click="triggerDelete2" class="flex-1 py-3.5 bg-orange-500 text-white rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-orange-600 shadow-lg shadow-orange-100">Proceed</button>
                </div>
            </div>
        </div>

        <div v-if="isDeleteModal2Open" class="fixed inset-0 z-[80] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-md">
            <div class="bg-white w-full max-w-sm rounded-[2.5rem] p-8 shadow-2xl animate-in zoom-in duration-200 text-center border-4 border-red-50">
                <div class="w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></div>
                <h3 class="text-lg font-black text-red-600 uppercase">Final Authorization</h3>
                <p class="text-sm text-slate-500 mt-2 font-medium">This action is irreversible. The entity will be permanently purged.</p>
                <div class="flex flex-col gap-3 mt-8">
                    <button @click="confirmDelete" class="w-full py-4 bg-red-600 text-white rounded-2xl font-black uppercase text-xs tracking-widest hover:bg-red-700 shadow-lg shadow-red-200">Purge Permanently</button>
                    <button @click="isDeleteModal2Open = false" class="w-full py-3.5 text-slate-400 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:text-slate-600">Abort Operation</button>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
<style>
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>