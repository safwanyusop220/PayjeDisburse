<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

// Form
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';


import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import TableDataCell from "@/Components/TableDataCell.vue";

const props = defineProps({
    permission: {
        type: Object,
        required: true
    }
})

const form = useForm({
    name: props.permission?.name,
})
</script>

<template>
    <Head title="Update Permission" />

    <AdminLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="flex justify-between">
                        <div class="p-1 text-gray-900 text-sm font-bold">| Perincian Akses | Edit Perincian Akses</div>
                    </div>
                    <div class="mt-6 bg-slate-100 rounded-lg p-6 max-w-md mx-auto">
                        <form @submit.prevent="$event => form.put(route('permissions.update', permission.id))">
                            <div>
                                <InputLabel for="name" value="Name" />
                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                    autofocus
                                    autocomplete="username"
                                />

                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <div class="flex items-center justify-end mt-8">
                                <Link :href="route('permissions.index')" class="mr-2.5 px-6 py-2 text-md text-white font-semibold bg-gray-400 hover:bg-gray-500 rounded-lg">Back</Link>
                                <button :class="{ 'opacity-25 mr-2.5 px-3 py-2 text-md text-white font-semibold bg-primary-400 hover:bg-primary-500 rounded': form.processing }" :disabled="form.processing" class="mr-2.5 px-3 py-2 text-md text-white font-semibold bg-primary-400 hover:bg-primary-600 rounded-lg">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
