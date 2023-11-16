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

import Content from "@/Components/Content.vue";

// BreadCrumb
import BreadcrumbInitial from "@/Components/BreadcrumbInitial.vue";
import BreadcrumbActive from "@/Components/BreadcrumbActive.vue";
import BreadcrumbCurrent from "@/Components/BreadcrumbCurrent.vue";

// Icons
import { IcCancel } from "@kalimahapps/vue-icons";
import { QuSendCancelled } from "@kalimahapps/vue-icons";
import { BxCheckDouble } from "@kalimahapps/vue-icons";


import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import TableDataCell from "@/Components/TableDataCell.vue";

import VueMultiselect from 'vue-multiselect'
import { onMounted } from 'vue';


defineProps({
    permissions: Array,
    groups: Array,
});

const form = useForm({
    name: "",
    description: "",
    permissions: [],
})

</script>

<template>
    <Head title="Create new role" />

    <AdminLayout>
        <div class="mt-14">
            <div class="w-full mx-auto">
                <div class="flex justify-between pt-4">
                    <nav class="text-sm font-semibold" aria-label="Breadcrumb">
                        <ol class="list-none p-0 inline-flex">
                            <li class="flex items-center">
                                <BreadcrumbInitial :href="route('roles.index')"></BreadcrumbInitial>
                            </li>
                            <li class="flex items-center">
                                <BreadcrumbActive :href="route('roles.index')">Roles</BreadcrumbActive>
                            </li>
                            <li class="flex items-center">
                                <BreadcrumbCurrent>Create</BreadcrumbCurrent>
                            </li>
                        </ol>
                    </nav>
                </div>
                <Content>
                    <form @submit.prevent="form.post(route('roles.store'))">
                        <div class="row mt-4">
                            <div class="grid-cols-2">
                                <div class="span-cols-1">
                                    <div>
                                        <InputLabel value="1. Name" />
                                        <TextInput
                                            id="name"
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="form.name"
                                        />
                                        <InputError class="mt-2" :message="form.errors.name" />
                                    </div>
                                </div>
                                <div class="span-cols-1">
                                    <div>
                                        <InputLabel value="2. Description" />
                                        <TextInput
                                            id="description"
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="form.description"
                                        />
                                        <InputError class="mt-2" :message="form.errors.description" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <InputLabel for="Permission" value="3. Access Details" />

                        <!--All Permission-->
                        <div class="bg-primary-100 rounded-xl px-2 pt-1 pb-2 mt-1 mr-2">
                            <div class="">
                                <input
                                    type="checkbox"
                                    class="mr-1 rounded border-primary-300 focus:ring-primary-100 px-2 text-xs"
                                />
                                <label class="text-xxs font-bold uppercase text-primary-700">All Permission
                                </label>
                            </div>
                            
                        </div>

                        <!--Access Details-->
                        <div class="">
                            <div class="grid grid-cols-3">
                                <template v-for="group in groups">
                                    <div class="bg-primary-100 rounded-xl px-2 py-2 mt-2 mr-2">
                                        <div class="">
                                            <input
                                                :key="group.id"
                                                type="checkbox"
                                                :id="group.id"
                                                :value="group.id"
                                                class="mr-1 rounded border-primary-300 focus:ring-primary-100 px-2 text-xs"
                                            />
                                            <label class="text-xxs font-bold text-primary-700" :for="group.id">
                                                {{ group.name.toUpperCase() }}
                                            </label>
                                        </div>
                                        
                                        <template v-for="permission in group.permissions">                                            
                                            <div class="ml-5 -mt-1.5">
                                                <input
                                                    :key="permission.id"
                                                    type="checkbox"
                                                    :id="permission.name"
                                                    v-model="form.permissions"
                                                    :value="permission"
                                                    class="mr-1 rounded border-primary-300 focus:ring-primary-100 px-2 text-xs"
                                                />
                                                <label class="text-xxs font-medium text-primary-700" :for="permission.id">{{ permission.name }}</label>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>
                        
                        <!--Button Submit-->
                        <div class="flex items-center justify-end space-x-2 mt-4 mb-2">
                            <Link :href="route('roles.index')" class="flex items-center py-2 px-2 text-xs text-white font-mono bg-danger-button hover:bg-danger-button-hover rounded-md">
                                <IcCancel class="text-lg mr-0.5" />
                                Cancel
                            </Link>
                            <button @click="create" :class="{ 'opacity-25 mr-2.5 px-3 py-2 text-md text-white font-semibold bg-primary-500 hover:bg-indigo-700 rounded': form.processing }" :disabled="form.processing" class="flex items-center py-2 px-2 text-xs text-white font-mono bg-success-button hover:bg-success-button-hover rounded-md">
                                <BxCheckDouble class="text-lg mr-0.5" />
                                Submit
                            </button>
                        </div>
                    </form>
                </Content>
            </div>
        </div>
    </AdminLayout>
</template>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>

