<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, watch, defineProps } from "vue";
import { router } from '@inertiajs/vue3'

import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import TableDataCell from "@/Components/TableDataCell.vue";
import TableHeaderCellLeft from "@/Components/TableHeaderCellLeft.vue";
import TableHeaderCellRight from "@/Components/TableHeaderCellRight.vue";
import TablePagination from "@/Components/TablePagination.vue";

import SpanSubmittedStatus from "@/Components/SpanSubmittedStatus.vue";
import SpanRecommendedStatus from "@/Components/SpanRecommendedStatus.vue";
import SpanApprovedStatus from "@/Components/SpanApprovedStatus.vue";
import SpanRejectedStatus from "@/Components/SpanRejectedStatus.vue";

import Content from "@/Components/Content.vue";

import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SuccessButton from "@/Components/SuccessButton.vue";
import AddButton from "@/Components/AddButton.vue";

import BreadcrumbInitial from "@/Components/BreadcrumbInitial.vue";
import BreadcrumbActive from "@/Components/BreadcrumbActive.vue";
import BreadcrumbCurrent from "@/Components/BreadcrumbCurrent.vue";

import { BsSearch } from "@kalimahapps/vue-icons";
import { SuCreate } from "@kalimahapps/vue-icons";
import { FlDelete } from "@kalimahapps/vue-icons";

const props = defineProps({
    allocations: {
        type: Object,
        required: true,
    },
    filters: Object,
});


const form = useForm({});

const showConfirmDeleteAllocationModal = ref(false);
const selectedId = ref(null);

const confirmDeleteAllocation = (id) => {
    selectedId.value = id;
    console.log(id);
    showConfirmDeleteAllocationModal.value = true;
};

const closeModal = () => {
    showConfirmDeleteAllocationModal.value = false;
};

const deleteAllocation = (id) => {
    try {
        form.delete(route("allocations.destroy", selectedId.value), {
            onSuccess: (page) => {
                Toast.fire({
                    icon: "success",
                    title: "Allocation has successfully Deleted",
                });
            },
        });
    } catch (err) {
        console.log(err);
    }
    showConfirmDeleteAllocationModal.value = false;
};

const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    width: 400,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener("mouseenter", Swal.stopTimer);
        toast.addEventListener("mouseleave", Swal.resumeTimer);
    },
});


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
    router.get("/allocations", {search: value, perPage: perPage.value},
    {
        preserveState: true,
        replace: true
    });
})

function getAllocations() {
    router.get(
        "/allocations",
        { perPage: perPage.value, search: search.value },
        {
            preserveState: true,
            replace: true,
        }
    );
}

function recommendedDialog() {
    Swal.fire({
            width: 450,
            height: 50,
            html: '<span class="text-sm">Changes to Recommended Allocations Are Not Allowed!</span>',
            icon: 'warning',
            confirmButtonText: 'Okay',
            customClass: {
                content: 'text-lg',
                confirmButton: 'px-4 py-2 text-white text-xs rounded',
            }
        });
    }
</script>

