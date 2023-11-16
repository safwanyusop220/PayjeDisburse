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
import TextInputXxs from '@/Components/TextInputXxs.vue';

import Content from "@/Components/Content.vue";

// BreadCrumb
import BreadcrumbInitial from "@/Components/BreadcrumbInitial.vue";
import BreadcrumbActive from "@/Components/BreadcrumbActive.vue";
import BreadcrumbCurrent from "@/Components/BreadcrumbCurrent.vue";

import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import TableDataCell from "@/Components/TableDataCell.vue";

const form = useForm({
    name: "",
    group_name: ""
})
</script>

<template>
    <Head title="Create new Permission" />

    <AdminLayout>
        <div class="mt-14">
            <div class="w-full mx-auto">
                <div class="flex justify-between pt-4">
                    <nav class="text-sm font-semibold" aria-label="Breadcrumb">
                        <ol class="list-none p-0 inline-flex">
                            <li class="flex items-center">
                                <BreadcrumbInitial :href="route('permissions.index')"></BreadcrumbInitial>
                            </li>
                            <li class="flex items-center">
                                <BreadcrumbActive :href="route('permissions.index')">Permission</BreadcrumbActive>
                            </li>
                            <li class="flex items-center">
                                <BreadcrumbCurrent>Create Permission</BreadcrumbCurrent>
                            </li>
                        </ol>
                    </nav>
                </div>
                <form @submit.prevent="form.post(route('permissions.store'))">
                    <Content>
                        <!--Permission Name-->
                        <div class="grid grid-cols-12 mt-4">
                            <div class="col-span-2">
                                <InputLabel class="mt-1.5" value="Permission Name" />
                            </div>
                            <div class="col-span-10">
                                <TextInputXxs
                                    id="name"
                                    type="text"
                                    class=" block w-1/3 text-xs"
                                    v-model="form.name"
                                />
                            </div>
                        </div>

                        <!--Permission Group-->
                        <div class="grid grid-cols-12">
                                <div class="col-span-2">
                                    <InputLabel class="mt-4" value="Permission Group" />
                                </div>
                                
                                <div class="col-span-10">
                                    <div class="my-3">
                                        <select class="h-[30px] text-primary-700 border-none bg-primary-50 text-xs rounded-lg w-1/3 focus:ring-white" v-model="form.group_name" id="allocation">
                                            <option value="" disabled>Select Group</option>
                                            <option value="Allocation">Allocation</option>
                                            <option value="Program">Program</option>
                                            <option value="Receiver">Receiver</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        <div class="flex items-center justify-end mt-8">
                            <Link :href="route('permissions.index')" class="mr-2.5 px-6 py-2 text-md text-white font-semibold bg-gray-400 hover:bg-gray-500 rounded-lg">Back</Link>
                            <button :class="{ 'opacity-25 mr-2.5 px-3 py-2 text-md text-white font-semibold bg-primary-400 hover:bg-primary-500 rounded': form.processing }" :disabled="form.processing" class="mr-2.5 px-3 py-2 text-md text-white font-semibold bg-primary-400 hover:bg-primary-600 rounded-lg">Submit</button>
                        </div>
                    </Content>
                    
                </form>
        </div>
        </div>
    </AdminLayout>
</template>
