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

import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import AddButton from "@/Components/AddButton.vue";

// BreadCrumb
import BreadcrumbInitial from "@/Components/BreadcrumbInitial.vue";
import BreadcrumbActive from "@/Components/BreadcrumbActive.vue";
import BreadcrumbCurrent from "@/Components/BreadcrumbCurrent.vue";

defineProps(['receivers'])

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


</script>

<template>
    <Head title="Payments" />

    <AdminLayout>
        <div class="py-12">
            <div class="w-full mx-auto">
                <div class="overflow-hidden">
                    <div class="flex justify-between mt-2">
                        <div class="flex items-center">
                            <span
                                v-if="receivers.data.length > 0"
                                class="font-bold text-black text-xl mr-1">
                                Requires processing - Program {{ receivers.data[0].program.name }}
                            </span>
                        </div>

                        <nav class="text-sm font-semibold" aria-label="Breadcrumb">
                            <ol class="list-none p-0 inline-flex">
                                <li class="flex items-center">
                                    <BreadcrumbInitial :href="route('payments.index')">Home</BreadcrumbInitial>
                                </li>
                                <li class="flex items-center">
                                    <BreadcrumbActive :href="route('payments.index')">Requires Processing</BreadcrumbActive>
                                </li>
                                <li class="flex items-center">
                                    <BreadcrumbCurrent>Payment</BreadcrumbCurrent>
                                </li>
                            </ol>
                        </nav>
                    </div>

                    <div class="mt-2">
                        <Table>
                            <template #header>
                                <TableRow>
                                    <TableHeaderCellLeft>No</TableHeaderCellLeft>
                                    <TableHeaderCell>Payment Date</TableHeaderCell>
                                    <TableHeaderCell>Receiver Name</TableHeaderCell>
                                    <TableHeaderCell>Program</TableHeaderCell>
                                    <TableHeaderCell>Bank</TableHeaderCell>
                                    <TableHeaderCell>Account Number</TableHeaderCell>
                                    <TableHeaderCellRightTextStart>Allocation Rate</TableHeaderCellRightTextStart>
                                </TableRow>
                            </template>
                            <template #default>
                                <TableRow v-for="receiver in receivers.data" :key="receiver.id" class="border-b">
                                    <TableDataCell>{{ receiver.id }}</TableDataCell>
                                    <TableDataCell>{{ receiver.id }}</TableDataCell>
                                    <TableDataCell>{{ receiver.name }}</TableDataCell>
                                    <TableDataCell>{{ receiver.program.name }}</TableDataCell>
                                    <TableDataCell>{{ receiver.bank.name }}</TableDataCell>
                                    <TableDataCell>{{ receiver.account_number }}</TableDataCell>
                                    <TableDataCell>RM {{ receiver.program.allocation_rate }}</TableDataCell>
                                </TableRow>
                            </template>
                        </Table>
                        <div class="text-center mx-auto m-2 p-2">
                            <TablePagination :links="receivers.links" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
