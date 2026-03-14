<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Dashboard</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">My Store</h3>
                            <template v-if="tenantDomain">
                                <a :href="productUrl" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition">Manage Products</a>
                            </template>
                            <template v-else>
                                <Link :href="route('central.store.form')" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition">Register New Store</Link>
                            </template>
                        </div>
                        <div v-if="tenant" class="text-sm text-gray-600">
                            <span class="font-medium">Store ID:</span> {{ tenant.id }}
                            <span class="ml-4 font-medium">Domain:</span>
                            <span v-if="tenantDomain">{{ tenantDomain }}</span>
                            <span v-else class="text-red-500">No domain registered</span>
                        </div>
                        <div v-else class="text-sm text-gray-500">You have not registered a store yet.</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Shopping Cart</h3>
                            <Link :href="route('cart')" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">View Full Cart</Link>
                        </div>
                        <div v-if="cartItems.length === 0" class="text-sm text-gray-500">Your cart is empty.</div>
                        <div v-else class="space-y-3">
                            <div v-for="item in cartItems" :key="item.id" class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ item.name }}</p>
                                    <p class="text-xs text-gray-500">Qty: {{ item.quantity }}</p>
                                </div>
                                <p class="text-sm font-semibold text-gray-900">${{ formatPrice(item.subtotal) }}</p>
                            </div>
                            <div class="flex items-center justify-between pt-3">
                                <p class="text-sm font-semibold text-gray-900">Total</p>
                                <p class="text-base font-bold text-indigo-600">${{ formatPrice(cartTotal) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { computed, inject } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const route = inject('route')

const props = defineProps({
    cartItems:    { type: Array,  default: () => [] },
    cartTotal:    { type: Number, default: 0 },
    tenantDomain: { type: String, default: null },
    tenant:       { type: Object, default: null },
})

const productUrl = computed(() => {
    if (!props.tenantDomain) return null
    const port = window.location.port ? `:${window.location.port}` : ''
    return `http://${props.tenantDomain}${port}/products`
})

function formatPrice(value) {
    return new Intl.NumberFormat('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(value)
}
</script>