<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const { props } = usePage();

const myStores = ref([]);

const syncStores = () => {
    let stores = JSON.parse(localStorage.getItem('enterprise_nodes') || '[]');
    if (stores.length === 0 && props.status?.hasStore) {
        stores.push({
            id: props.status.storeName || 'active-1',
            name: props.status.storeDisplay || props.status.storeName,
            url: props.status.storeUrl,
            lastActivity: 'Active Now'
        });
        localStorage.setItem('enterprise_nodes', JSON.stringify(stores));
    }
    myStores.value = stores;
};

onMounted(() => {
    syncStores();
    window.addEventListener('nodes-updated', syncStores);
});

const revokeStore = (storeId) => {
    if (confirm('Are you sure you want to remove this store?')) {
        let stores = JSON.parse(localStorage.getItem('enterprise_nodes') || '[]');
        stores = stores.filter(s => s.id !== storeId);
        localStorage.setItem('enterprise_nodes', JSON.stringify(stores));
        window.dispatchEvent(new Event('nodes-updated'));
        myStores.value = stores;
    }
};

const isModalOpen = ref(false);
const currentStep = ref(1);
const extensions = ['.mystore.id', '.mystore.co', '.aweso.me', '.shop.me'];
const countries = [
    { code: '+1', name: 'US' },
    { code: '+62', name: 'ID' },
    { code: '+44', name: 'UK' }
];

const form = useForm({
    store_display_name: '',
    store_name: '',
    domain_extension: '.mystore.id',
    description: '',
    category: 'Fashion & Apparel',
    country_code: '+1',
    phone: '',
    address_detail: '',
    zip_code: '',
    agreement: false
});

const validateAndNext = () => {
    form.clearErrors();
    if (currentStep.value === 1 && (!form.store_display_name || !form.store_name)) {
        form.setError('store_display_name', 'Required: Please define your store identity.');
        return;
    }
    if (currentStep.value === 2 && !form.description) {
        form.setError('description', 'Required: Business description is mandatory.');
        return;
    }
    currentStep.value++;
};

const createStore = () => {
    form.post(route('central.store.create'), {
        onSuccess: (page) => {
            const newStatus = page.props.status;
            const stores = JSON.parse(localStorage.getItem('enterprise_nodes') || '[]');
            stores.push({
                id: form.store_name,
                name: form.store_display_name,
                url: newStatus?.storeUrl || `http://${form.store_name}.localhost:8000/products`,
                lastActivity: 'Just Deployed'
            });
            localStorage.setItem('enterprise_nodes', JSON.stringify(stores));
            window.dispatchEvent(new Event('nodes-updated'));
            isModalOpen.value = false;
            form.reset();
            currentStep.value = 1;
        },
        preserveScroll: true
    });
};

const formatUSD = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val);

const dummyCart = [
    { id: 1, name: 'Mechanical Keyboard K3 Edition Ultra', price: 89.99, qty: 1 },
    { id: 2, name: 'Arabica Coffee Beans 500g Premium', price: 12.50, qty: 2 },
];

const dummyHistory = [
    { id: '#INV-001', date: '12 Mar 2026', total: 45.00, status: 'Success' },
    { id: '#INV-002', date: '13 Mar 2026', total: 89.99, status: 'Pending' },
];

const suggested = [
    { id: 101, name: 'Minimalist Chair', price: 120.00 },
    { id: 102, name: 'Bluetooth Earbuds', price: 45.50 },
    { id: 103, name: 'Leather Notebook', price: 15.00 },
    { id: 104, name: 'Desk Lamp Pro', price: 29.99 },
];
</script>

