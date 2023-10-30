<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import TableDataCell from "@/Components/TableDataCell.vue";

import Content from "@/Components/Content.vue";

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
    user: {
        type: Object,
        required: true
    },
    roles: Array,
    permissions: true,
})

const form = useForm({
    name: props.user?.name,
    email: props.user?.email,
    roles: [],
    permissions: []

});

const submit = () => {
    form.put(route("users.update", props.user?.id))
};

onMounted(() =>{
    form.permissions = props.user?.permissions;
    form.roles = props.user?.roles;
});

watch(
    () => props.user,
    () => {
        form.permissions = props.user?.permissions,
        form.roles = props.user?.roles
    }
);
</script>

<template>
    <AdminLayout>
        <Head title="Create user" />
        <div class="mt-14">
            <div class="w-full mx-auto">
                <div class="flex justify-between pt-4">
                    <nav class="text-sm font-semibold" aria-label="Breadcrumb">
                        <ol class="list-none p-0 inline-flex">
                            <li class="flex items-center">
                                <BreadcrumbInitial :href="route('users.index')"></BreadcrumbInitial>
                            </li>
                            <li class="flex items-center">
                                <BreadcrumbActive :href="route('users.index')">Users</BreadcrumbActive>
                            </li>
                            <li class="flex items-center">
                                <BreadcrumbCurrent>Update User</BreadcrumbCurrent>
                            </li>
                        </ol>
                    </nav>
                </div>
                <Content>
                    <form @submit.prevent="submit" class="px-2">
                        <div class="grid grid-cols-2 space-x-4">
                            <div class="mt-4">
                                <InputLabel for="name" value="Name" />

                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                    required
                                    autofocus
                                    autocomplete="name"
                                />

                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="email" value="Email" />

                                <TextInput
                                    id="email"
                                    type="email"
                                    class="mt-1 block w-full"
                                    v-model="form.email"
                                    required
                                    autocomplete="username"
                                />

                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>
                        </div>

                        <div class="mt-4 mb-4">
                            <InputLabel for="roles" value="Roles" />

                            <VueMultiselect
                            v-model="form.roles"
                            :options="roles"
                            :multiple="true"
                            :close-on-select="true"
                            placeholder="Pick some"
                            label="name"
                            track-by="id"
                            />
                        </div>

                        <h3 class="mt-3 font-semibold text-gray-900 dark:text-white">User Access</h3>

                        <div class="mt-2 mb-4">
                            <div class="row">
                                <div class="col grid grid-cols-3 space-x-4">
                                    <template v-for="permission in permissions">
                                        <div class="bg-primary-50 rounded-xl px-2 py-4 mt-4">
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
                                                            class="mr-2 rounded px-2"
                                                        />
                                                        <label class="text-xs" :for="permission.id">{{ permission.name.toUpperCase() }}</label>
                                                    
                                                        </div>
                                                    </div>
                                                    <div class="row ml-4">
                                                        <div class=" items-center" >
                                                        <input
                                                            :key="permission.id"
                                                            type="checkbox"
                                                            :id="permission.id"
                                                            v-model="form.permissions"
                                                            :value="permission"
                                                            class="mr-2 rounded px-2"
                                                        />
                                                        <label class="text-xs" :for="permission.id">{{ permission.name }}</label>
                                                    
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <!-- Button submit & back-->
                        <div class="flex items-center justify-end space-x-2 mt-4 mb-2">
                            <Link :href="route('users.index')" class="flex items-center py-2 px-2 text-xs text-white font-mono bg-danger-button hover:bg-danger-button-hover rounded-md">
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
            </div>
        </div>
        <!--
        <div class="mt-6">
            <div class="bg-slate-100 pt-8 px-3 py-3 rounded-lg">
                <p class="-mt-4 mb-4 text-xl font-bold">Roles</p>
                <Table>
                    <template #header>
                        <TableRow>
                            <TableHeaderCell>ID</TableHeaderCell>
                            <TableHeaderCell>Name</TableHeaderCell>
                            <TableHeaderCell>Action</TableHeaderCell>
                        </TableRow>
                    </template>
                    <template #default>
                        <TableRow v-for="userRole in user.roles" :key="userRole.id" class="border-b">
                            <TableDataCell>{{ userRole.id }}</TableDataCell>
                            <TableDataCell>{{ userRole.name }}</TableDataCell>
                            <TableDataCell class="space-x-4">
                                <Link 
                                :href="route('users.roles.destroy', 
                                [user.id, userRole.id])"
                                 method="DELETE" as="button" 
                                 class="text-red-400 hover:text-red-600"
                                 preserve-scroll="">Invoke</Link>
                            </TableDataCell>
                        </TableRow>
                    </template>
                </Table>
            </div>
        </div>
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
                        <TableRow v-for="userPermission in user.permissions" :key="userPermission.id" class="border-b">
                            <TableDataCell>{{ userPermission.id }}</TableDataCell>
                            <TableDataCell>{{ userPermission.name }}</TableDataCell>
                            <TableDataCell class="space-x-4">
                                <Link 
                                :href="route('users.permissions.destroy', 
                                [user.id, userPermission.id])" 
                                method="DELETE" as="button" 
                                class="text-red-400 hover:text-red-600"
                                preserve-scroll="">Invoke</Link>
                            </TableDataCell>
                        </TableRow>
                    </template>
                </Table>
            </div>
        </div>-->
    </AdminLayout>
</template>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>
