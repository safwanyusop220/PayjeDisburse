<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, watch, defineProps } from "vue";
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

// Icons
import RejectButton from "@/Components/RejectButton.vue";
import AcceptButton from "@/Components/AcceptButton.vue";
import { BxCheckDouble } from "@kalimahapps/vue-icons";
import { CaViewFilled } from "@kalimahapps/vue-icons";

// Icons
import { BsSearch } from "@kalimahapps/vue-icons";

const props = defineProps({
    programs: {
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

const search = ref(props.filters.search);
const perPage = ref(5);

watch(search, (value) =>{
    router.get("/programs/programs-waiting-approval", {search: value, perPage: perPage.value},
    {
        preserveState: true,
        replace: true
    });
})

function getPrograms() {
    router.get(
        "/programs/programs-waiting-approval",
        { perPage: perPage.value, search: search.value },
        {
            preserveState: true,
            replace: true,
        }
    );
}

</script>

<template>
    <Head title="Program Waiting Approval" />

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
                                <BreadcrumbActive :href="route('programs.index')">Program</BreadcrumbActive>
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
                            <span class="font-medium text-primary-text text-lg mt-2">Program Approval</span>
                            <!--Button Approval-->
                            <div class="flex justify-end -mt-2 space-x-2">
                                <RejectButton class="my-3">Reject</RejectButton>
                                <button
                                    :disabled="form.processing"
                                    class="my-3 flex items-center py-2 px-2 text-xs text-white font-mono bg-success-button hover:bg-success-button-hover rounded-md"
                                    @click="approve()">
                                    <BxCheckDouble class="text-lg mr-0.5" />
                                    Approve
                                </button>
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
                                @change="getPrograms"
                                class="rounded-md bg-primary-50 text-xs border-none focus:ring-primary-100">
                                <option value="1">10</option>
                                <option value="2">20</option>
                                <option value="5">50</option>
                                <option value="10">100</option>
                            </select>
                        </div>

                        <!--Table-->
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
                                            <TableDataCellView class="">
                                                <div class="row">
                                                    <div class="col grid-cols-1 justify-items-center flex justify-center">
                                                        <Link :href="route('programs.programApproval', program.id)" class="flex items-center text-xxs-custom space-x-2 px-2 py-1 bg-blue-500 text-white hover:bg-blue-600 rounded-md">
                                                            <CaViewFilled class="text-sm mr-0.5 mt-0.5"/>
                                                            View
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
                                        </TableDataCellView>
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
                </Content>
            </div>
        </div>
    </AdminLayout>
</template>
