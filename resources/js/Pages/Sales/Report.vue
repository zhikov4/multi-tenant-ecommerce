<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>
<template>
    <Head title="Sales Report" />
    <AuthenticatedLayout>
        <div class="py-12 text-black">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                    <h1 class="text-2xl font-bold mb-4">Sales Analytics</h1>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-indigo-50 p-6 rounded-xl border border-indigo-100">
                            <div class="text-indigo-600 text-sm font-bold uppercase">Total Revenue</div>
                            <div class="text-3xl font-black text-gray-900">$12,450.00</div>
                        </div>
                        <div class="bg-green-50 p-6 rounded-xl border border-green-100">
                            <div class="text-green-600 text-sm font-bold uppercase">Total Orders</div>
                            <div class="text-3xl font-black text-gray-900">142</div>
                        </div>
                        <div class="bg-orange-50 p-6 rounded-xl border border-orange-100">
                            <div class="text-orange-600 text-sm font-bold uppercase">Avg. Order Value</div>
                            <div class="text-3xl font-black text-gray-900">$87.60</div>
                        </div>
                    </div>
                    <div class="text-center py-20 border-2 border-dashed border-gray-200 rounded-2xl">
                        <p class="text-gray-400 italic">Historical data chart will appear here after more sales occur.</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
