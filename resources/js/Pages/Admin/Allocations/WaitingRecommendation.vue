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
import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SuccessButton from "@/Components/SuccessButton.vue";

import SpanSubmittedStatus from "@/Components/SpanSubmittedStatus.vue";

import BreadcrumbInitial from "@/Components/BreadcrumbInitial.vue";
import BreadcrumbActive from "@/Components/BreadcrumbActive.vue";
import BreadcrumbCurrent from "@/Components/BreadcrumbCurrent.vue";

import { BxCheckDouble } from "@kalimahapps/vue-icons";
import { CaViewFilled } from "@kalimahapps/vue-icons";
import { BsSearch } from "@kalimahapps/vue-icons";
import { IcCancel } from "@kalimahapps/vue-icons";

import { ClCircleWarning } from "@kalimahapps/vue-icons";

const props = defineProps({
    allocations: {
      type: Array,
      required: true,
    },
    filters: Object,
  });

  const formatTimestamp = (timestamp) => {
  const date = new Date(timestamp);

  const day = date.getDate().toString().padStart(2, '0');
  const month = (date.getMonth() + 1).toString().padStart(2, '0'); 
  const year = date.getFullYear().toString().slice(-2);

  const formattedDate = `${day}/${month}/${year}`;

  return formattedDate;
}

const form = useForm({
    reject_reason: props.allocation?.reject_reason,
})

const selectAll = false;
const selected = ref([]);
const totalSelected = ref(0); 

const select = () => {
  selected.value = [];
  totalSelected.value = 0;

  if (selectAll) {
    for (const allocation of allocations.data) {
      selected.value.push(allocation.id);
      totalSelected.value++; 
    }
  } else {
    for (const allocation of allocations.data) {
      if (allocation.is_selected === true) {
        selected.value.push(allocation.id);
        totalSelected.value++; 
      }
    }
  }
};

watch(selected, () => {
  totalSelected.value = selected.value.length;
});

const showConfirmApproveAllocationModal = ref(false);

const approve = () => {
    showConfirmApproveAllocationModal.value = true;
};

const closeModalApprove = () => {
    showConfirmApproveAllocationModal.value = false;
};

const approveAllocation = (selectedItems) => {
  try {
    selectedItems.forEach((id) => {
      form.put(route("allocations.approveRecommendation", id), {
        onSuccess: (page) => {
            Swal.fire({
                width: 400,
                height: 100,
                html: '<span class="text-sm">Allocation Has Successfully Been Recommended!</span>',
                icon: 'success',
                confirmButtonText: 'Okay',
                customClass: {
                    content: 'text-lg',
                    confirmButton: 'px-4 py-2 text-white text-xs rounded',
                }
            });
        },
      });
    });
    selected.value = [];
    totalSelected.value = 0;
    showConfirmApproveAllocationModal.value = false;
  } catch (err) {
    console.log(err);
  }
};

const showConfirmRejectAllocationModal = ref(false);

const reject = () => {
    showConfirmRejectAllocationModal.value = true;
};

const closeModalReject = () => {
    showConfirmRejectAllocationModal.value = false;
};

const rejectAllocation = async (selectedItems) => {
    const { value } = await Swal.fire({
        input: "textarea",
        inputLabel: "Reason for reject",
        inputPlaceholder: "Type your message here...",
        inputAttributes: {
            "aria-label": "Type your message here",
        },
        showCancelButton: false,
        allowOutsideClick: false,
        preConfirm: (value) => {
            if (!value.trim()) {
                Swal.showValidationMessage("Message is required");
            }
            showConfirmRejectAllocationModal.value = false;
            return value;
        },
    });

    console.log(value);

    form.reject_reason = value;

    try {
        selectedItems.forEach((id) => {
            form.post(route("allocations.rejectRecommendation", id), {
            onSuccess: (page) => {
                Toast.fire({
                icon: "success",
                title: "Allocation Has Successfully Rejected",
                });
                selected.value = [];
                totalSelected.value = 0;
                showConfirmRejectAllocationModal.value = false;
            },
            });
        });
    } catch (err) {
        console.error(err);
        Swal.fire(
            "Error!",
            "There was an error while rejecting the allocation.",
            "error"
        );
    }
};

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
    router.get("/allocations/waiting-recommendation", {search: value, perPage: perPage.value},
    {
        preserveState: true,
        replace: true
    });
})

function getAllocations() {
    router.get(
        "/allocations/waiting-recommendation",
        { perPage: perPage.value, search: search.value },
        {
            preserveState: true,
            replace: true,
        }
    );
}
</script>

