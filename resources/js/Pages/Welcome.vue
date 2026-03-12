<script setup>
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    store_name: '',
});

const submit = () => {
    form.post('/onboarding');
};
</script>

<template>
    <Head title="iStore SaaS - Build Your Empire" />
    
    <div class="min-h-screen bg-[#FBFBFD] flex flex-col justify-center items-center px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 text-center">
            
            <div class="mb-12">
                <h1 class="text-5xl font-extrabold tracking-tight text-gray-900 mb-4">
                    Launch Your Store.
                </h1>
                <p class="text-lg text-gray-500">
                    Sistem multi-tenant otomatis. Satu klik, database independen Anda siap beroperasi.
                </p>
            </div>

            <div class="bg-white py-10 px-8 shadow-2xl shadow-gray-200/50 rounded-3xl border border-gray-100 text-left">
                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">
                            Store Name
                        </label>
                        <div class="relative flex items-center">
                            <input 
                                v-model="form.store_name" 
                                type="text" 
                                class="w-full pl-4 pr-24 py-4 border-gray-200 rounded-2xl focus:ring-black focus:border-black font-medium transition-colors" 
                                placeholder="my-awesome-store"
                                required
                            >
                            <span class="absolute right-4 text-gray-400 font-medium bg-white pl-2">
                                .localhost
                            </span>
                        </div>
                        <p v-if="form.errors.store_name" class="mt-2 text-sm text-red-600 font-medium">
                            {{ form.errors.store_name }}
                        </p>
                    </div>

                    <button 
                        type="submit" 
                        :disabled="form.processing"
                        class="w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-sm text-lg font-bold text-white bg-black hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black disabled:opacity-50 transition-all"
                    >
                        {{ form.processing ? 'Creating Database...' : 'Create My Store' }}
                    </button>
                </form>
            </div>

            <p class="text-xs text-gray-400 font-medium tracking-widest uppercase mt-8">
                Powered by Laravel Tenancy
            </p>
        </div>
    </div>
</template>