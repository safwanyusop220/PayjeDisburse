<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from "vue";

import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import TableDataCell from "@/Components/TableDataCell.vue";
import TableHeaderCellLeft from "@/Components/TableHeaderCellLeft.vue";
import TableHeaderCellRightTextStart from "@/Components/TableHeaderCellRightTextStart.vue";
import TablePagination from "@/Components/TablePagination.vue";

import Content from "@/Components/Content.vue";

import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';


import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SuccessButton from "@/Components/SuccessButton.vue";
import BackButton from "@/Components/BackButton.vue";
import Dropdown from '@/Components/Dropdown.vue';

// BreadCrumb
import BreadcrumbInitial from "@/Components/BreadcrumbInitial.vue";
import BreadcrumbActive from "@/Components/BreadcrumbActive.vue";
import BreadcrumbCurrent from "@/Components/BreadcrumbCurrent.vue";

import { SuCreate } from "@kalimahapps/vue-icons";
import { McFileExportLine } from "@kalimahapps/vue-icons";
import { CaDocumentImport } from "@kalimahapps/vue-icons";

defineProps([
    'requiredProcessPayments',
    'inProgressPayments',
    'successTransaction',
    'failedTransaction',
    'requiredProcessPaymentsCount',
])

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



// Define the formatTimestamp method
const formatTimestamp = (timestamp) => {
    const date = new Date(timestamp); // Convert Unix timestamp to milliseconds
    const formattedDate = date.toISOString().split('T')[0];
    return formattedDate;
}

//getStatusText method 
const getStatusText = (status) => {
    switch (status) {
        case 1:
            return 'Sedang Diproses';
        case 2:
            return 'Telah Diproses';
        case 3:
            return 'Diluluskan';
        case 4:
            return 'Ditolak';
        default:
            return 'Unknown Status';
    }
};

//getProgramTypeText method 
const getTypeText = (type) => {
    switch (type) {
        case 1:
            return 'Individu';
        case 2:
            return 'Kumpulan';
        case 3:
            return 'Pecahan';
        case 4:
            return 'Kelompok';
        default:
            return 'Unknown Status';
    }
};

const form = useForm({
    select_users_file : ""
})
// Modal Approve
const showConfirmCreateAllocationModal = ref(false)

// Method for "Luluskan" button
const create = () => {
    const mainFormUsers = document.getElementById('mainFormUsers');
        const data = new FormData(mainFormUsers);
    form.post(route('payments.import', data));
};


const closeModalCreate = () => {
    showConfirmCreateAllocationModal.value = false;
}

const createProgram = () => {
    const mainFormUsers = document.getElementById('mainFormUsers');
        const data = new FormData(mainFormUsers);
    try{
        form.post(route('payments.import', data), {
            onSuccess: (page) => {
            Toast.fire({
                icon: 'success',
                title: 'Program has successfully approved',
            })
            },
        })
    }catch (err){
        console.log(err)
    }
};



</script>