<template>
    <Head title="Allocation Recommendation" />

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
                                <BreadcrumbCurrent>Waiting Recommendation</BreadcrumbCurrent>
                            </li>
                        </ol>
                    </nav>
                </div>
                <Content>
                    <form @submit.prevent="submit">
                        <div class="flex justify-between">
                            <span class="font-medium text-primary-text text-lg mt-2">Allocations Waiting Recommendation</span>
                            
                            <div class="flex justify-end space-x-2 -mt-2">
                                <!--Reject Button-->
                                <button v-if="totalSelected != 0"
                                    :disabled="form.processing"
                                    class="w-[100px] justify-center my-3 flex items-center py-1.5 px-4 text-xxs text-white font-mono bg-danger-button hover:bg-danger-button-hover rounded-md"
                                    @click="reject">
                                    <IcCancel class="text-lg mr-0.5" />
                                    Reject 
                                        <template v-if="totalSelected != 0">
                                            <p class="bg-white rounded-full text-danger-button px-1 text-xs text-bold ml-1">
                                            {{ totalSelected }}
                                            </p>
                                        </template>
                                </button>
                                <button v-if="totalSelected == 0"
                                    disabled
                                    class="w-[100px] my-3 flex justify-center items-center py-1.5 px-4 text-xxs text-white font-mono bg-danger-button-hover rounded-md"
                                    @click="reject">
                                    <IcCancel class="text-lg mr-0.5" />
                                    Reject 
                                        <template v-if="totalSelected != 0">
                                            <p class="bg-white rounded-full text-danger-button px-1 text-xs text-bold ml-1">
                                            {{ totalSelected }}
                                            </p>
                                        </template>
                                </button>
                                <!--Recommend Button-->
                                <button v-if="totalSelected != 0"
                                    :disabled="form.processing"
                                    class="w-[100px] my-3 flex items-center px-2 justify-center text-xxs text-white font-mono bg-success-button hover:bg-success-button-hover rounded-md"
                                    @click="approve">
                                    <BxCheckDouble class="text-lg mr-0.5" />
                                    Recommend 
                                        <template v-if="totalSelected != 0">
                                            <p class="bg-white rounded-full text-success-button px-1 text-xs text-bold ml-1">
                                            {{ totalSelected }}
                                            </p>
                                        </template>
                                </button>
                                <button v-if="totalSelected == 0"
                                    disabled
                                    class="w-[100px] my-3 justify-center flex items-center px-2 text-xxs text-white font-mono bg-success-button-hover rounded-md"
                                   >
                                    <BxCheckDouble class="text-lg mr-0.5" />
                                    Recommend 
                                </button>
                            </div>
                        </div>
                        
                        <div class="border-b-2 border-gray-200 mb-2"></div>

                        <div class="mb-2 flex justify-between">
                            <BsSearch class="absolute ml-2 text-sm text-gray-500 mt-2" />
                            <input
                                name="search"
                                v-model="search"
                                type="text"
                                placeholder="Search"
                                class="pl-7 w-1/3 rounded-md bg-primary-50 text-xs border-none focus:ring-primary-100"
                            />

                            <select
                                name="perPage"
                                v-model="perPage"
                                @change="getAllocations"
                                class="rounded-md bg-primary-50 text-primary-500 text-xs border-none focus:ring-primary-100">
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
                                        <TableHeaderCellLeft></TableHeaderCellLeft>
                                        <TableHeaderCell>No</TableHeaderCell>
                                        <TableHeaderCell>Date Created</TableHeaderCell>
                                        <TableHeaderCell>Allocation Source</TableHeaderCell>
                                        <TableHeaderCell>Total Allocation</TableHeaderCell>
                                        <TableHeaderCell class="text-center">Status</TableHeaderCell>
                                        <TableHeaderCellRight>Action</TableHeaderCellRight>
                                    </TableRow>
                                </template>
                                <template #default>
                                    <TableRow v-for="(allocation, index) in allocations.data" :key="allocation.id" class="border-b">
                                        <TableDataCell>
                                            <div class="flex items-center">
                                                <input type="checkbox" :value="allocation.id" v-model="selected" name="selected" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-white" >
                                            </div>
                                        </TableDataCell>
                                        <TableDataCell>{{ index + 1}}</TableDataCell>
                                        <TableDataCell>{{ formatTimestamp(allocation.created_at) }}</TableDataCell>
                                        <TableDataCell>{{ allocation.allocation_source }}</TableDataCell>
                                        <TableDataCell>RM {{ allocation.total_allocation }}</TableDataCell>
                                        <TableDataCell class="flex justify-center">
                                            <SpanSubmittedStatus :value="allocation.status_id.name"/>
                                        </TableDataCell>
                                        <TableDataCellView class="text-center">
                                            <div class="row">
                                                <div class="col grid-cols-1 justify-items-center flex justify-center">
                                                    <a :href="route('allocations.allocationRecommendation', allocation.id)" class="flex items-center text-xxs space-x-2 px-2 py-1 bg-blue-500 text-white hover:bg-blue-600 rounded-md">
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
                        <!--Reject Modal-->
                        <Modal
                            :show="showConfirmRejectAllocationModal"
                            @close="closeModalReject">
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
                                    <p class="text-xs font-normal text-slate-800">
                                        Are You Sure Want To
                                        Reject This Allocation?
                                    </p>
                                </div>
                                <div class="mt-6 flex justify-center space-x-2">
                                    <DangerButton @click="($event) =>rejectAllocation(selected)"
                                        class="text-xs font-normal">Yes, I'm sure</DangerButton>
                                    <SecondaryButton @click="closeModalReject"
                                        class="text-xs font-extralight">No, Cancel</SecondaryButton>
                                </div>
                            </div>
                        </Modal>
                        <!---Approve Modal-->
                        <Modal
                            :show="
                                showConfirmApproveAllocationModal" @close="closeModalApprove">
                            <div class="py-4">
                                <div class="justify-center flex">
                                    <ClCircleWarning class="text-5xl text-gray-400"/>
                                </div>
                                <div class="flex justify-center">
                                    <p class="text-xs font-normal text-slate-800 mt-3">
                                        Are You Sure Want To Recommend this Allocation?
                                    </p>
                                </div>
                                <div class="mt-6 flex justify-center space-x-2">
                                    <SuccessButton
                                        @click="($event) =>approveAllocation(selected)"
                                        class="text-xs font-extralight">Yes, I'm sure</SuccessButton>
                                    <SecondaryButton
                                        @click="closeModalApprove"
                                        class="text-xs font-extralight">No, Cancel</SecondaryButton>
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
