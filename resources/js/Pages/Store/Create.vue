<template>
    <Head title="Register New Store" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Register New Store</h2>
        </template>
        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-8">
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Store Information</h3>
                            <p class="text-sm text-gray-500 mt-1">Fill in your store details below.</p>
                        </div>

                        <div v-if="successMessage" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-md">
                            <p class="text-sm text-green-700">{{ successMessage }}</p>
                        </div>

                        <form @submit.prevent="submit" class="space-y-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Store Display Name</label>
                                <input v-model="form.store_display_name" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="My Awesome Store" />
                                <p v-if="errors.store_display_name" class="text-red-500 text-xs mt-1">{{ errors.store_display_name }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Store Name / Subdomain</label>
                                <div class="flex items-center border border-gray-300 rounded-md overflow-hidden focus-within:ring-2 focus-within:ring-indigo-500">
                                    <input v-model="form.store_name" type="text" class="flex-1 px-3 py-2 text-sm outline-none" placeholder="mystore" @input="sanitizeStoreName" />
                                    <span class="bg-gray-100 px-3 py-2 text-sm text-gray-500 border-l border-gray-300">.localhost</span>
                                </div>
                                <p class="text-xs text-gray-400 mt-1">Your store URL: http://{{ form.store_name || 'yourstore' }}.localhost:8000</p>
                                <p v-if="errors.domain_id" class="text-red-500 text-xs mt-1">{{ errors.domain_id }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Domain Extension</label>
                                <select v-model="form.domain_extension" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option value=".localhost">.localhost</option>
                                    <option value=".local">.local</option>
                                </select>
                                <p v-if="errors.domain_extension" class="text-red-500 text-xs mt-1">{{ errors.domain_extension }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                <select v-model="form.category" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option value="">Select category</option>
                                    <option value="electronics">Electronics</option>
                                    <option value="fashion">Fashion</option>
                                    <option value="food">Food & Beverages</option>
                                    <option value="health">Health & Beauty</option>
                                    <option value="home">Home & Living</option>
                                    <option value="sports">Sports & Outdoors</option>
                                    <option value="toys">Toys & Games</option>
                                    <option value="books">Books & Media</option>
                                    <option value="other">Other</option>
                                </select>
                                <p v-if="errors.category" class="text-red-500 text-xs mt-1">{{ errors.category }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea v-model="form.description" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Describe your store..."></textarea>
                                <p v-if="errors.description" class="text-red-500 text-xs mt-1">{{ errors.description }}</p>
                            </div>

                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Country Code</label>
                                    <input v-model="form.country_code" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="+1" />
                                    <p v-if="errors.country_code" class="text-red-500 text-xs mt-1">{{ errors.country_code }}</p>
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                    <input v-model="form.phone" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="8123456789" />
                                    <p v-if="errors.phone" class="text-red-500 text-xs mt-1">{{ errors.phone }}</p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                                <input v-model="form.address_detail" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="123 Main Street" />
                                <p v-if="errors.address_detail" class="text-red-500 text-xs mt-1">{{ errors.address_detail }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ZIP / Postal Code</label>
                                <input v-model="form.zip_code" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="10001" />
                                <p v-if="errors.zip_code" class="text-red-500 text-xs mt-1">{{ errors.zip_code }}</p>
                            </div>

                            <div class="flex items-start gap-3">
                                <input v-model="form.agreement" type="checkbox" id="agreement" class="mt-1" />
                                <label for="agreement" class="text-sm text-gray-600">I agree to the terms and conditions of this platform.</label>
                            </div>
                            <p v-if="errors.agreement" class="text-red-500 text-xs">{{ errors.agreement }}</p>

                            <div class="pt-2">
                                <button type="submit" :disabled="processing" class="w-full bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white font-medium py-2.5 px-4 rounded-md text-sm transition">
                                    {{ processing ? 'Creating Store...' : 'Create Store' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, inject } from 'vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const route = inject('route')
const page = usePage()

const successMessage = ref(page.props.flash?.success ?? null)

const form = ref({
    store_display_name: '',
    store_name:         '',
    domain_extension:   '.localhost',
    category:           '',
    description:        '',
    country_code:       '+1',
    phone:              '',
    address_detail:     '',
    zip_code:           '',
    agreement:          false,
})

const errors = ref({})
const processing = ref(false)

function sanitizeStoreName() {
    form.value.store_name = form.value.store_name
        .toLowerCase()
        .replace(/[^a-z0-9-]/g, '-')
        .replace(/-+/g, '-')
        .replace(/^-|-$/g, '')
}

function submit() {
    processing.value = true
    errors.value = {}
    router.post(route('central.store.create'), form.value, {
        onError: (errs) => {
            errors.value = errs
            processing.value = false
        },
        onFinish: () => {
            processing.value = false
        },
    })
}
</script>