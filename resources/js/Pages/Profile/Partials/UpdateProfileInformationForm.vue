<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: { type: Boolean },
    status: { type: String },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
    photo: null,
});

// FIX BUG: Upload file pake trik method spoofing di Laravel
const submitProfile = () => {
    form.post(route('profile.update', { _method: 'patch' }), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => form.reset('photo'), // Reset input foto setelah sukses
    });
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-slate-900">Profile Information</h2>
            <p class="mt-1 text-sm text-slate-500">Update your account's profile information, email address, and profile photo.</p>
        </header>

        <form @submit.prevent="submitProfile" class="mt-6 space-y-6">
            
            <div>
                <InputLabel value="Profile Photo" class="text-slate-400 font-black uppercase tracking-widest text-xs" />
                <div class="mt-2 flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-slate-100 border-2 border-slate-200 overflow-hidden flex items-center justify-center text-slate-400 text-2xl font-black">
                        {{ user.name.charAt(0) }}
                    </div>
                    <input type="file" @change="e => form.photo = e.target.files[0]" accept="image/*"
                        class="block w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-5 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all cursor-pointer" />
                </div>
            </div>

            <div>
                <InputLabel for="name" value="Name" class="text-slate-400 font-black uppercase tracking-widest text-xs" />
                <TextInput id="name" type="text" class="mt-1 block w-full bg-slate-50 border-slate-200 rounded-xl py-3 px-4 font-bold focus:ring-blue-500 outline-none" v-model="form.name" required />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="Email" class="text-slate-400 font-black uppercase tracking-widest text-xs" />
                <TextInput id="email" type="email" class="mt-1 block w-full bg-slate-50 border-slate-200 rounded-xl py-3 px-4 font-bold focus:ring-blue-500 outline-none" v-model="form.email" required />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="flex items-center gap-4 pt-4 border-t border-slate-100">
                <button :disabled="form.processing" class="bg-blue-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-100 transition-all">
                    {{ form.processing ? 'Saving...' : 'Save Profile' }}
                </button>
                <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0" leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                    <p v-if="form.recentlySuccessful" class="text-sm font-bold text-green-500 bg-green-50 px-3 py-1 rounded-lg">Saved successfully!</p>
                </Transition>
            </div>
        </form>
    </section>
</template>