<template>
    <Head title="Personal Hub" />
    <AuthenticatedLayout>
        <div class="max-w-6xl mx-auto space-y-10 pb-20">

            <header class="flex justify-between items-end">
                <div>
                    <h1 class="text-4xl font-black text-slate-900 tracking-tighter uppercase italic">Personal Hub</h1>
                    <p class="text-slate-500 mt-2 font-medium">Manage your shopping and business ventures in one place.</p>
                </div>
                <button @click="isModalOpen = true; currentStep = 1; form.clearErrors()" class="bg-blue-600 text-white px-8 py-3.5 rounded-2xl font-black shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all uppercase text-xs tracking-widest">
                    + Register New Store
                </button>
            </header>

            <section class="bg-white p-8 rounded-[3rem] shadow-sm border border-slate-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-blue-50 rounded-full blur-3xl -mr-20 -mt-20 opacity-40"></div>
                <h2 class="text-xl font-black text-slate-800 flex items-center gap-3 mb-8 pb-4 border-b border-slate-50 relative z-10">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Recent Shop Activity
                </h2>
                <div v-if="myStores.length > 0" class="space-y-4 relative z-10">
                    <div v-for="store in myStores" :key="store.id" class="flex flex-col md:flex-row md:items-center justify-between bg-slate-50/50 p-6 rounded-[2rem] border border-slate-100 hover:border-blue-200 transition-all gap-4">
                        <div class="flex items-center gap-5 min-w-0">
                            <div class="w-14 h-14 bg-blue-600 rounded-[1.25rem] flex items-center justify-center text-white text-xl font-black shadow-lg shadow-blue-100 uppercase flex-shrink-0">
                                {{ store.name.charAt(0) }}
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-black text-slate-900 text-lg uppercase tracking-tight truncate">{{ store.name }}</h3>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ store.lastActivity }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 w-full md:w-auto flex-shrink-0">
                            <button @click="revokeStore(store.id)" class="bg-red-50 text-red-500 p-3.5 rounded-2xl hover:bg-red-500 hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                            <a :href="store.url || `http://${store.id}.localhost:8000/products`" class="flex-1 md:flex-none bg-slate-900 text-white px-8 py-3.5 rounded-2xl font-black text-sm hover:bg-blue-600 transition-all flex items-center justify-center gap-2">
                                Open Console
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center py-12 bg-slate-50 rounded-[2.5rem] border-2 border-dashed border-slate-200">
                    <p class="text-slate-400 font-bold italic text-sm">No active stores detected.</p>
                </div>
            </section>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <section class="bg-white p-8 rounded-[3rem] shadow-sm border border-slate-100 flex flex-col">
                    <h2 class="text-xl font-black text-slate-800 flex items-center gap-3 mb-8">
                        <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        Shopping Cart
                    </h2>
                    <div class="space-y-4 flex-1">
                        <div v-for="item in dummyCart" :key="item.id" class="flex items-center gap-4 p-4 bg-slate-50 rounded-[1.5rem] border border-slate-100">
                            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center border border-slate-200 shadow-sm">
                                <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h4 class="font-bold text-slate-800 text-sm truncate uppercase">{{ item.name }}</h4>
                                <p class="text-blue-600 font-black text-xs mt-1">{{ formatUSD(item.price) }} <span class="text-slate-400 ml-1 font-bold">x{{ item.qty }}</span></p>
                            </div>
                        </div>
                    </div>
                    <Link :href="route('cart')" class="w-full py-4 mt-8 text-xs font-black uppercase text-blue-600 bg-blue-50/50 border-2 border-blue-100 rounded-2xl hover:bg-blue-600 hover:text-white transition-all text-center block tracking-widest">
                        View Cart Details
                    </Link>
                </section>

                <section class="bg-white p-8 rounded-[3rem] shadow-sm border border-slate-100 flex flex-col">
                    <h2 class="text-xl font-black text-slate-800 flex items-center gap-3 mb-8">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Recent History
                    </h2>
                    <div class="space-y-4 flex-1">
                        <div v-for="trx in dummyHistory" :key="trx.id" class="flex items-center justify-between p-5 bg-slate-50 rounded-2xl border border-slate-100 transition-all hover:bg-white">
                            <div>
                                <h4 class="font-black text-slate-800 text-sm uppercase">{{ trx.id }}</h4>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ trx.date }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-black text-slate-900 text-sm">{{ formatUSD(trx.total) }}</p>
                                <span class="text-green-500 bg-green-50 border border-green-100 px-2 py-0.5 rounded text-[10px] font-black uppercase mt-1 inline-block">{{ trx.status }}</span>
                            </div>
                        </div>
                    </div>
                    <button class="w-full py-4 mt-8 text-xs font-black uppercase text-slate-400 bg-slate-50/50 border-2 border-slate-100 rounded-2xl hover:bg-slate-900 hover:text-white transition-all block tracking-widest">
                        Transaction Ledger
                    </button>
                </section>
            </div>

            <section class="bg-white p-8 rounded-[3rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-xl font-black text-slate-800">Suggested For You</h2>
                    <Link :href="route('central.home')" class="text-xs font-black text-blue-600 uppercase tracking-widest hover:underline decoration-2 underline-offset-4">Marketplace &rarr;</Link>
                </div>
                <div class="flex overflow-x-auto gap-6 pb-4 scrollbar-hide snap-x">
                    <div v-for="item in suggested" :key="item.id" class="snap-start flex-shrink-0 w-44 group cursor-pointer">
                        <div class="aspect-square bg-slate-50 rounded-[2.5rem] border border-slate-100 flex items-center justify-center mb-4 group-hover:bg-blue-50 transition-all shadow-sm">
                            <svg class="w-16 h-16 text-slate-300 group-hover:text-blue-400 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <h4 class="font-bold text-slate-800 text-sm truncate uppercase">{{ item.name }}</h4>
                        <p class="text-blue-600 font-black text-sm mt-1">{{ formatUSD(item.price) }}</p>
                    </div>
                </div>
            </section>
        </div>

        <div v-if="isModalOpen" class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md">
            <div class="bg-white w-full max-w-xl rounded-[3rem] p-10 shadow-2xl animate-in zoom-in duration-300 relative overflow-hidden">
                <div class="absolute top-0 left-0 h-1.5 bg-blue-600 transition-all duration-500" :style="{ width: (currentStep/3 * 100) + '%' }"></div>
                <div class="flex justify-between items-start mb-10">
                    <div>
                        <h3 class="text-3xl font-black text-slate-900 uppercase tracking-tighter italic">Register Store</h3>
                        <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mt-2">Phase {{ currentStep }} of 3</p>
                    </div>
                    <button @click="isModalOpen = false" class="text-slate-300 hover:text-red-500 transition-colors">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form @submit.prevent="createStore" class="space-y-6">
                    <div v-if="currentStep === 1" class="space-y-6">
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Official Store Name</label>
                            <input v-model="form.store_display_name" type="text" placeholder="e.g. My Awesome Brand" class="w-full mt-3 bg-slate-50 border-slate-200 rounded-[1.5rem] py-4 px-6 font-bold outline-none shadow-sm focus:ring-2 focus:ring-blue-100 transition-all" />
                            <p v-if="form.errors.store_display_name" class="text-red-500 text-[10px] font-black uppercase mt-2 ml-1">{{ form.errors.store_display_name }}</p>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Domain Slug (URL)</label>
                            <div class="flex mt-3 shadow-sm">
                                <input v-model="form.store_name" type="text" placeholder="brand-slug" class="flex-1 bg-slate-50 border-slate-200 rounded-l-[1.5rem] py-4 px-6 font-black outline-none focus:ring-2 focus:ring-blue-100" />
                                <select v-model="form.domain_extension" class="bg-slate-100 border-y border-r border-slate-200 rounded-r-[1.5rem] px-5 font-black text-slate-600 outline-none cursor-pointer">
                                    <option v-for="ext in extensions" :key="ext" :value="ext">{{ ext }}</option>
                                </select>
                            </div>
                            <p v-if="form.errors.store_name" class="text-red-500 text-[10px] font-black uppercase mt-2 ml-1">{{ form.errors.store_name }}</p>
                        </div>
                        <button @click.prevent="validateAndNext" class="w-full bg-slate-900 text-white py-5 rounded-[1.5rem] font-black mt-4 hover:bg-blue-600 transition-all uppercase text-xs tracking-widest">Continue &rarr;</button>
                    </div>

                    <div v-if="currentStep === 2" class="space-y-6">
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Store Description</label>
                            <textarea v-model="form.description" rows="3" class="w-full mt-3 bg-slate-50 border-slate-200 rounded-[1.5rem] py-4 px-6 font-bold outline-none focus:ring-2 focus:ring-blue-100" placeholder="What are you selling?"></textarea>
                            <p v-if="form.errors.description" class="text-red-500 text-[10px] font-black uppercase mt-2 ml-1">{{ form.errors.description }}</p>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Industry Category</label>
                            <select v-model="form.category" class="w-full mt-3 bg-slate-50 border-slate-200 rounded-[1.5rem] py-4 px-6 font-black outline-none">
                                <option>Fashion & Apparel</option>
                                <option>Electronics</option>
                                <option>Home Living</option>
                                <option>Food & Beverages</option>
                            </select>
                        </div>
                        <div class="flex gap-4 pt-4">
                            <button @click.prevent="currentStep = 1" class="flex-1 bg-slate-100 text-slate-600 py-4 rounded-2xl font-black uppercase text-xs tracking-widest">Back</button>
                            <button @click.prevent="validateAndNext" class="flex-1 bg-slate-900 text-white py-4 rounded-2xl font-black uppercase text-xs tracking-widest">Continue</button>
                        </div>
                    </div>

                    <div v-if="currentStep === 3" class="space-y-6">
                        <div class="grid grid-cols-3 gap-3">
                            <div class="col-span-1">
                                <label class="text-[10px] font-black text-slate-400 uppercase ml-1">Code</label>
                                <select v-model="form.country_code" class="w-full mt-2 bg-slate-50 border-slate-200 rounded-2xl py-4 px-3 font-black outline-none">
                                    <option v-for="c in countries" :key="c.code" :value="c.code">{{ c.name }} ({{ c.code }})</option>
                                </select>
                            </div>
                            <div class="col-span-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase ml-1">Contact Number</label>
                                <input v-model="form.phone" type="text" placeholder="812345..." class="w-full mt-2 bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-black outline-none shadow-sm" />
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-3">
                            <div class="col-span-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase ml-1">Business Address</label>
                                <input v-model="form.address_detail" type="text" placeholder="Street, Number" class="w-full mt-2 bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 font-bold outline-none shadow-sm" />
                            </div>
                            <div class="col-span-1">
                                <label class="text-[10px] font-black text-slate-400 uppercase ml-1">Zip Code</label>
                                <input v-model="form.zip_code" type="text" placeholder="12345" class="w-full mt-2 bg-slate-50 border-slate-200 rounded-2xl py-4 px-4 font-bold outline-none shadow-sm" />
                            </div>
                        </div>
                        <div class="bg-blue-50/50 p-6 rounded-[2rem] border border-blue-100 flex items-start gap-4">
                            <input type="checkbox" v-model="form.agreement" id="final" class="mt-1 w-6 h-6 text-blue-600 rounded-lg cursor-pointer flex-shrink-0" />
                            <label for="final" class="text-[11px] text-slate-600 font-bold uppercase leading-relaxed cursor-pointer">I certify that all details provided are legitimate and I am authorized to launch.</label>
                        </div>
                        <div class="flex gap-4 pt-4">
                            <button @click.prevent="currentStep = 2" class="flex-1 bg-slate-100 text-slate-600 py-4 rounded-2xl font-black uppercase text-xs tracking-widest">Back</button>
                            <button type="submit" :disabled="form.processing" class="flex-1 bg-green-600 text-white py-4 rounded-2xl font-black uppercase text-xs shadow-lg shadow-green-100 flex items-center justify-center gap-2">Launch Store 🚀</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>
```