<template>
    <Head title="Payments" />

    <AdminLayout>
        <div class="mt-14">
            <div class="w-full mx-auto">
                <div class="flex justify-between pt-4">
                    <nav class="text-sm font-semibold" aria-label="Breadcrumb">
                        <ol class="list-none p-0 inline-flex">
                            <li class="flex items-center">
                                <BreadcrumbInitial :href="route('payments.index')">Home</BreadcrumbInitial>
                            </li>
                            <li class="flex items-center">
                                <BreadcrumbCurrent>Requires Processing</BreadcrumbCurrent>
                            </li>
                        </ol>
                    </nav>
                </div>
                <Content>
                    <div>
                        <div class="mb-4 mt-4 flex justify-between">
                            <div>
                                <ul class="flex flex-wrap  text-xs font-medium text-center" id="myTab" data-tabs-toggle="#myTabContent" role="tablist">
                                    <li class="" role="presentation">
                                        <button class="rounded-l-lg inline-block w-full p-3 bg-primary-100 hover:bg-primary-200" id="profile-tab" data-tabs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Required Process</button>
                                    </li>
                                    <li class="" role="presentation">
                                        <button class="inline-block w-full p-3 bg-primary-100  hover:bg-primary-200" id="dashboard-tab" data-tabs-target="#dashboard" type="button" role="tab" aria-controls="dashboard" aria-selected="false">In Progress</button>
                                    </li>
                                    <li class="" role="presentation">
                                        <button class="inline-block w-full p-3 bg-primary-100  hover:bg-primary-200" id="settings-tab" data-tabs-target="#settings" type="button" role="tab" aria-controls="settings" aria-selected="false">Success Transaction</button>
                                    </li>
                                    <li role="presentation">
                                        <button class="rounded-r-lg inline-block w-full p-3 bg-primary-100  hover:bg-primary-200" id="contacts-tab" data-tabs-target="#contacts" type="button" role="tab" aria-controls="contacts" aria-selected="false">Failed Transaction</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="flex justify-between">
                                <!--Tabs<form id="mainFormUsers" enctype="multipart/form-data">
                                    
                                    <input type="file" name="select_users_file"/>

                                    <input type="submit" @click.prevent="create" name="upload" class="flex items-center py-2 px-2 text-xs text-white font-mono bg-success-button hover:bg-success-button-hover rounded-md">

                                </form>-->
                                <Modal :show="showConfirmCreateAllocationModal" @close="closeModalCreate">
                                    <div class="p-6">
                                        <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg>
                                        <div class="flex justify-center">
                                            <h2 class="text-lg font-semibold text-slate-800">
                                            Are you sure you want add this program?
                                            </h2>
                                        </div>
                                        <div class="mt-6 flex justify-center space-x-2">
                                            <SuccessButton @click="createProgram" class="text-xs font-extralight py-3">Yes, I'm sure</SuccessButton>
                                            <SecondaryButton @click="closeModalCreate" class="text-xs font-extralight py-3">No, Cancel</SecondaryButton>
                                        </div>
                                    </div>
                                </Modal>
                                <a :href="route('payments.export')" class="button ml-4 flex items-center px-3 text-xs text-white font-mono bg-primary-button hover:bg-primary-button-hover rounded-md">
                                    <McFileExportLine class="text-lg mr-0.5" />
                                    Exports
                                </a>
                            </div>
                        </div>
                        <div id="myTabContent">

                            <div class="hidden rounded-lg bg-gray-50" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="mt-2">
                                    <Table>
                                        <template #header>
                                            <TableRow>
                                                <TableHeaderCellLeft>No</TableHeaderCellLeft>
                                                <TableHeaderCell>Payment Date</TableHeaderCell>
                                                <TableHeaderCell>Program/Receiver Name</TableHeaderCell>
                                                <TableHeaderCellRightTextStart>Total Receiver</TableHeaderCellRightTextStart>
                                            </TableRow>
                                        </template>
                                        <template #default>
                                            <TableRow v-for="payment in requiredProcessPayments.data" :key="payment.id" class="border-b">
                                                <TableDataCell>{{ payment.id }}</TableDataCell>
                                                <TableDataCell> {{ payment.payment_date }}</TableDataCell>
                                                <TableDataCell v-if="payment.receiver_id === null">
                                                    {{ payment.program.name }}
                                                </TableDataCell>
                                                <TableDataCell v-else>
                                                    {{ payment.receiver.name }}
                                                </TableDataCell>
                                                <TableDataCell v-if="payment.receiver_id === null">
                                                    <Link :href="route('payments.programReceiverList', payment.program.id)" class="underline text-blue-600 ">
                                                        {{ payment.total_receiver }}
                                                    </Link>
                                                </TableDataCell>
                                                <TableDataCell v-else>
                                                    1
                                                </TableDataCell>
                                            </TableRow>
                                        </template>
                                    </Table>
                                    <div class="text-center mx-auto m-2 p-2">
                                        <TablePagination :links="requiredProcessPayments.links" />
                                    </div>
                                </div>
                            </div>
                            <div class="hidden rounded-lg bg-gray-50 dark:bg-gray-800" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
                                <div class="mt-2">
                                    <Table>
                                        <template #header>
                                            <TableRow>
                                                <TableHeaderCellLeft>No</TableHeaderCellLeft>
                                                <TableHeaderCell>Payment Date</TableHeaderCell>
                                                <TableHeaderCell>Program/Receiver Name</TableHeaderCell>
                                                <TableHeaderCellRightTextStart>Total Receiver</TableHeaderCellRightTextStart>
                                            </TableRow>
                                        </template>
                                        <template #default>
                                            <TableRow v-for="payment in inProgressPayments.data" :key="payment.id" class="border-b">
                                                <TableDataCell>{{ payment.id }}</TableDataCell>
                                                <TableDataCell> {{ payment.payment_date }}</TableDataCell>
                                                <TableDataCell v-if="payment.receiver_id === null">
                                                    {{ payment.program.name }}
                                                </TableDataCell>
                                                <TableDataCell v-else>
                                                    {{ payment.receiver.name }}
                                                </TableDataCell>
                                                <TableDataCell v-if="payment.receiver_id === null">
                                                    <Link :href="route('payments.programReceiverList', payment.program.id)" class="underline text-blue-600 ">
                                                        {{ payment.total_receiver }}
                                                    </Link>
                                                </TableDataCell>
                                                <TableDataCell v-else>
                                                    {{ payment.total_receiver }}
                                                </TableDataCell>
                                            </TableRow>
                                        </template>
                                    </Table>
                                    <div class="text-center mx-auto m-2 p-2">
                                        <TablePagination :links="inProgressPayments.links" />
                                    </div>
                                </div>
                            </div>
                            <div class="hidden rounded-lg bg-gray-50 dark:bg-gray-800" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                                <div class="mt-2">
                                    <Table>
                                        <template #header>
                                            <TableRow>
                                                <TableHeaderCellLeft>No</TableHeaderCellLeft>
                                                <TableHeaderCell>Payment Date</TableHeaderCell>
                                                <TableHeaderCell>Program/Receiver Name</TableHeaderCell>
                                                <TableHeaderCellRightTextStart>Total Receiver</TableHeaderCellRightTextStart>
                                            </TableRow>
                                        </template>
                                        <template #default>
                                            <TableRow v-for="payment in successTransaction.data" :key="payment.id" class="border-b">
                                                <TableDataCell>{{ payment.id }}</TableDataCell>
                                                <TableDataCell> {{ payment.payment_date }}</TableDataCell>
                                                <TableDataCell v-if="payment.receiver_id === null">
                                                    {{ payment.program.name }}
                                                </TableDataCell>
                                                <TableDataCell v-else>
                                                    {{ payment.receiver.name }}
                                                </TableDataCell>
                                                <TableDataCell v-if="payment.receiver_id === null">
                                                    <Link :href="route('payments.programReceiverList', payment.program.id)" class="underline text-blue-600 ">
                                                        {{ payment.total_receiver }}
                                                    </Link>
                                                </TableDataCell>
                                                <TableDataCell v-else>
                                                    {{ payment.total_receiver }}
                                                </TableDataCell>
                                            </TableRow>
                                        </template>
                                    </Table>
                                    <div class="text-center mx-auto m-2 p-2">
                                        <TablePagination :links="successTransaction.links" />
                                    </div>
                                </div>
                            </div>
                            <div class="hidden rounded-lg bg-gray-50 dark:bg-gray-800" id="contacts" role="tabpanel" aria-labelledby="contacts-tab">
                                <div class="mt-2">
                                    <Table>
                                        <template #header>
                                            <TableRow>
                                                <TableHeaderCellLeft>No</TableHeaderCellLeft>
                                                <TableHeaderCell>Payment Date</TableHeaderCell>
                                                <TableHeaderCell>Program/Receiver Name</TableHeaderCell>
                                                <TableHeaderCellRightTextStart>Total Receiver</TableHeaderCellRightTextStart>
                                            </TableRow>
                                        </template>
                                        <template #default>
                                            <TableRow v-for="payment in failedTransaction.data" :key="payment.id" class="border-b">
                                                <TableDataCell>{{ payment.id }}</TableDataCell>
                                                <TableDataCell> {{ payment.payment_date }}</TableDataCell>
                                                <TableDataCell v-if="payment.receiver_id === null">
                                                    {{ payment.program.name }}
                                                </TableDataCell>
                                                <TableDataCell v-else>
                                                    {{ payment.receiver.name }}
                                                </TableDataCell>
                                                <TableDataCell v-if="payment.receiver_id === null">
                                                    <Link :href="route('payments.programReceiverList', payment.program.id)" class="underline text-blue-600 ">
                                                        {{ payment.total_receiver }}
                                                    </Link>
                                                </TableDataCell>
                                                <TableDataCell v-else>
                                                    {{ payment.total_receiver }}
                                                </TableDataCell>
                                            </TableRow>
                                        </template>
                                    </Table>
                                    <div class="text-center mx-auto m-2 p-2">
                                        <TablePagination :links="failedTransaction.links" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </Content>
            </div>
        </div>
    </AdminLayout>
</template>
