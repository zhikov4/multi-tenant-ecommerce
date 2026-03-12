<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({ orders: Array });

const formatDate = (date) => new Date(date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
</script>

<template>
    <Head title="Order History" />
    <AuthenticatedLayout>
        <div class="max-w-4xl mx-auto py-16 px-4">
            <h1 class="text-3xl font-bold mb-10">Order History</h1>

            <div v-if="orders.length === 0" class="text-center py-20 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                <p class="text-gray-400">No transactions found yet.</p>
            </div>

            <div v-else class="space-y-8">
                <div v-for="order in orders" :key="order.id" class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-sm">
                    <div class="p-6 border-b border-gray-50 flex flex-wrap justify-between items-center gap-4">
                        <div class="flex items-center space-x-4">
                            <div class="h-12 w-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center font-bold">
                                #{{ order.id }}
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-widest">Date</p>
                                <p class="text-sm font-bold">{{ formatDate(order.created_at) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-8">
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-widest">Total Amount</p>
                                <p class="text-sm font-black text-black">${{ order.total_price }}</p>
                            </div>
                            <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-green-100 text-green-700">
                                {{ order.status }}
                            </span>
                        </div>
                    </div>
                    <div class="p-6 bg-gray-50/50">
                        <div v-for="item in order.items" :key="item.id" class="flex justify-between items-center py-2">
                            <span class="text-sm text-gray-600">{{ item.product.name }} <span class="text-gray-300 mx-2">×</span> {{ item.quantity }}</span>
                            <span class="text-sm font-semibold">${{ item.price }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>