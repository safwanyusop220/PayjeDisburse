<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

// Form
import Checkbox from "@/Components/Checkbox.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputBold from "@/Components/InputBold.vue";
import InputMoneyBold from "@/Components/InputMoneyBold.vue";

import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import TableDataCell from "@/Components/TableDataCell.vue";

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

import { BxCheckDouble } from "@kalimahapps/vue-icons";
import { IcCancel } from "@kalimahapps/vue-icons";

import { onMounted, watch } from "vue";
import axios from "axios";

const props = defineProps({
    allocation: {
        type: Object,
        required: true,
    },
    roles: Array,
    permissions: true,
});

const form = useForm({
    allocation_source: props.allocation?.allocation_source,
    total_allocation: props.allocation?.total_allocation,
    status: props.allocation?.status,
    bank: props.allocation?.bank,
    reject_reason: props.allocation?.reject_reason,
});

const getStatusText = (status) => {
    switch (status) {
        case 1:
            return "Submitted";
        case 2:
            return "Recommended";
        case 3:
            return "Approved";
        case 4:
            return "Rejected";
        default:
            return "Unknown Status";
    }
};

const shouldShowInput = ref(false);

watch(
    () => props.allocation,
    () => {
        form.status = props.allocation?.status;
    }
);

const disableButtonReject = ref(true);
const disableButtonApprove = ref(true);

const showConfirmApproveAllocationModal = ref(false);

const approve = () => {
    showConfirmApproveAllocationModal.value = true;
};

const closeModalApprove = () => {
    showConfirmApproveAllocationModal.value = false;
};

