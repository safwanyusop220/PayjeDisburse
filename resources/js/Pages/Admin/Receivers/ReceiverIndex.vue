<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, watch, defineProps } from "vue";
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

const props = defineProps({
    receivers: {
        type: Object,
        required: true,
    },
    filters: Object,
});

const form = useForm({})


// Delete Receiver
const showConfirmDeleteModal = ref(false)
const selectedId = ref(null);


const confirmDelete = (id) => {
    selectedId.value = id;
    console.log(id)
    showConfirmDeleteModal.value = true;
}

const closeModal = () => {
    showConfirmDeleteModal.value = false;
}

const deleteReceiver = () => {
    try{
        form.delete(route('receivers.destroy', selectedId.value), {
            onSuccess: (page) => {
            Toast.fire({
                icon: 'success',
                title: 'Receiver has successfully Deleted'
            })
            },
        })
    }catch (err){
        console.log(err)
    }
    showConfirmDeleteModal.value = false;
};

// Delete SweetAllert
const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  width: 400,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer);
    toast.addEventListener('mouseleave', Swal.resumeTimer);
  },
});

const search = ref(props.filters.search);
const perPage = ref(5);

watch(search, (value) =>{
    router.get("/receivers", {search: value, perPage: perPage.value},
    {
        preserveState: true,
        replace: true
    });
})

function getAllocations() {
    router.get(
        "/receivers",
        { perPage: perPage.value, search: search.value },
        {
            preserveState: true,
            replace: true,
        }
    );
}


</script>

<template>
    <Head title="Receivers" />

    <AdminLayout>
        <div class="mt-14">
            <div class="w-full mx-auto">
                <div class="flex justify-between pt-4">
                    <nav class="text-sm font-semibold" aria-label="Breadcrumb">
                        <ol class="list-none p-0 inline-flex">
                            <li class="flex items-center">
                                <BreadcrumbInitial :href="route('programs.index')"></BreadcrumbInitial>
                            </li>
                            <li class="flex items-center">
                                <BreadcrumbCurrent>Receiver</BreadcrumbCurrent>
                            </li>
                        </ol>
                    </nav>
                </div>

                <Content>
                    <div class="flex justify-between mt-1 mb-3">
                        <span class="font-medium text-primary-text text-lg mt-1.5">Receivers</span>

                        <div class="flex justify-end">
                            <AddButton :href="route('receivers.create')">Create</AddButton>
                        </div>
                    </div>
                
                    <div class="border-b-2 border-gray-200 mb-5"></div>

                    <div class="mb-2 flex justify-between">
                        <BsSearch class="absolute ml-2 text-sm text-gray-500 mt-2" />
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Search"
                            class="pl-7 w-1/3 rounded-md bg-primary-50 text-xs border-none focus:ring-primary-100"
                        />

                        <select
                            v-model="perPage"
                            @change="getAllocations"
                            class="rounded-md bg-primary-50 text-xs border-none focus:ring-primary-100">
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
                                    <TableHeaderCellLeft class="w-1/12">No</TableHeaderCellLeft>
                                    <TableHeaderCell class="w-2/12">Receiver</TableHeaderCell>
                                    <TableHeaderCell class="w-1/12">No IC</TableHeaderCell>
                                    <TableHeaderCell class="w-3/12">Address</TableHeaderCell>
                                    <TableHeaderCell class="w-1/12">Program</TableHeaderCell>
                                    <TableHeaderCell class="w-2/12 text-center">Status</TableHeaderCell>
                                    <TableHeaderCell class="w-2/12">Bank</TableHeaderCell>
                                    <TableHeaderCellRight class="w-12/12">Action</TableHeaderCellRight>
                                </TableRow>
                            </template>
                            <template #default>
                                <TableRow v-for="receiver in receivers.data" :key="receiver.id" class="border-b">
                                    <TableDataCell class="w-1/12">{{ receiver.id }}</TableDataCell>
                                    <TableDataCell class="w-2/12">
                                        <div class="col">
                                            <div class="row">
                                                <div>
                                                    {{ receiver.name }}
                                                </div>
                                                <div>
                                                    {{ receiver.email }}
                                                </div>
                                                <div>
                                                    {{ receiver.phone_number }}
                                                </div>
                                            </div>
                                        </div>
                                    </TableDataCell>
                                    <TableDataCell class="w-1/12">{{ receiver.identification_number }}</TableDataCell>
                                    <TableDataCell class="w-3/12">{{ receiver.address }}</TableDataCell>
                                    <TableDataCell class="w-1/12">{{ receiver.program.name }}</TableDataCell>
                                    <TableDataCell class="w-12/12 text-center">
                                        <span
                                        
                                            :class="{
                                                'bg-blue-100 text-blue-800 text-xs font-thin px-2 py-0.5 rounded-full':
                                                receiver.status === 1,
                                                'bg-yellow-100 text-yellow-400 text-xs font-thin px-3 py-0.5 rounded-full':
                                                receiver.status === 2,
                                                'bg-green-200 text-green-400 text-xs font-thin px-5 py-0.5 rounded-full':
                                                receiver.status === 3,
                                                'bg-red-100 text-red-500 text-xs font-thin px-7 py-0.5 rounded-full':
                                                receiver.status === 4,
                                            }"
                                            >
                                            {{ receiver.status_id.name }}
                                        </span>
                                    </TableDataCell> 
                                    <TableDataCell class="w-2/12">
                                        <div class="col">
                                            <div class="row">
                                                <div>
                                                    {{ receiver.bank.name }}
                                                </div>
                                                <div>
                                                    {{ receiver.account_number }}
                                                </div>
                                            </div>
                                        </div>
                                    </TableDataCell>    
                                                                
                                    <!---<TableDataCell> {{ allocation.allocation_balance === null ? '-' : 'RM '+ allocation.allocation_balance }}

                                    </TableDataCell>-->
                                    <TableDataCell class="w-1/12">
                                        <div class="row">
                                            <div class="grid grid-cols-2 justify-items-center">
                                                <Link :href="route('receivers.edit', receiver.id)" class="text-blue-400 hover:text-blue-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                    </svg>
                                                </Link>
                                                <button @click="(e)=> confirmDelete(receiver.id)" class="text-red-400 hover:text-red-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>

                                        <!--Delete Receiver-->
                                        <Modal :show="showConfirmDeleteModal" @close="closeModal">
                                            <div class="p-6">
                                                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                </svg>
                                                <div class="flex justify-center">
                                                    <h2 class="text-lg font-semibold text-slate-800">
                                                    Are you sure you want to delete this Receiver?
                                                    </h2>
                                                </div>
                                                <div class="mt-6 flex justify-center space-x-2">
                                                    <DangerButton @click="deleteReceiver" class="text-xs font-extralight py-3">Yes, I'm sure</DangerButton>
                                                    <SecondaryButton @click="closeModal" class="text-xs font-extralight py-3">No, Cancel</SecondaryButton>
                                                </div>
                                            </div>
                                        </Modal>
                                    </TableDataCell>
                                </TableRow>
                            </template>
                        </Table>
                        <div class="text-center mx-auto m-2 p-2">
                            <TablePagination :links="receivers.links" />
                        </div>
                    </div>
                </Content>
            </div>
        </div>
    </AdminLayout>
</template>
