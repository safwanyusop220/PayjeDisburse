<script setup>
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

// Modal Component
import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SuccessButton from "@/Components/SuccessButton.vue";

// BreadCrumb
import BreadcrumbInitial from "@/Components/BreadcrumbInitial.vue";
import BreadcrumbActive from "@/Components/BreadcrumbActive.vue";
import BreadcrumbCurrent from "@/Components/BreadcrumbCurrent.vue";

// Icons
import RejectButton from "@/Components/RejectButton.vue";
import AcceptButton from "@/Components/AcceptButton.vue";
import { BxCheckDouble } from "@kalimahapps/vue-icons";


defineProps(['programs'])

// Define the formatTimestamp method
const formatTimestamp = (timestamp) => {
    const date = new Date(timestamp); // Convert Unix timestamp to milliseconds
    const formattedDate = date.toISOString().split('T')[0];
    return formattedDate;
}

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


const select_all = ref(false);
const selected = ref([]);

console.log(selected)

const select = () => {
    this.selected = []
    if(!this.select_all){
        for(let i in this.data){
            this.selected.push(this.data[i].id);
        }
    }
};

const form = useForm({
    selected: [],
    selectAll: false,
})

// Modal Approve
const showConfirmApproveModal = ref(false);

// Method for "Luluskan" button
const approve = () => {
    showConfirmApproveModal.value = true;
};

const closeModalApprove = () => {
    showConfirmApproveModal.value = false;
};

const approveProgram = (selected) => {
    try {
        selected.forEach((id) => {
        form.put(route("programs.approve", id), {
            onSuccess: (page) => {
                Toast.fire({
                    icon: "success",
                    title: "Allocation has successfully approved",
                });
            },
        });
    });
    showConfirmApproveModal.value = false;
    } catch (err) {
        console.log(err);
    }
};

// Approve SweetAllert
const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    width: 430,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener("mouseenter", Swal.stopTimer);
        toast.addEventListener("mouseleave", Swal.resumeTimer);
    },
});

</script>

<template>
    <Head title="Program Waiting Approval" />

    <AdminLayout>
        <div class="py-12">
            <div class="w-full mx-auto">
                <div class="overflow-hidden">
                    <div class="flex justify-between mt-2">
                        <div class="flex items-center">
                            <span class="font-semibold text-black text-xl mr-1">Installment Program</span>
                        </div>

                        <nav class="text-sm font-semibold" aria-label="Breadcrumb">
                            <ol class="list-none p-0 inline-flex">
                                <li class="flex items-center">
                                    <BreadcrumbInitial :href="route('programs.index')">Home</BreadcrumbInitial>
                                </li>
                                <li class="flex items-center">
                                    <BreadcrumbActive :href="route('programs.index')">Program</BreadcrumbActive>
                                </li>
                                <li class="flex items-center">
                                    <BreadcrumbCurrent>Waiting Approval</BreadcrumbCurrent>
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <form @submit.prevent="submit">
                        <!--Button Approval-->
                        <div class="flex justify-end -mb-3 space-x-2">
                            <RejectButton class="my-3">Reject</RejectButton>
                            <button
                                :disabled="form.processing"
                                class="my-3 flex items-center py-2 px-2 text-xs text-white font-mono bg-success-button hover:bg-success-button-hover rounded-md"
                                @click="approve()">
                                <BxCheckDouble class="text-lg mr-0.5" />
                                Approve
                            </button>
                        </div>
                        <div class="mt-2">
                            <Table>
                                <template #header>
                                    <TableRow>
                                        <TableHeaderCellLeft>
                                                <div class="flex items-center">
                                                    <input v-model="select_all" @click="select" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                </div>
                                            </TableHeaderCellLeft>
                                            <TableHeaderCell>No</TableHeaderCell>
                                        <TableHeaderCell>Date Created</TableHeaderCell>
                                        <TableHeaderCell>Program Name</TableHeaderCell>
                                        <TableHeaderCell>Program Type</TableHeaderCell>
                                        <TableHeaderCell>Allocation Rate</TableHeaderCell>
                                        <TableHeaderCell>Approval Status</TableHeaderCell>
                                        <TableHeaderCellRight>Action</TableHeaderCellRight>
                                    </TableRow>
                                </template>
                                <template #default>
                                    <TableRow v-for="program in programs.data" :key="program.id" class="border-b">
                                            <TableDataCell>
                                                <div class="flex items-center">
                                                    <input type="checkbox" :value="program.id" v-model="selected" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                </div>
                                            </TableDataCell>
                                            <TableDataCell>{{ program.id }}</TableDataCell>
                                            <TableDataCell>{{ formatTimestamp(program.created_at) }}</TableDataCell>
                                            <TableDataCell>{{ program.name }}</TableDataCell>
                                            <TableDataCell> {{ getTypeText(program.type_id) }}</TableDataCell>
                                            <TableDataCell> RM{{ program.allocation_rate }}</TableDataCell>
                                            <TableDataCell>
                                                <span class="bg-blue-100 text-blue-800 text-xs font-normal mr-2 px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">
                                                {{ program.status_id.name }}
                                                </span>
                                            </TableDataCell>
                                            <TableDataCell class="">
                                                <div class="row">
                                                    <div class="col grid-cols-1 justify-items-center flex justify-center">
                                                        <Link :href="route('programs.createInstallment', program.id)" class="text-green-400 hover:text-green-600">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                            </svg>
                                                        </Link>
                                                    </div>
                                                </div>
                                                <Modal :show="showConfirmDeleteAllocationModal" @close="closeModal">
                                                    <div class="p-6">
                                                        <h2 class="text-lg font-semibold text-slate-800">
                                                            Are you sure to delete this Programs?
                                                        </h2>
                                                        <div class="mt-6 flex space-x-2">
                                                            <DangerButton @click="$event => deleteAllocation(allocation.id)">Delete</DangerButton>
                                                            <SecondaryButton @click="closeModal">Cancel</SecondaryButton>
                                                        </div>
                                                    </div>
                                                </Modal>
                                        </TableDataCell>
                                    </TableRow>
                                </template>
                                
                            </Table>
                            <div class="text-center mx-auto m-2 p-2">
                                <TablePagination :links="programs.links" />
                            </div>
                        </div>
                        <!---Approve Modal-->
                        <Modal
                            :show="
                                showConfirmApproveModal" @close="closeModalApprove">
                            <div class="p-6">
                                <svg
                                    class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200"
                                    aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 20 20">
                                    <path
                                        stroke="currentColor"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                                    />
                                </svg>
                                <div class="flex justify-center">
                                    <h2 class="text-lg font-semibold text-slate-800">
                                        Are you sure you want to
                                        Approve this Allocation?
                                    </h2>
                                </div>
                                <div class="mt-6 flex justify-center space-x-2">
                                    <SuccessButton
                                        @click="($event) =>approveProgram(selected)"
                                        class="text-xs font-extralight py-3">Yes, I'm sure</SuccessButton>
                                    <SecondaryButton
                                        @click="closeModalApprove"
                                        class="text-xs font-extralight py-3">No, Cancel</SecondaryButton>
                                </div>
                            </div>
                        </Modal>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