<template>
    <Head title="Allocation" />

    <AdminLayout>
        <div class="mt-14">
            <div class="mx-auto w-full">
                <div class="flex justify-between pt-4">
                    <nav class="text-sm font-semibold" aria-label="Breadcrumb">
                        <ol class="inline-flex list-none">
                            <li class="flex items-center">
                                <BreadcrumbInitial :href="route('allocations.index')"></BreadcrumbInitial>
                            </li>
                            <li class="flex items-center">
                                <BreadcrumbCurrent>
                                    Allocation
                                </BreadcrumbCurrent>
                            </li>
                        </ol>
                    </nav>
                </div>
                <Content>
                    <div class="flex justify-between">
                        <span class="font-medium text-primary-text text-lg mt-2">Allocations</span>

                        <AddButton :href="route('allocations.create')">Create</AddButton>
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

                    <div class="mt-2 bg-white">
                        <Table>
                            <template #header>
                                <TableRow>
                                    <TableHeaderCellLeft>No</TableHeaderCellLeft>
                                    <TableHeaderCell>Date Created</TableHeaderCell>
                                    <TableHeaderCell>Allocation Source</TableHeaderCell>
                                    <TableHeaderCell>Total Source</TableHeaderCell>
                                    <TableHeaderCell>Allocation Balance</TableHeaderCell>
                                    <TableHeaderCell class="text-center">Status</TableHeaderCell>
                                    <TableHeaderCellRight>Action</TableHeaderCellRight>
                                </TableRow>
                            </template>
                            <template #default>
                                <TableRow v-for="(allocation , index) in allocations.data" :key="allocation.id" class="border-b">
                                    <TableDataCell>{{ index + 1 }}</TableDataCell>
                                    <TableDataCell>{{formatTimestamp(allocation.created_at)}}</TableDataCell>
                                    <TableDataCell class="text-md">{{allocation.allocation_source}}</TableDataCell>
                                    <TableDataCell>RM{{allocation.total_allocation}}</TableDataCell>
                                    <TableDataCell>{{allocation.allocation_balance ===null? "-": "RM " +allocation.allocation_balance}}
                                    </TableDataCell>
                                    <TableDataCell class="flex justify-center">
                                        <template v-if="allocation.status == 1">
                                            <SpanSubmittedStatus :value="allocation.status_id.name"/>
                                        </template>
                                        <template v-if="allocation.status == 2">
                                            <SpanRecommendedStatus :value="allocation.status_id.name"/>
                                        </template>
                                        <template v-if="allocation.status == 3">
                                            <SpanApprovedStatus :value="allocation.status_id.name"/>
                                        </template>
                                        <template v-if="allocation.status == 4">
                                            <SpanRejectedStatus :value="allocation.status_id.name"/>
                                        </template>
                                    </TableDataCell>
                                    <TableDataCell class="">
                                        <div class="row">
                                            <div class="grid grid-cols-2 justify-items-center">
                                                <template v-if="allocation.status == 2">
                                                    <a class="-mr-2 text-blue-400 hover:text-blue-600 cursor-pointer" @click="recommendedDialog">
                                                        <SuCreate class="text-lg font-bold" />
                                                    </a>
                                                </template>
                                                <template v-else>
                                                    <Link
                                                        :href="route('allocations.edit',allocation.id)"
                                                        class="-mr-2 text-blue-400 hover:text-blue-600">
                                                        <SuCreate class="text-lg"/>
                                                    </Link>
                                                </template>
                                                <button
                                                    @click="(e) =>confirmDeleteAllocation(allocation.id)" class="-ml-2 text-red-400 hover:text-red-600">
                                                    <FlDelete class="text-lg"/>
                                                </button>
                                            </div>
                                        </div>
                                    </TableDataCell>
                                    <!--Delete Allocation-->
                                    <Modal
                                        :show="showConfirmDeleteAllocationModal"
                                        @close="closeModal">
                                        <div class="p-6">
                                            <svg
                                                class="mx-auto mb-4 h-12 w-12 text-gray-400 dark:text-gray-200"
                                                aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none">
                                                <path
                                                    stroke="currentColor"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                                                />
                                            </svg>
                                            <div class="flex justify-center">
                                                <h2 class="text-xs font-normal text-slate-800">
                                                    Are You Sure Want To
                                                    Reject This Allocation?
                                                </h2>
                                            </div>
                                            <div class="mt-6 flex justify-center space-x-2">
                                                <DangerButton
                                                    @click="deleteAllocation"
                                                    class="text-xs font-extralight">Yes, I'm sure</DangerButton>
                                                <SecondaryButton
                                                    @click="closeModal"
                                                    class="text-xs font-extralight">No, Cancel</SecondaryButton>
                                            </div>
                                        </div>
                                    </Modal>
                                </TableRow>
                            </template>
                        </Table>
                    </div>

                    <div class="m-2 mx-auto p-2 text-center">
                        <TablePagination :links="allocations.links" />
                    </div>
                </Content>
            </div>
        </div>
    </AdminLayout>
</template>
