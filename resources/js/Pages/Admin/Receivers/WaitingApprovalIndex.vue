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

const props = defineProps({
    receivers: {
      type: Array,
      required: true,
    },
    filters: Object,
  });

const selected = ref([]);

const form = useForm({

})

const showConfirmApproveModal = ref(false);

const approve = () => {
    showConfirmApproveModal.value = true;
};

const closeModalApprove = () => {
    showConfirmApproveModal.value = false;
};

const approveReceiver = (selected) => {
    try {
        selected.forEach((id) => {
        form.put(route("receivers.approve", id), {
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

const search = ref(props.filters.search);
const perPage = ref(5);

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

watch(search, (value) =>{
    router.get("/receivers-waiting-approval", {search: value, perPage: perPage.value},
    {
        preserveState: true,
        replace: true
    });
})

function getReceivers() {
    router.get(
        "/receivers-waiting-approval",
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
                                <BreadcrumbInitial :href="route('receivers.index')"></BreadcrumbInitial>
                            </li>
                            <li class="flex items-center">
                                <BreadcrumbActive :href="route('receivers.index')">Receiver</BreadcrumbActive>
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
                            <span class="font-medium text-primary-text text-lg mt-2">Receiver Waiting Approval</span>
                            
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
                                @change="getReceivers"
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
                                        <TableHeaderCellLeft>
                                            <div class="flex items-center">
                                                <input v-model="selectAll" @click="select" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            </div>
                                        </TableHeaderCellLeft>
                                        <TableHeaderCell class="w-1/12">No</TableHeaderCell>
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
                                        <TableDataCell>
                                            <div class="flex items-center">
                                                <input type="checkbox" :value="receiver.id" v-model="selected" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            </div>
                                        </TableDataCell>
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
                                        <TableDataCellView class="w-1/12">
                                            <div class="row">
                                                <div class="grid grid-cols-2 justify-items-center">
                                                    
                                                    <Link :href="route('receivers.receiverApproval', receiver.id)" class="flex items-center text-xxs-custom space-x-2 px-2 py-1 bg-blue-500 text-white hover:bg-blue-600 rounded-md">
                                                        <CaViewFilled class="text-sm mr-0.5 mt-0.5"/>
                                                        View
                                                    </Link>
                                                </div>
                                            </div>
                                        </TableDataCellView>
                                    </TableRow>
                                </template>
                            </Table>
                            <div class="text-center mx-auto m-2 p-2">
                                <TablePagination :links="receivers.links" />
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
                                        @click="($event) =>approveReceiver(selected)"
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
