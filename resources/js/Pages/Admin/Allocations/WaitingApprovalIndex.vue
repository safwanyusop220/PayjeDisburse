<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, watch } from "vue";
import { router } from '@inertiajs/vue3'

import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import TableDataCell from "@/Components/TableDataCell.vue";
import TableDataCellView from "@/Components/TableDataCellView.vue";
import TableHeaderCellLeft from "@/Components/TableHeaderCellLeft.vue";
import TableHeaderCellRight from "@/Components/TableHeaderCellRight.vue";
import TablePagination from "@/Components/TablePagination.vue";

import Content from "@/Components/Content.vue";

// Modal Component
import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SuccessButton from "@/Components/SuccessButton.vue";

// BreadCrumb
import BreadcrumbInitial from "@/Components/BreadcrumbInitial.vue";
import BreadcrumbActive from "@/Components/BreadcrumbActive.vue";
import BreadcrumbCurrent from "@/Components/BreadcrumbCurrent.vue";

import RejectButton from "@/Components/RejectButton.vue";
import AcceptButton from "@/Components/AcceptButton.vue";
import { QuSendLater } from "@kalimahapps/vue-icons";
import { BxCheckDouble } from "@kalimahapps/vue-icons";
import { FlReceiptMoney } from "@kalimahapps/vue-icons";
import { CaViewFilled } from "@kalimahapps/vue-icons";
import { BsSearch } from "@kalimahapps/vue-icons";

const props = defineProps({
    allocations: {
      type: Array,
      required: true,
    },
    filters: Object,
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

const form = useForm({

})

const selectAll = false;
const selected = ref([]);


const select = () => {
  selected.value = [];
  if (!selectAll) {
    for (const allocation of allocations.value.data) {
      selected.value.push(allocation.id);
    }
  }
};


// Modal Approve
const showConfirmApproveAllocationModal = ref(false);

// Method for "Luluskan" button
const approve = () => {
    showConfirmApproveAllocationModal.value = true;
};

const closeModalApprove = () => {
    showConfirmApproveAllocationModal.value = false;
};

const approveAllocation = (selected) => {
    try {
        selected.forEach((id) => {
        form.put(route("allocations.approve", id), {
            onSuccess: (page) => {
                Toast.fire({
                    icon: "success",
                    title: "Allocation has successfully approved",
                });
            },
        });
    })
    showConfirmApproveAllocationModal.value = false;
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

const search = ref(props.filters.search);
const perPage = ref(5);

watch(search, (value) =>{
    router.get("/allocations/allocations-waiting-approval", {search: value, perPage: perPage.value},
    {
        preserveState: true,
        replace: true
    });
})

function getAllocations() {
    router.get(
        "/allocations/allocations-waiting-approval",
        { perPage: perPage.value, search: search.value },
        {
            preserveState: true,
            replace: true,
        }
    );
}
</script>

<template>
    <Head title="Allocation" />

    <AdminLayout>
        <div class="mt-14">
            <div class="w-full mx-auto">
                <div class="flex justify-between pt-4">
                    <nav class="text-sm font-semibold" aria-label="Breadcrumb">
                        <ol class="list-none inline-flex">
                            <li class="flex items-center">
                                <BreadcrumbInitial :href="route('allocations.index')"></BreadcrumbInitial>
                            </li>
                            <li class="flex items-center">
                                <BreadcrumbActive :href="route('allocations.index')">Allocation</BreadcrumbActive>
                            </li>
                            <li class="flex items-center">
                                <BreadcrumbCurrent>Waiting Approval</BreadcrumbCurrent>
                            </li>
                        </ol>
                    </nav>
                </div>
                <Content>
                    <form @submit.prevent="submit">
                        <div class="flex justify-between">
                            <span class="font-medium text-primary-text text-lg mt-2">Allocations Waiting Approval</span>
                            
                            <div class="flex justify-end space-x-2 -mt-2">
                                <RejectButton class="my-3">Reject</RejectButton>
                                <button
                                    :disabled="form.processing"
                                    class="my-3 flex items-center px-2 text-xs text-white font-mono bg-success-button hover:bg-success-button-hover rounded-md"
                                    @click="approve">
                                    <BxCheckDouble class="text-lg mr-0.5" />
                                    Approve
                                </button>
                            </div>
                        </div>
                        
                        <div class="border-b-2 border-gray-200 mb-2"></div>

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

                        <div class="mt-2 bg-white">
                            <Table>
                                <template #header>
                                    <TableRow>
                                        <TableHeaderCellLeft>
                                            <div class="flex items-center">
                                                <input v-model="selectAll" @click="select" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded">
                                            </div>
                                        </TableHeaderCellLeft>
                                        <TableHeaderCell>No</TableHeaderCell>
                                        <TableHeaderCell>Date Created</TableHeaderCell>
                                        <TableHeaderCell>Allocation Source</TableHeaderCell>
                                        <TableHeaderCell>Total Allocation</TableHeaderCell>
                                        <TableHeaderCell class="text-center">Status</TableHeaderCell>
                                        <TableHeaderCellRight>Action</TableHeaderCellRight>
                                    </TableRow>
                                </template>
                                <template #default>
                                    <TableRow v-for="allocation in allocations.data" :key="allocation.id" class="border-b">
                                        <TableDataCell>
                                            <div class="flex items-center">
                                                <input type="checkbox" :value="allocation.id" v-model="selected" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            </div>
                                        </TableDataCell>
                                        <TableDataCell>{{ allocation.id }}</TableDataCell>
                                        <TableDataCell>{{ formatTimestamp(allocation.created_at) }}</TableDataCell>
                                        <TableDataCell>{{ allocation.allocation_source }}</TableDataCell>
                                        <TableDataCell>RM {{ allocation.total_allocation }}</TableDataCell>
                                        <TableDataCell class="text-center">
                                            <span class="bg-blue-100 text-blue-800 text-xs font-normal mr-2 px-2.5 py-0.5 rounded-full">
                                            {{ allocation.status_id.name }}
                                            </span>
                                        </TableDataCell>
                                        <TableDataCellView class="text-center">
                                            <div class="row">
                                                <div class="col grid-cols-1 justify-items-center flex justify-center">
                                                    <a :href="route('allocations.allocationApproval', allocation.id)" class="flex items-center text-xxs-custom space-x-2 px-2 py-1 bg-blue-500 text-white hover:bg-blue-600 rounded-md">
                                                        <CaViewFilled class="text-sm mr-0.5 mt-0.5"/>
                                                        View
                                                    </a>
                                                </div>
                                            </div>
                                        </TableDataCellView>
                                    </TableRow>
                                </template>
                            </Table>
                        </div>
                        <!---Approve Modal-->
                        <Modal
                            :show="
                                showConfirmApproveAllocationModal" @close="closeModalApprove">
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
                                        @click="($event) =>approveAllocation(selected)"
                                        class="text-xs font-extralight py-3">Yes, I'm sure</SuccessButton>
                                    <SecondaryButton
                                        @click="closeModalApprove"
                                        class="text-xs font-extralight py-3">No, Cancel</SecondaryButton>
                                </div>
                            </div>
                        </Modal>
                    </form>
                    <div class="text-center mx-auto m-2 p-2">
                        <TablePagination :links="allocations.links" />
                    </div>
                </Content>
            </div>
        </div>
    </AdminLayout>
</template>
