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
    title: "",
})
</script>

<template>
    <Head title="Create new post" />

    <AdminLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="flex justify-between">
                        <div class="p-1 text-gray-900">Create post</div>
                        <Link :href="route('posts.index')" class="px-3 py-2 text-xs text-white font-semibold bg-indigo-500 hover:bg-indigo-700 rounded">Back</Link>
                    </div>
                    <div class="mt-6 bg-slate-100 rounded-lg p-6 max-w-md mx-auto">
                        <form @submit.prevent="form.post(route('posts.store'))">
                            <div class="mt-4">
                                <InputLabel for="title" value="title" />
                                <TextInput
                                    id="title"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.title"
                                    autofocus
                                    autocomplete="title"
                                />

                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <div class="flex items-center mt-4">
                                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Create Post
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

