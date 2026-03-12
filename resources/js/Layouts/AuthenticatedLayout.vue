<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
const showingNavigationDropdown = ref(false);
</script>
<template>
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="shrink-0 flex items-center font-bold text-xl text-indigo-600">
                            MyStoreTest
                        </div>
                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <Link :href="route('products.index')" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 transition duration-150 ease-in-out">
                                Products (CRUD)
                            </Link>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <Link :href="route('logout')" method="post" as="button" class="ml-4 text-sm font-medium text-red-600 hover:text-red-800 transition">
                            Logout
                        </Link>
                    </div>
                </div>
            </div>
        </nav>
        <main>
            <slot />
        </main>
    </div>
</template>
