<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from "vue";

import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import TableDataCell from "@/Components/TableDataCell.vue";
import TableHeaderCellLeft from "@/Components/TableHeaderCellLeft.vue";
import TableHeaderCellRight from "@/Components/TableHeaderCellRight.vue";
import TablePagination from "@/Components/TablePagination.vue";
import Content from "@/Components/Content.vue";

import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import AddButton from "@/Components/AddButton.vue";

// BreadCrumb
import BreadcrumbInitial from "@/Components/BreadcrumbInitial.vue";
import BreadcrumbActive from "@/Components/BreadcrumbActive.vue";
import BreadcrumbCurrent from "@/Components/BreadcrumbCurrent.vue";

defineProps(['permissions'])

const form = useForm({})

const showConfirmDeletePermissionModal = ref(false)


const confirmDeletePermission = () => {
    showConfirmDeletePermissionModal.value = true;
}

const closeModal = () => {
    showConfirmDeletePermissionModal.value = false;
}

const deletePermission = (id) => {
    form.delete(route('permissions.destroy', id), {
        onSuccess: () => closeModal()        
    });
}
</script>

<template>
    <Head title="Permissions" />

    <AdminLayout>
        <div class="mt-14">
            <div class="mx-auto w-full">
                <div class="flex justify-between pt-4">
                    <nav class="text-sm font-semibold" aria-label="Breadcrumb">
                        <ol class="inline-flex list-none">
                            <li class="flex items-center">
                                <BreadcrumbInitial
                                    :href="route('permissions.index')"
                                ></BreadcrumbInitial>
                            </li>
                            <li class="flex items-center">
                                <BreadcrumbCurrent>
                                    Permissions
                                </BreadcrumbCurrent>
                            </li>
                        </ol>
                    </nav>
                </div>
                <Content>
                    <div class="mb-3 mt-1 flex justify-between">
                        <span class="mt-1.5 text-lg font-medium text-primary-text">Permission</span>

                        <div class="flex justify-end">
                            <AddButton :href="route('permissions.create')">Create</AddButton>
                        </div>
                    </div>

                <div class="mb-2 border-b-2 border-gray-200"></div>
                    <div class="mt-6">
                        <Table>
                            <template #header>
                                <TableRow>
                                    <TableHeaderCellLeft>Id</TableHeaderCellLeft>
                                    <TableHeaderCell>Permission Name</TableHeaderCell>
                                    <TableHeaderCell>Group Name</TableHeaderCell>
                                    <TableHeaderCellRight>Tindakan</TableHeaderCellRight>
                                </TableRow>
                            </template>
                            <template #default>
                                <TableRow v-for="permission in permissions.data" :key="permission.id" class="border-b">
                                    <TableDataCell>{{ permission.id }}</TableDataCell>
                                    <TableDataCell>{{ permission.name }}</TableDataCell>
                                    <TableDataCell>{{ permission.group.name }}</TableDataCell>
                                    <TableDataCell class="space-x-4">
                                        <div class="row">
                                            <div class="grid grid-cols-2 justify-items-center">
                                                <Link :href="route('permissions.edit', permission.id)" class="text-blue-400 hover:text-blue-600 -mr-28">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                    </svg>
                                                </Link>
                                                <button @click="confirmDeletePermission" class="text-red-400 hover:text-red-600 -ml-28">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>

                                        <Modal :show="showConfirmDeletePermissionModal" @close="closeModal">
                                            <div class="p-6">
                                                <h2 class="text-lg font-semibold text-slate-800">
                                                    Are you sure to delete this Permission ?
                                                </h2>
                                                <div class="mt-6 flex space-x-2">
                                                    <DangerButton @click="$event => deletePermission(permission.id)">Delete</DangerButton>
                                                    <SecondaryButton @click="closeModal">Cancel</SecondaryButton>
                                                </div>
                                            </div>
                                        </Modal>
                                    </TableDataCell>
                                </TableRow>
                            </template>
                        </Table>
                        <div class="text-center mx-auto m-2 p-2">
                            <TablePagination :links="permissions.links" />
                        </div>
                    </div>
                </Content>
            </div>
        </div>
    </AdminLayout>
</template>
