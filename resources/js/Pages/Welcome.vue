<script setup>
import { Head, Link, usePage, useForm, router } from '@inertiajs/vue3';
import { ref, inject } from 'vue';

const route = inject('route');
const { props } = usePage();

const appProps = defineProps({
    products: { type: Array, default: () => [] },
    canLogin: Boolean,
    canRegister: Boolean,
});

const selectedProduct = ref(null);
const isDetailModalOpen = ref(false);
const showLoginModal = ref(false);
const showRegisterModal = ref(false);
const purchaseQty = ref(1);

const loginForm = useForm({ email: '', password: '', remember: false });
const registerForm = useForm({ name: '', email: '', password: '', password_confirmation: '' });

const openDetail = (product) => {
    selectedProduct.value = product;
    purchaseQty.value = 1;
    isDetailModalOpen.value = true;
};

const addToCart = () => {
    if (!props.auth?.user) {
        showLoginModal.value = true;
        return;
    }
    router.post(route('cart.store'), {
        tenant_id: selectedProduct.value.tenant_id,
        product_id: selectedProduct.value.id,
        quantity: purchaseQty.value,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            isDetailModalOpen.value = false;
            alert('Item added to cart! 🛒');
        },
    });
};

const submitLogin = () => {
    loginForm.post(route('login'), {
        preserveScroll: true,
        onSuccess: () => { showLoginModal.value = false; },
        onFinish: () => loginForm.reset('password'),
    });
};

const submitRegister = () => {
    registerForm.post(route('register'), {
        preserveScroll: true,
        onSuccess: () => { showRegisterModal.value = false; },
        onFinish: () => registerForm.reset('password', 'password_confirmation'),
    });
};

const switchToRegister = () => { showLoginModal.value = false; showRegisterModal.value = true; };
const switchToLogin = () => { showRegisterModal.value = false; showLoginModal.value = true; };
</script>

