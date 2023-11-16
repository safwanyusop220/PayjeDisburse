<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, watch } from "vue";
import { router } from '@inertiajs/vue3'

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

import { BsSearch } from "@kalimahapps/vue-icons";
import { SuCreate } from "@kalimahapps/vue-icons";
import { FlDelete } from "@kalimahapps/vue-icons";

const props = defineProps({
    roles: {
        type: Object,
        required: true,
    },
    filters: Object,
});

const form = useForm({})

const showConfirmDeleteRoleModal = ref(false)


const confirmDeleteRole = () => {
    showConfirmDeleteRoleModal.value = true;
}

const closeModal = () => {
    showConfirmDeleteRoleModal.value = false;
}

const deleteRole = (id) => {
    form.delete(route('roles.destroy', id), {
        onSuccess: () => closeModal()        
    });
}

const formatTimestamp = (timestamp) => {
  const date = new Date(timestamp);

  const day = date.getDate().toString().padStart(2, '0');
  const month = (date.getMonth() + 1).toString().padStart(2, '0'); 
  const year = date.getFullYear().toString().slice(-2);

  const formattedDate = `${day}/${month}/${year}`;

  return formattedDate;
}

const search = ref(props.filters.search);
const perPage = ref(5);

watch(search, (value) =>{
    router.get("/roles", {search: value, perPage: perPage.value},
    {
        preserveState: true,
        replace: true
    });
})

function getAllocations() {
    router.get(
        "/roles",
        { perPage: perPage.value, search: search.value },
        {
            preserveState: true,
            replace: true,
        }
    );
}
</script>

<template>
    <Head title="Dashboard" />

    <AdminLayout>
        <div class="mt-14">
            <div class="w-full mx-auto">
                <div class="overflow-hidden">
                    <div class="flex justify-between pt-4">
                        <nav class="text-sm font-semibold" aria-label="Breadcrumb">
                            <ol class="list-none p-0 inline-flex">
                                <li class="flex items-center">
                                    <BreadcrumbInitial :href="route('roles.index')"></BreadcrumbInitial>
                                </li>
                                <li class="flex items-center">
                                    <BreadcrumbCurrent>Roles</BreadcrumbCurrent>
                                </li>
                            </ol>
                        </nav>
                    </div>

                    <Content>
                        <div class="flex justify-between">
                            <span class="font-medium text-primary-text text-lg mt-2">Roles</span>

                            <AddButton :href="route('roles.create')">Create</AddButton>
                        </div>

                        <div class="mb-2 mt-2.5 border-b-2 border-gray-200"></div>

                        <div class="mb-2 flex justify-between">
                            <BsSearch class="absolute ml-2 text-sm text-gray-500 mt-2" />
                            <input
                                v-model="search"
                                type="text"
                                placeholder="Search"
                                class="pl-7 w-1/3 rounded-md text-primary-600 bg-primary-50 text-xs border-none focus:ring-primary-100"
                            />

                            <select
                                v-model="perPage"
                                @change="getAllocations"
                                class="rounded-md bg-primary-50 text-xs text-primary-400 border-none focus:ring-primary-100">
                                <option value="1">10</option>
                                <option value="2">20</option>
                                <option value="5">50</option>
                                <option value="10">100</option>
                            </select>
                        </div>

                        <div class="mt-2">
                            <Table>
                                <template #header>
                                    <TableRow>
                                        <TableHeaderCellLeft class="w-[20px]">No</TableHeaderCellLeft>
                                        <TableHeaderCell class="w-[150px]">Date Created</TableHeaderCell>
                                        <TableHeaderCell class="w-[200px]">Name</TableHeaderCell>
                                        <TableHeaderCell class="w-[400px]">Description</TableHeaderCell>
                                        <TableHeaderCell class="w-[200px]">Latest Update</TableHeaderCell>
                                        <TableHeaderCellRight class="w-[150px]">Action</TableHeaderCellRight>
                                    </TableRow>
                                </template>
                                <template #default>
                                    <TableRow v-for="(role, index) in roles.data" :key="role.id" class="border-b">
                                        <TableDataCell>{{ index + 1 }}</TableDataCell>
                                        <TableDataCell>{{ formatTimestamp(role.created_at) }}</TableDataCell>
                                        <TableDataCell>{{ role.name }}</TableDataCell>
                                        <TableDataCell>{{ role.description }}</TableDataCell>
                                        <TableDataCell>{{ formatTimestamp(role.updated_at) }}</TableDataCell>
                                        <TableDataCell class="space-x-4">
                                            <div class="row">
                                                <div class="grid grid-cols-2 justify-items-center">
                                                    <Link :href="route('roles.edit', role.id)" class="text-blue-400 hover:text-blue-600 -mr-8">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                        </svg>
                                                    </Link>
                                                    <button @click="confirmDeleteUser" class="text-red-400 hover:text-red-600 -ml-8">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                            <Modal :show="showConfirmDeleteRoleModal" @close="closeModal">
                                                <div class="p-6">
                                                    <h2 class="text-lg font-semibold text-slate-800">
                                                        Are you sure to delete this Role?
                                                    </h2>
                                                    <div class="mt-6 flex space-x-2">
                                                        <DangerButton @click="$event => deleteRole(role.id)">Delete</DangerButton>
                                                        <SecondaryButton @click="closeModal">Cancel</SecondaryButton>
                                                    </div>
                                                </div>
                                            </Modal>
                                        </TableDataCell>
                                    </TableRow>
                                </template>
                            </Table>
                            <div class="text-center mx-auto m-2 p-2">
                                <TablePagination :links="roles.links" />
                            </div>
                        </div>
                    </Content>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
