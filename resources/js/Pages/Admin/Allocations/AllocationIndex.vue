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

import Content from "@/Components/Content.vue";

import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SuccessButton from "@/Components/SuccessButton.vue";
import AddButton from "@/Components/AddButton.vue";

// BreadCrumb
import BreadcrumbInitial from "@/Components/BreadcrumbInitial.vue";
import BreadcrumbActive from "@/Components/BreadcrumbActive.vue";
import BreadcrumbCurrent from "@/Components/BreadcrumbCurrent.vue";

import { BsSearch } from "@kalimahapps/vue-icons";

const props = defineProps({
    allocations: {
        type: Object,
        required: true,
    },
    filters: Object,
});


const form = useForm({});

// Delete Allocation
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

// Delete SweetAllert
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

// Pagination

// Define the formatTimestamp method
const formatTimestamp = (timestamp) => {
    const date = new Date(timestamp); // Convert Unix timestamp to milliseconds
    const formattedDate = date.toISOString().split("T")[0];
    return formattedDate;
};

//getStatusText method
const getStatusText = (status) => {
    switch (status) {
        case 1:
            return "Sedang Diproses";
        case 2:
            return "Telah Diproses";
        case 3:
            return "Diluluskan";
        case 4:
            return "Ditolak";
        default:
            return "Unknown Status";
    }
};

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
                                <BreadcrumbInitial
                                    :href="route('allocations.index')"
                                ></BreadcrumbInitial>
                            </li>
                            <li class="flex items-center">
                                <BreadcrumbCurrent
                                    >Allocation</BreadcrumbCurrent
                                >
                            </li>
                        </ol>
                    </nav>
                </div>
                <Content>
                    <div class="mb-3 mt-1 flex justify-between">
                        <span class="mt-1.5 text-lg font-medium text-primary-text">Allocations</span>

                        <div class="flex justify-end">
                            <AddButton :href="route('allocations.create')">Create</AddButton>
                        </div>
                    </div>

                    <div class="mb-2 border-b-2 border-gray-200"></div>

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
                                    <TableHeaderCellLeft>No</TableHeaderCellLeft>
                                    <TableHeaderCell>Date Created</TableHeaderCell>
                                    <TableHeaderCell>Allocation Source</TableHeaderCell>
                                    <TableHeaderCell>Total Source</TableHeaderCell>
                                    <TableHeaderCell>Allocation Balance</TableHeaderCell>
                                    <TableHeaderCell class="text-center">Approval Status</TableHeaderCell>
                                    <TableHeaderCellRight>Action</TableHeaderCellRight>
                                </TableRow>
                            </template>
                            <template #default>
                                <TableRow
                                    v-for="allocation in allocations.data"
                                    :key="allocation.id"
                                    class="border-b">
                                    <TableDataCell>{{allocation.id}}</TableDataCell>
                                    <TableDataCell>{{formatTimestamp(allocation.created_at)}}</TableDataCell>
                                    <TableDataCell>{{allocation.allocation_source
                                    }}</TableDataCell>
                                    <TableDataCell
                                        >RM
                                        {{
                                            allocation.total_allocation
                                        }}</TableDataCell
                                    >
                                    <TableDataCell>
                                        {{
                                            allocation.allocation_balance ===
                                            null
                                                ? "-"
                                                : "RM " +
                                                  allocation.allocation_balance
                                        }}
                                    </TableDataCell>
                                    <TableDataCell class="text-center">
                                        <template v-if="allocation.status == 1">
                                            <span
                                                class="mr-2 rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-normal text-blue-800"
                                            >
                                                {{ allocation.status_id.name }}
                                            </span>
                                        </template>
                                        <template v-if="allocation.status == 3">
                                            <span
                                                class="mr-2 rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-normal text-green-800"
                                            >
                                                {{ allocation.status_id.name }}
                                            </span>
                                        </template>
                                        <template v-if="allocation.status == 4">
                                            <span
                                                class="mr-2 rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-normal text-red-800"
                                            >
                                                {{ allocation.status_id.name }}
                                            </span>
                                        </template>
                                    </TableDataCell>
                                    <TableDataCell class="">
                                        <div class="row">
                                            <div
                                                class="grid grid-cols-2 justify-items-center"
                                            >
                                                <Link
                                                    :href="
                                                        route(
                                                            'allocations.edit',
                                                            allocation.id
                                                        )
                                                    "
                                                    class="-mr-2 text-blue-400 hover:text-blue-600"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke-width="1.5"
                                                        stroke="currentColor"
                                                        class="h-4 w-4"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"
                                                        />
                                                    </svg>
                                                </Link>
                                                <button
                                                    @click="
                                                        (e) =>
                                                            confirmDeleteAllocation(
                                                                allocation.id
                                                            )
                                                    "
                                                    class="-ml-2 text-red-400 hover:text-red-600"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke-width="1.5"
                                                        stroke="currentColor"
                                                        class="h-4 w-4"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                                                        />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </TableDataCell>
                                    <!--Delete Allocation-->
                                    <Modal
                                        :show="showConfirmDeleteAllocationModal"
                                        @close="closeModal"
                                    >
                                        <div class="p-6">
                                            <svg
                                                class="mx-auto mb-4 h-12 w-12 text-gray-400 dark:text-gray-200"
                                                aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 20 20"
                                            >
                                                <path
                                                    stroke="currentColor"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                                                />
                                            </svg>
                                            <div class="flex justify-center">
                                                <h2
                                                    class="text-lg font-semibold text-slate-800"
                                                >
                                                    Are you sure you want to
                                                    reject this Allocation?
                                                </h2>
                                            </div>
                                            <div
                                                class="mt-6 flex justify-center space-x-2"
                                            >
                                                <DangerButton
                                                    @click="deleteAllocation"
                                                    class="py-3 text-xs font-extralight"
                                                    >Yes, I'm sure</DangerButton
                                                >
                                                <SecondaryButton
                                                    @click="closeModal"
                                                    class="py-3 text-xs font-extralight"
                                                    >No, Cancel</SecondaryButton
                                                >
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