<template>
    <Head title="Welcome - MyStore Marketplace" />

    <div class="min-h-screen bg-slate-50 text-slate-900 font-sans pb-20">
        <nav class="bg-white/90 backdrop-blur-md border-b border-slate-100 sticky top-0 z-40 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 h-20 flex items-center justify-between">
                <Link :href="route('central.home')" class="text-xl sm:text-2xl font-black text-blue-600 tracking-tighter flex items-center gap-2">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>MyStore</span>
                </Link>
                <div class="flex items-center gap-3">
                    <template v-if="$page.props.auth.user">
                        <Link :href="route('cart')" class="relative text-slate-600 hover:text-blue-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </Link>
                        <Link :href="route('dashboard')" class="bg-slate-900 text-white px-5 py-2 rounded-xl font-bold hover:bg-blue-600 transition-all text-sm">Dashboard</Link>
                    </template>
                    <template v-else>
                        <button @click="showLoginModal = true" class="font-bold text-slate-600 hover:text-blue-600 px-3 text-sm">Sign In</button>
                        <button @click="showRegisterModal = true" class="bg-slate-900 text-white px-5 py-2 rounded-xl font-bold hover:bg-blue-600 transition-all text-sm">Sign Up</button>
                    </template>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
            <h1 class="text-3xl sm:text-4xl font-black mb-2 text-slate-900">Marketplace</h1>
            <p class="text-slate-500 mb-8">Discover products from all stores.</p>

            <div v-if="products.length === 0" class="text-center py-24 text-slate-400">
                <svg class="w-16 h-16 mx-auto mb-4 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <p class="font-bold">No products available yet.</p>
                <p class="text-sm mt-1">Register a store and add products to see them here.</p>
            </div>

            <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div v-for="product in products" :key="`${product.tenant_id}-${product.id}`"
                    @click="openDetail(product)"
                    class="group cursor-pointer bg-white p-4 rounded-3xl shadow-sm border border-transparent hover:border-blue-200 hover:shadow-xl transition-all flex flex-col">
                    <div class="aspect-square rounded-2xl bg-slate-50 mb-4 flex items-center justify-center group-hover:bg-blue-50 transition-colors">
                        <svg class="w-20 h-20 text-slate-200 group-hover:text-blue-300 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-blue-500 mb-1">{{ product.store }}</p>
                    <h3 class="font-bold text-slate-800 mb-1 line-clamp-2">{{ product.name }}</h3>
                    <p v-if="product.category" class="text-xs text-slate-400 mb-2">{{ product.category }}</p>
                    <p class="text-xl font-black text-slate-900 mt-auto">${{ Number(product.price).toFixed(2) }}</p>
                    <p class="text-xs text-slate-400 mt-1">{{ product.stock }} in stock</p>
                </div>
            </div>
        </main>

        <!-- Product Detail Modal -->
        <div v-if="isDetailModalOpen && selectedProduct" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" @click.self="isDetailModalOpen = false">
            <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl p-8">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-blue-500 mb-1">{{ selectedProduct.store }}</p>
                        <h2 class="text-2xl font-black text-slate-900">{{ selectedProduct.name }}</h2>
                        <p v-if="selectedProduct.category" class="text-sm text-slate-400 mt-1">{{ selectedProduct.category }}</p>
                    </div>
                    <button @click="isDetailModalOpen = false" class="text-slate-300 hover:text-red-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <p v-if="selectedProduct.description" class="text-slate-500 text-sm mb-6">{{ selectedProduct.description }}</p>

                <div class="flex justify-between items-center mb-6">
                    <p class="text-3xl font-black text-blue-600">${{ Number(selectedProduct.price).toFixed(2) }}</p>
                    <p class="text-sm text-slate-400">{{ selectedProduct.stock }} in stock</p>
                </div>

                <div class="flex items-center gap-4 mb-6">
                    <label class="text-sm font-bold text-slate-600">Qty</label>
                    <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl p-1">
                        <button @click="purchaseQty > 1 ? purchaseQty-- : null" class="w-8 h-8 rounded-lg bg-white shadow-sm font-bold text-slate-500 hover:text-blue-600">-</button>
                        <span class="w-8 text-center font-black">{{ purchaseQty }}</span>
                        <button @click="purchaseQty < selectedProduct.stock ? purchaseQty++ : null" class="w-8 h-8 rounded-lg bg-white shadow-sm font-bold text-slate-500 hover:text-blue-600">+</button>
                    </div>
                </div>

                <button @click="addToCart" class="w-full bg-blue-600 text-white py-4 rounded-2xl font-black hover:bg-blue-700 transition-all shadow-lg shadow-blue-200">
                    Add to Cart 🛒
                </button>
            </div>
        </div>

        <!-- Login Modal -->
        <div v-if="showLoginModal" class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md" @click.self="showLoginModal = false">
            <div class="bg-white w-full max-w-md rounded-3xl p-8 shadow-2xl">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-black text-slate-900">Sign In</h2>
                    <button @click="showLoginModal = false" class="text-slate-300 hover:text-red-500"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
                <form @submit.prevent="submitLogin" class="space-y-4">
                    <input v-model="loginForm.email" type="email" placeholder="Email" class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-3 px-5 font-bold outline-none focus:ring-2 focus:ring-blue-500" required />
                    <p v-if="loginForm.errors.email" class="text-red-500 text-xs font-bold">{{ loginForm.errors.email }}</p>
                    <input v-model="loginForm.password" type="password" placeholder="Password" class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-3 px-5 font-bold outline-none focus:ring-2 focus:ring-blue-500" required />
                    <p v-if="loginForm.errors.password" class="text-red-500 text-xs font-bold">{{ loginForm.errors.password }}</p>
                    <button type="submit" :disabled="loginForm.processing" class="w-full bg-slate-900 text-white py-4 rounded-2xl font-black hover:bg-blue-600 transition-all disabled:opacity-50">
                        {{ loginForm.processing ? 'Signing in...' : 'Sign In' }}
                    </button>
                </form>
                <p class="text-center text-sm text-slate-500 mt-6">
                    No account? <button @click="switchToRegister" class="text-blue-600 font-bold hover:underline">Sign Up</button>
                </p>
            </div>
        </div>

        <!-- Register Modal -->
        <div v-if="showRegisterModal" class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md" @click.self="showRegisterModal = false">
            <div class="bg-white w-full max-w-md rounded-3xl p-8 shadow-2xl">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-black text-slate-900">Sign Up</h2>
                    <button @click="showRegisterModal = false" class="text-slate-300 hover:text-red-500"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
                <form @submit.prevent="submitRegister" class="space-y-4">
                    <input v-model="registerForm.name" type="text" placeholder="Name" class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-3 px-5 font-bold outline-none focus:ring-2 focus:ring-blue-500" required />
                    <input v-model="registerForm.email" type="email" placeholder="Email" class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-3 px-5 font-bold outline-none focus:ring-2 focus:ring-blue-500" required />
                    <input v-model="registerForm.password" type="password" placeholder="Password" class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-3 px-5 font-bold outline-none focus:ring-2 focus:ring-blue-500" required />
                    <input v-model="registerForm.password_confirmation" type="password" placeholder="Confirm Password" class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-3 px-5 font-bold outline-none focus:ring-2 focus:ring-blue-500" required />
                    <p v-if="registerForm.errors.password" class="text-red-500 text-xs font-bold">{{ registerForm.errors.password }}</p>
                    <button type="submit" :disabled="registerForm.processing" class="w-full bg-blue-600 text-white py-4 rounded-2xl font-black hover:bg-blue-700 transition-all disabled:opacity-50">
                        {{ registerForm.processing ? 'Creating...' : 'Create Account' }}
                    </button>
                </form>
                <p class="text-center text-sm text-slate-500 mt-6">
                    Have account? <button @click="switchToLogin" class="text-blue-600 font-bold hover:underline">Sign In</button>
                </p>
            </div>
        </div>
    </div>
</template>