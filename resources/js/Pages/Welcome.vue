<script setup>
import { Head, Link, usePage, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const { props } = usePage();

const products = [
    { id: 1, name: 'Mechanical Keyboard K3', price: 1200000, store: 'TechStore' },
    { id: 2, name: 'Arabica Coffee Beans 500g', price: 150000, store: 'KopiSenja' },
    { id: 3, name: 'Wireless Gaming Mouse', price: 650000, store: 'GamingRig' },
    { id: 4, name: 'Minimalist Office Desk', price: 2100000, store: 'WoodWork' },
];

const selectedProduct = ref(null);
const isDetailModalOpen = ref(false);
const isCheckoutModalOpen = ref(false);
const showLoginModal = ref(false);
const showRegisterModal = ref(false);

const purchaseForm = ref({ quantity: 1, variant: 'Standard Edition', note: '' });
const checkoutForm = ref({ fullName: '', address: '', shipping: 'Reguler (Rp 15.000)', payment: 'Bank Transfer' });

const loginForm = useForm({ email: '', password: '', remember: false });
const registerForm = useForm({ name: '', email: '', password: '', password_confirmation: '' });

const openDetail = (product) => {
    selectedProduct.value = product;
    purchaseForm.value.quantity = 1;
    purchaseForm.value.note = '';
    isDetailModalOpen.value = true;
};

const addToCart = () => {
    if (props.auth && props.auth.user) {
        alert('Yeay! Item added to cart 🛒');
        isDetailModalOpen.value = false;
    } else {
        showLoginModal.value = true;
    }
};

const proceedToCheckout = () => {
    if (props.auth && props.auth.user) {
        checkoutForm.value.fullName = props.auth.user.name;
        isCheckoutModalOpen.value = true;
    } else {
        showLoginModal.value = true;
    }
};

const confirmOrder = () => {
    alert('🎉 Order placed successfully!');
    isCheckoutModalOpen.value = false;
    isDetailModalOpen.value = false;
};

const submitLogin = () => {
    loginForm.post(route('login'), {
        preserveScroll: true,
        onSuccess: () => {
            showLoginModal.value = false;
            window.location.href = route('dashboard');
        },
        onFinish: () => loginForm.reset('password'),
    });
};

const submitRegister = () => {
    registerForm.post(route('register'), {
        preserveScroll: true,
        onSuccess: () => {
            showRegisterModal.value = false;
            window.location.href = route('dashboard');
        },
        onFinish: () => registerForm.reset('password', 'password_confirmation'),
    });
};

const switchToRegister = () => { showLoginModal.value = false; showRegisterModal.value = true; };
const switchToLogin = () => { showRegisterModal.value = false; showLoginModal.value = true; };
</script>

<template>
    <Head title="Welcome - MyStore Discovery" />

    <div class="min-h-screen bg-slate-50 text-slate-900 font-sans pb-20">
        <nav class="bg-white/90 backdrop-blur-md border-b border-slate-100 sticky top-0 z-40 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 h-20 flex items-center justify-between break-words">
                <Link :href="route('central.home')" class="text-xl sm:text-2xl font-black text-blue-600 tracking-tighter flex items-center gap-2">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <span class="truncate">MyStore</span>
                </Link>

                <div class="flex items-center gap-2 sm:gap-4">
                    <template v-if="$page.props.auth.user">
                        <Link :href="route('dashboard')" class="bg-slate-900 text-white px-4 sm:px-6 py-2 sm:py-2.5 rounded-xl font-bold shadow-lg hover:bg-blue-600 transition-all text-sm sm:text-base text-center">Dashboard</Link>
                    </template>
                    <template v-else>
                        <button @click="showLoginModal = true" class="font-bold text-slate-600 hover:text-blue-600 px-2 sm:px-4 text-sm sm:text-base">Sign In</button>
                        <button @click="showRegisterModal = true" class="bg-slate-900 text-white px-4 sm:px-6 py-2 sm:py-2.5 rounded-xl font-bold hover:bg-blue-600 transition-all text-sm sm:text-base whitespace-nowrap">Sign Up</button>
                    </template>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
            <h1 class="text-3xl sm:text-4xl font-black mb-8 text-slate-900">Marketplace Discovery</h1>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
                <div v-for="product in products" :key="product.id"
                    @click="openDetail(product)"
                    class="group cursor-pointer bg-white p-4 rounded-[2.5rem] shadow-sm border border-transparent hover:border-blue-200 hover:shadow-xl transition-all flex flex-col min-w-0">
                    <div class="aspect-square rounded-[2rem] overflow-hidden bg-slate-50 mb-4 flex items-center justify-center relative group-hover:bg-blue-50 transition-colors flex-shrink-0">
                        <svg class="w-20 h-20 sm:w-24 sm:h-24 text-slate-300 group-hover:text-blue-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-blue-500 mb-1">{{ product.store }}</p>
                    <h3 class="font-bold text-lg text-slate-800 mb-2 line-clamp-2">{{ product.name }}</h3>
                    <p class="text-xl font-black text-slate-900 mt-auto">Rp {{ product.price.toLocaleString('id-ID') }}</p>
                </div>
            </div>
        </main>

        <div v-if="isDetailModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" @click.self="isDetailModalOpen = false">
            <div class="bg-white w-full max-w-4xl rounded-[2rem] sm:rounded-[3rem] overflow-hidden shadow-2xl flex flex-col md:flex-row animate-in zoom-in duration-300 max-h-[95vh] overflow-y-auto">
                <div class="md:w-1/2 bg-slate-50 p-6 sm:p-10 flex flex-col justify-between border-b md:border-b-0 md:border-r border-slate-100 min-w-0">
                    <div>
                        <div class="aspect-square bg-white rounded-3xl border border-slate-100 flex items-center justify-center mb-6 shadow-sm">
                            <svg class="w-24 h-24 sm:w-32 sm:h-32 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 mb-2">{{ selectedProduct.name }}</h2>
                        <p class="text-blue-600 font-bold text-sm tracking-wide mb-4">STORE: {{ selectedProduct.store }}</p>
                        <div class="prose prose-sm text-slate-500">
                            <p>Premium quality product directly from the seller. Guaranteed authentic and comes with full warranty.</p>
                            <ul class="mt-4 space-y-1 pl-4">
                                <li>Weight: 500g</li>
                                <li>Condition: Brand New</li>
                                <li>Category: General Goods</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="md:w-1/2 p-6 sm:p-10 flex flex-col relative min-w-0">
                    <button @click="isDetailModalOpen = false" class="absolute top-4 right-4 sm:top-6 sm:right-8 text-slate-400 font-bold hover:text-red-500 flex items-center gap-1 bg-white rounded-full p-1 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>

                    <h3 class="text-lg sm:text-xl font-black text-slate-900 mb-4 sm:mb-6 border-b border-slate-100 pb-4 pr-8">Purchase Details</h3>

                    <div class="space-y-4 sm:space-y-5 flex-1">
                        <div>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Unit Price</p>
                            <p class="text-2xl sm:text-3xl font-black text-blue-600">Rp {{ selectedProduct.price.toLocaleString('id-ID') }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Select Variant/Size</label>
                            <select v-model="purchaseForm.variant" class="w-full mt-1 bg-slate-50 border-slate-200 rounded-2xl py-3 px-4 font-bold focus:ring-blue-500 outline-none">
                                <option>Standard Edition</option>
                                <option>Pro Edition (+ Rp 50.000)</option>
                                <option>Limited Color</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Quantity</label>
                            <div class="flex items-center gap-2 sm:gap-4 mt-1">
                                <button @click="purchaseForm.quantity > 1 ? purchaseForm.quantity-- : 1" class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-slate-100 font-black text-xl hover:bg-slate-200 transition-colors flex-shrink-0">-</button>
                                <input v-model="purchaseForm.quantity" type="number" class="w-16 sm:w-20 text-center border border-slate-200 rounded-xl sm:rounded-2xl py-2 sm:py-3 font-black text-lg focus:ring-blue-500 outline-none min-w-0" min="1" />
                                <button @click="purchaseForm.quantity++" class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-slate-100 font-black text-xl hover:bg-slate-200 transition-colors flex-shrink-0">+</button>
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Notes for Seller</label>
                            <textarea v-model="purchaseForm.note" rows="2" placeholder="e.g. Please pack safely..." class="w-full mt-1 bg-slate-50 border-slate-200 rounded-2xl py-3 px-4 font-bold focus:ring-blue-500 outline-none"></textarea>
                        </div>
                    </div>

                    <div class="pt-4 sm:pt-6 border-t border-slate-100 mt-4 sm:mt-6 flex flex-col sm:flex-row gap-3 sm:gap-4">
                        <button @click="addToCart" class="flex-1 bg-white text-slate-700 py-3 sm:py-4 rounded-2xl font-black hover:bg-slate-50 transition-all border-2 border-slate-200 text-sm sm:text-base">Add to Cart</button>
                        <button @click="proceedToCheckout" class="flex-1 bg-blue-600 text-white py-3 sm:py-4 rounded-2xl font-black hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all flex justify-center items-center gap-2 text-sm sm:text-base">Buy Now 🛒</button>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="isCheckoutModalOpen" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-md" @click.self="isCheckoutModalOpen = false">
            <div class="bg-white w-full max-w-xl rounded-[2rem] sm:rounded-[3rem] p-6 sm:p-10 shadow-2xl animate-in zoom-in duration-300 max-h-[95vh] overflow-y-auto min-w-0">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 flex items-center gap-2 pr-4">
                        <svg class="w-6 h-6 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Checkout
                    </h2>
                    <button @click="isCheckoutModalOpen = false" class="text-slate-400 hover:text-red-500 flex-shrink-0"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
                <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 sm:p-5 mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 sm:gap-4 min-w-0">
                    <div class="min-w-0">
                        <p class="text-[10px] sm:text-xs font-bold text-blue-600 uppercase tracking-widest mb-1">Order Summary</p>
                        <p class="font-black text-slate-800 line-clamp-2">{{ selectedProduct.name }} (x{{ purchaseForm.quantity }})</p>
                    </div>
                    <p class="font-black text-lg sm:text-xl text-blue-700 whitespace-nowrap">Rp {{ (selectedProduct.price * purchaseForm.quantity).toLocaleString('id-ID') }}</p>
                </div>
                <form @submit.prevent="confirmOrder" class="space-y-4 sm:space-y-5 min-w-0">
                    <div>
                        <label class="text-[10px] sm:text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Recipient Name</label>
                        <input v-model="checkoutForm.fullName" type="text" class="w-full mt-1 bg-slate-50 border-slate-200 rounded-2xl py-3 px-4 font-bold focus:ring-blue-500 outline-none min-w-0" required />
                    </div>
                    <div>
                        <label class="text-[10px] sm:text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Full Delivery Address</label>
                        <textarea v-model="checkoutForm.address" rows="3" placeholder="Street, City, Zip Code..." class="w-full mt-1 bg-slate-50 border-slate-200 rounded-2xl py-3 px-4 font-bold focus:ring-blue-500 outline-none min-w-0" required></textarea>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 min-w-0">
                        <div>
                            <label class="text-[10px] sm:text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Shipping</label>
                            <select v-model="checkoutForm.shipping" class="w-full mt-1 bg-slate-50 border-slate-200 rounded-2xl py-3 px-4 font-bold focus:ring-blue-500 outline-none min-w-0">
                                <option>Reguler (Rp 15.000)</option>
                                <option>Express (Rp 30.000)</option>
                                <option>Cargo (Rp 50.000)</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] sm:text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Payment Method</label>
                            <select v-model="checkoutForm.payment" class="w-full mt-1 bg-slate-50 border-slate-200 rounded-2xl py-3 px-4 font-bold focus:ring-blue-500 outline-none min-w-0">
                                <option>Bank Transfer</option>
                                <option>Credit Card</option>
                                <option>E-Wallet</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-green-600 text-white py-3 sm:py-4 rounded-2xl font-black hover:bg-green-700 shadow-lg shadow-green-200 transition-all mt-6 text-base sm:text-lg">
                        Confirm & Pay Now 💳
                    </button>
                </form>
            </div>
        </div>

        <div v-if="showLoginModal" class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md" @click.self="showLoginModal = false">
            <div class="bg-white w-full max-w-md rounded-[2rem] sm:rounded-[2.5rem] p-6 sm:p-8 shadow-2xl animate-in zoom-in duration-300 min-w-0">
                <div class="flex justify-between items-center mb-4 sm:mb-6">
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Sign In
                    </h2>
                    <button @click="showLoginModal = false" class="text-slate-400 hover:text-red-500 transition-colors flex-shrink-0"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>

                <p class="text-slate-500 text-sm mb-6 font-medium">Please sign in to continue.</p>

                <form @submit.prevent="submitLogin" class="space-y-4 min-w-0">
                    <div>
                        <input v-model="loginForm.email" type="email" placeholder="Email" class="w-full bg-slate-50 border-slate-200 rounded-2xl py-3 px-5 font-bold focus:ring-blue-500 outline-none min-w-0" required />
                        <p v-if="loginForm.errors.email" class="text-red-500 text-xs mt-1 ml-2 font-bold">{{ loginForm.errors.email }}</p>
                    </div>
                    <div>
                        <input v-model="loginForm.password" type="password" placeholder="Password" class="w-full bg-slate-50 border-slate-200 rounded-2xl py-3 px-5 font-bold focus:ring-blue-500 outline-none min-w-0" required />
                        <p v-if="loginForm.errors.password" class="text-red-500 text-xs mt-1 ml-2 font-bold">{{ loginForm.errors.password }}</p>
                    </div>
                    <button type="submit" :disabled="loginForm.processing" class="w-full bg-slate-900 text-white py-3 sm:py-4 rounded-2xl font-black hover:bg-blue-600 transition-all mt-4 flex justify-center items-center gap-2">
                        <svg v-if="loginForm.processing" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span>{{ loginForm.processing ? 'Processing...' : 'Sign In' }}</span>
                    </button>
                </form>

                <div class="mt-6 text-center border-t border-slate-100 pt-6 min-w-0">
                    <p class="text-sm font-bold text-slate-500 flex flex-col sm:flex-row justify-center items-center gap-1 sm:gap-2">
                        Don't have an account?
                        <button @click="switchToRegister" class="text-blue-600 hover:text-blue-800">Sign Up Now</button>
                    </p>
                </div>
            </div>
        </div>

        <div v-if="showRegisterModal" class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md" @click.self="showRegisterModal = false">
            <div class="bg-white w-full max-w-md rounded-[2rem] sm:rounded-[2.5rem] p-6 sm:p-8 shadow-2xl animate-in zoom-in duration-300 max-h-[90vh] overflow-y-auto min-w-0">
                <div class="flex justify-between items-center mb-4 sm:mb-6">
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        Sign Up
                    </h2>
                    <button @click="showRegisterModal = false" class="text-slate-400 hover:text-red-500 transition-colors flex-shrink-0"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>

                <p class="text-slate-500 text-sm mb-6 font-medium">Create a new account. It's quick and free.</p>

                <form @submit.prevent="submitRegister" class="space-y-4 min-w-0">
                    <div>
                        <input v-model="registerForm.name" type="text" placeholder="Name" class="w-full bg-slate-50 border-slate-200 rounded-2xl py-3 px-5 font-bold focus:ring-blue-500 outline-none min-w-0" required />
                        <p v-if="registerForm.errors.name" class="text-red-500 text-xs mt-1 ml-2 font-bold">{{ registerForm.errors.name }}</p>
                    </div>
                    <div>
                        <input v-model="registerForm.email" type="email" placeholder="Email" class="w-full bg-slate-50 border-slate-200 rounded-2xl py-3 px-5 font-bold focus:ring-blue-500 outline-none min-w-0" required />
                        <p v-if="registerForm.errors.email" class="text-red-500 text-xs mt-1 ml-2 font-bold">{{ registerForm.errors.email }}</p>
                    </div>
                    <div>
                        <input v-model="registerForm.password" type="password" placeholder="Password" class="w-full bg-slate-50 border-slate-200 rounded-2xl py-3 px-5 font-bold focus:ring-blue-500 outline-none min-w-0" required />
                        <p v-if="registerForm.errors.password" class="text-red-500 text-xs mt-1 ml-2 font-bold">{{ registerForm.errors.password }}</p>
                    </div>
                    <div>
                        <input v-model="registerForm.password_confirmation" type="password" placeholder="Confirm Password" class="w-full bg-slate-50 border-slate-200 rounded-2xl py-3 px-5 font-bold focus:ring-blue-500 outline-none min-w-0" required />
                        <p v-if="registerForm.errors.password_confirmation" class="text-red-500 text-xs mt-1 ml-2 font-bold">{{ registerForm.errors.password_confirmation }}</p>
                    </div>
                    <button type="submit" :disabled="registerForm.processing" class="w-full bg-blue-600 text-white py-3 sm:py-4 rounded-2xl font-black hover:bg-blue-700 transition-all mt-4 flex justify-center items-center gap-2">
                        <svg v-if="registerForm.processing" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span>{{ registerForm.processing ? 'Processing...' : 'Register Now' }}</span>
                    </button>
                </form>

                <div class="mt-6 text-center border-t border-slate-100 pt-6 min-w-0">
                    <p class="text-sm font-bold text-slate-500 flex flex-col sm:flex-row justify-center items-center gap-1 sm:gap-2">
                        Already have an account?
                        <button @click="switchToLogin" class="text-blue-600 hover:text-blue-800">Sign In</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>