const approveAllocation = (id) => {
    try {
        form.put(route("allocations.approveRecommendation", props.allocation?.id), {
            onSuccess: (page) => {
                Swal.fire(
                    'Success',
                    'Allocation Has Successfully Recommended!',
                    'success'
                )
                showConfirmApproveAllocationModal.value = false;
                disableButtonReject.value = false
                disableButtonApprove.value = false
            },
        });
    } catch (err) {
        console.log(err);
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

const showConfirmRejectAllocationModal = ref(false);

const reject = () => {
    showConfirmRejectAllocationModal.value = true;
};

const closeModalReject = () => {
    showConfirmRejectAllocationModal.value = false;
};

const rejectAllocation = async (id) => {
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
        form.post(route("allocations.rejectRecommendation", props.allocation?.id), {
            onSuccess: (page) => {
                Toast.fire({
                    icon: "success",
                    title: "Allocation Has Successfully Rejected",
                });
                showConfirmRejectAllocationModal.value = false;
                disableButtonReject.value = false
                disableButtonApprove.value = false
            },
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
</script>

<template>
    <Head title="Allocation Recommendation" />

    <AdminLayout>
        <div class="mt-12">
            <div class="w-full mx-auto">
                <div class="flex justify-between pt-6">
                    <nav class="text-sm font-semibold" aria-label="Breadcrumb">
                        <ol class="list-none p-0 inline-flex">
                            <li class="flex items-center">
                                <BreadcrumbInitial :href="route('allocations.index')"></BreadcrumbInitial>
                            </li>
                            <li class="flex items-center">
                                <BreadcrumbActive :href="route('allocations.index')">Allocation</BreadcrumbActive>
                            </li>
                            <li class="flex items-center">
                                <BreadcrumbActive :href="route('allocations.waitingRecommendation')">Waiting Recommendation</BreadcrumbActive>
                            </li>
                            <li class="flex items-center">
                                <BreadcrumbCurrent>Recommendation</BreadcrumbCurrent>
                            </li>
                        </ol>
                    </nav>
                </div>
                <Content>
                    <form @submit.prevent="submit">
                        <div class="grid grid-cols-2 mt-2">
                            <div>
                                <!---Allocation Source-->
                                <div class="px-4 mb-2">
                                    <div class="col">
                                        <div class="grid grid-cols-3">
                                            <InputLabel value="Allocation Source"/>

                                            <div class="col-span-2">
                                                <InputBold :value="allocation.allocation_source"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!---Bank Name-->
                                <div class="px-4 mb-2">
                                    <div class="col">
                                        <div class="grid grid-cols-3">
                                            <InputLabel value="Bank Name"/>

                                            <div class="col-span-2">
                                                <InputBold :value="allocation.bank.name"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!---Account Number-->
                                <div class="px-4 mb-2">
                                    <div class="col">
                                        <div class="grid grid-cols-3">
                                            <InputLabel value="Account Number"/>

                                            <div class="col-span-2">
                                                <InputBold :value="allocation.account_number"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--Total Allocation-->
                                <div class="px-4 mb-2">
                                    <div class="col">
                                        <div class="grid grid-cols-3">
                                            <InputLabel value="Total Allocation"/>

                                            <div class="col-span-2">
                                                <InputMoneyBold :value="allocation.total_allocation"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--Status-->
                                <div class="px-4 -mb-1 mt-6">
                                    <div class="col">
                                        <div class="grid grid-cols-3">
                                            <InputLabel value="Status"/>

                                            <div class="col-span-2">
                                                <InputBold :value="getStatusText(form.status)"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="form.status == 4">
                                    <!--Reject Reason-->
                                    <div class="px-4">
                                        <div class="col">
                                            <div class="grid grid-cols-3">
                                                <InputLabel value="Reason"/>

                                                <div class="col-span-2">
                                                    <InputBold :value="allocation.reject_reason"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Button-->
                        <div  class="flex justify-end space-x-2 mb-2">
                            <button v-if="disableButtonReject"
                                :disabled="form.processing"
                                class="flex items-center py-2 px-5 text-xs text-white font-mono bg-danger-button hover:bg-danger-button-hover rounded-md"
                                @click="reject"
                                >
                                <IcCancel class="text-lg mr-0.5" />
                                Reject
                            </button>

                            <a v-if="!disableButtonReject"
                                class="flex items-center py-2 px-5 text-xs text-white font-mono bg-danger-button-hover rounded-md"
                                >
                                <IcCancel class="text-lg mr-0.5" />
                                Reject
                            </a>

                            <button v-if="disableButtonApprove"
                                :disabled="form.processing"
                                class="flex items-center py-2 px-2 text-xs text-white font-mono bg-success-button hover:bg-success-button-hover rounded-md"
                                @click="approve">
                                <BxCheckDouble class="text-lg mr-0.5" />
                                Recommend
                            </button>

                            <a v-if="!disableButtonApprove"
                                class="flex items-center py-2 px-2 text-xs text-white font-mono bg-success-button-hover rounded-md"
                                >
                                <BxCheckDouble class="text-lg mr-0.5" />
                                Recommend
                            </a>
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
                                        Are You Sure Want To
                                        Recommend This Allocation?
                                    </h2>
                                </div>
                                <div class="mt-6 flex justify-center space-x-2">
                                    <SuccessButton
                                        @click="($event) =>approveAllocation(allocation.id)"
                                        class="text-xs font-extralight py-3">Yes, I'm sure</SuccessButton>
                                    <SecondaryButton
                                        @click="closeModalApprove"
                                        class="text-xs font-extralight py-3">No, Cancel</SecondaryButton>
                                </div>
                            </div>
                        </Modal>
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
                                    <h2 class="text-lg font-semibold text-slate-800">
                                        Are You Sure Want To
                                        Reject This Allocation?
                                    </h2>
                                </div>
                                <div class="mt-6 flex justify-center space-x-2">
                                    <DangerButton @click="($event) =>rejectAllocation(allocation.id)"
                                        class="text-xs font-extralight py-3">Yes, I'm sure</DangerButton>
                                    <SecondaryButton @click="closeModalReject"
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
