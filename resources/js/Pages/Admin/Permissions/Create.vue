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

const form = useForm({
    name: ""
})
</script>

<template>
    <Head title="Create new Permission" />

    <AdminLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="flex justify-between">
                        <div class="p-1 text-gray-900 text-sm font-bold">| Perincian Akses | Tambah Perincian Akses</div>
                    </div>
                    <div class="mt-6 bg-primary-100 rounded-lg p-6 max-w-md mx-auto">
                        <form @submit.prevent="form.post(route('permissions.store'))">
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
