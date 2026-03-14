<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';

const { props } = usePage();
const storeName = props.status?.storeDisplay || props.status?.storeName || 'Store';

const storeProducts = ref([]);
const activeDropdown = ref(null);
const isAddEditModalOpen = ref(false);
const isDeleteModal1Open = ref(false);
const isDeleteModal2Open = ref(false);
const formMode = ref('add');
const itemToDelete = ref(null);

const productForm = ref({ id: null, name: '', stock: 0, price: 0, status: 'Active', desc: '' });

const formatUSD = (val) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val);

const closeDropdowns = () => {
    activeDropdown.value = null;
};

const fetchProducts = async () => {
    try {
        const response = await axios.get('/api/products');
        storeProducts.value = response.data;
    } catch (e) {}
};

const saveProduct = async () => {
    if (!productForm.value.name) return;
    try {
        if (formMode.value === 'add') {
            await axios.post('/api/products', productForm.value);
        } else {
            await axios.put(`/api/products/${productForm.value.id}`, productForm.value);
        }
        fetchProducts();
        isAddEditModalOpen.value = false;
    } catch (e) {
        alert("Action failed: Database error.");
    }
};

const triggerDelete1 = (product) => { itemToDelete.value = product; isDeleteModal1Open.value = true; };
const triggerDelete2 = () => { isDeleteModal1Open.value = false; isDeleteModal2Open.value = true; };

const confirmDelete = async () => {
    try {
        await axios.delete(`/api/products/${itemToDelete.value.id}`);
        fetchProducts();
        isDeleteModal2Open.value = false;
        itemToDelete.value = null;
    } catch (e) {
        alert("Purge failed.");
    }
};

const openAddModal = () => {
    formMode.value = 'add';
    productForm.value = { id: null, name: '', stock: 0, price: 0, status: 'Active', desc: '' };
    isAddEditModalOpen.value = true;
};

const openEditModal = (product) => {
    formMode.value = 'edit';
    productForm.value = { ...product, desc: product.description };
    isAddEditModalOpen.value = true;
};

const toggleDropdown = (id) => {
    activeDropdown.value = activeDropdown.value === id ? null : id;
};

onMounted(() => {
    fetchProducts();
    document.addEventListener('click', closeDropdowns);
});

onUnmounted(() => {
    document.removeEventListener('click', closeDropdowns);
});
</script>

<template>
    <Head :title="storeName + ' Console'" />
    <AuthenticatedLayout>
        <div class="max-w-6xl mx-auto space-y-8 pb-10">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-black text-slate-900 uppercase">{{ storeName }} Console</h1>
                <button @click="openAddModal" class="bg-blue-600 text-white px-6 py-3 rounded-xl font-bold">Add Product</button>
            </div>

            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-[10px] font-black uppercase tracking-widest text-slate-400">
                        <tr>
                            <th class="p-6">Product Name</th>
                            <th>Stock</th>
                            <th>Price</th>
                            <th class="text-right p-6">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="p in storeProducts" :key="p.id" class="border-t border-slate-50 hover:bg-slate-50 transition-colors">
                            <td class="p-6 font-bold text-slate-700">{{ p.name }}</td>
                            <td>{{ p.stock }} PCS</td>
                            <td class="font-black text-blue-600">{{ formatUSD(p.price) }}</td>
                            <td class="p-6 text-right relative">
                                <button @click.stop="toggleDropdown(p.id)" class="text-slate-400 hover:text-blue-600">•••</button>
                                <div v-if="activeDropdown === p.id" class="absolute right-6 top-12 w-40 bg-white shadow-xl rounded-xl z-50 border border-slate-100 text-left py-2">
                                    <button @click="openEditModal(p)" class="w-full px-4 py-2 text-xs font-bold hover:bg-slate-50">Edit</button>
                                    <button @click="triggerDelete1(p)" class="w-full px-4 py-2 text-xs font-bold text-red-500 hover:bg-red-50">Delete</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div v-if="isAddEditModalOpen" class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm">
            <div class="bg-white w-full max-w-md rounded-[2rem] p-8">
                <h3 class="text-xl font-black mb-6 uppercase">{{ formMode === 'add' ? 'New Product' : 'Edit Product' }}</h3>
                <div class="space-y-4">
                    <input v-model="productForm.name" placeholder="Name" class="w-full p-4 bg-slate-50 rounded-xl border-none font-bold" />
                    <textarea v-model="productForm.desc" placeholder="Description" class="w-full p-4 bg-slate-50 rounded-xl border-none font-bold"></textarea>
                    <div class="grid grid-cols-2 gap-4">
                        <input v-model="productForm.stock" type="number" placeholder="Stock" class="w-full p-4 bg-slate-50 rounded-xl border-none font-bold" />
                        <input v-model="productForm.price" type="number" placeholder="Price" class="w-full p-4 bg-slate-50 rounded-xl border-none font-bold" />
                    </div>
                    <button @click="saveProduct" class="w-full bg-blue-600 text-white py-4 rounded-xl font-black uppercase tracking-widest mt-4">Save Product</button>
                    <button @click="isAddEditModalOpen = false" class="w-full text-slate-400 font-bold text-xs uppercase">Cancel</button>
                </div>
            </div>
        </div>

        <div v-if="isDeleteModal1Open" class="fixed inset-0 z-[70] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm">
            <div class="bg-white p-8 rounded-[2rem] text-center max-w-xs">
                <h3 class="font-black uppercase mb-2">Are you sure?</h3>
                <p class="text-sm text-slate-500 mb-6 font-bold">Delete {{ itemToDelete?.name }}?</p>
                <div class="flex gap-2">
                    <button @click="isDeleteModal1Open = false" class="flex-1 py-3 bg-slate-100 rounded-xl font-bold">No</button>
                    <button @click="triggerDelete2" class="flex-1 py-3 bg-red-500 text-white rounded-xl font-bold">Yes, Delete</button>
                </div>
            </div>
        </div>

        <div v-if="isDeleteModal2Open" class="fixed inset-0 z-[80] flex items-center justify-center bg-slate-900/80 backdrop-blur-md">
            <div class="bg-white p-8 rounded-[2rem] text-center max-w-xs border-4 border-red-100">
                <h3 class="font-black text-red-600 uppercase mb-4">FINAL WARNING</h3>
                <button @click="confirmDelete" class="w-full py-4 bg-red-600 text-white rounded-xl font-black mb-2">PERMANENT PURGE</button>
                <button @click="isDeleteModal2Open = false" class="w-full py-2 text-slate-400 font-bold text-[10px]">ABORT</button>
            </div>
        </div>
    </AuthenticatedLayout>
</template>