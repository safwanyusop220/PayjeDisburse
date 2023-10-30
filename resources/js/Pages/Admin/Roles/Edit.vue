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

import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import TableDataCell from "@/Components/TableDataCell.vue";

// BreadCrumb
import BreadcrumbInitial from "@/Components/BreadcrumbInitial.vue";
import BreadcrumbActive from "@/Components/BreadcrumbActive.vue";
import BreadcrumbCurrent from "@/Components/BreadcrumbCurrent.vue";

// Icons
import { IcCancel } from "@kalimahapps/vue-icons";
import { QuSendCancelled } from "@kalimahapps/vue-icons";
import { BxCheckDouble } from "@kalimahapps/vue-icons";
import { BsSendCheck } from "@kalimahapps/vue-icons";
import { MdOutlinedUpdate } from "@kalimahapps/vue-icons";

import VueMultiselect from 'vue-multiselect'
import { onMounted, watch } from 'vue';

const props = defineProps({
    role: {
        type: Object,
        required: true
    },
    permissions: Array,
});

const form = useForm({
    name: props.role.name,
    description: props.role.description,
    permissions: []
});



onMounted(() =>{
    form.permissions = props.role?.permissions;
});

watch(
    () => props.role,
    () => (form.permissions = props.role?.permissions)
);

</script>

<template>
    <Head title="Update Role" />

    <AdminLayout>
        <div class="mt-14">
            <div class="w-full mx-auto">
                <div class=" overflow-hidden">
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
                                    <BreadcrumbCurrent>Update Role</BreadcrumbCurrent>
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <Content>
                        <form @submit.prevent="form.put(route('roles.update', role.id))">
                            <div class="mt-4">
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
                            <div class="mt-4">
                                <InputLabel for="description" value="Description" />
                                <TextInput
                                    id="description"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.description"
                                    autofocus
                                    autocomplete="username"
                                />

                                <InputError class="mt-2" :message="form.errors.description" />
                            </div>
                            <InputLabel for="Permission" value="Access Details" />

                            <div class="-mt-2 mb-4">
                                <div class="grid grid-cols-3 space-x-4">
                                    <template v-for="permission in permissions">
                                        <div class="bg-primary-50 rounded-xl px-2 py-2 mt-4">
                                            <div>
                                                <div class="col">
                                                    <div class="row">
                                                        <div class=" items-center" >
                                                        <input
                                                            :key="permission.id"
                                                            type="checkbox"
                                                            :id="permission.id"
                                                            v-model="form.permissions"
                                                            :value="permission"
                                                            class="mr-2 rounded px-2 text-xs"
                                                        />
                                                        <label class="text-xs" :for="permission.id">{{ permission.name.toUpperCase() }}</label>
                                                    
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="row">
                                                        <div class="items-center" >
                                                        <input
                                                            :key="permission.id"
                                                            type="checkbox"
                                                            :id="permission.id"
                                                            v-model="form.permissions"
                                                            :value="permission"
                                                            class="ml-3 mr-2 rounded px-2 text-xs"
                                                        />
                                                        <label class="text-xs" :for="permission.id">{{ permission.name.toUpperCase() }}</label>
                                                    
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <!-- Button submit & back-->
                            <div class="flex items-center justify-end space-x-2 mt-8">
                                <Link :href="route('roles.index')" class="flex items-center py-2 px-2 text-xs text-white font-mono bg-danger-button hover:bg-danger-button-hover rounded-md">
                                    <IcCancel class="text-lg mr-0.5" />
                                    Cancel
                                </Link>
                                <button @click="update" :class="{ 'opacity-25 mr-2.5 px-3 py-2 text-md text-white font-semibold bg-primary-500 hover:bg-indigo-700 rounded': form.processing }" :disabled="form.processing" class="flex items-center py-2 px-2 text-xs text-white font-mono bg-success-button hover:bg-success-button-hover rounded-md">
                                    <BxCheckDouble class="text-lg mr-0.5" />
                                    Update
                                </button>
                            </div>
                        </form>
                    </Content>
                    <!--
                    <div class="mt-6">
                        <div class="bg-slate-100 pt-8 px-3 py-3 rounded-lg">
                            <p class="-mt-4 mb-4 text-xl font-bold">Permission</p>
                            <Table>
                                <template #header>
                                    <TableRow>
                                        <TableHeaderCell>ID</TableHeaderCell>
                                        <TableHeaderCell>Name</TableHeaderCell>
                                        <TableHeaderCell>Action</TableHeaderCell>
                                    </TableRow>
                                </template>
                                <template #default>
                                    <TableRow v-for="rolePermission in role.permissions" :key="rolePermission.id" class="border-b">
                                        <TableDataCell>{{ rolePermission.id }}</TableDataCell>
                                        <TableDataCell>{{ rolePermission.name }}</TableDataCell>
                                        <TableDataCell class="space-x-4">
                                            <Link :href="route('roles.permissions.destroy', [role.id, rolePermission.id])" method="DELETE" as="button" class="text-red-400 hover:text-red-600">Invoke</Link>
                                        </TableDataCell>
                                    </TableRow>
                                </template>
                            </Table>
                        </div>
                    </div>-->
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>
