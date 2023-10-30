<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from "vue";

// Form
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';


import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import TableDataCell from "@/Components/TableDataCell.vue";

import Content from "@/Components/Content.vue";

import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SuccessButton from "@/Components/SuccessButton.vue";

// BreadCrumb
import BreadcrumbInitial from "@/Components/BreadcrumbInitial.vue";
import BreadcrumbActive from "@/Components/BreadcrumbActive.vue";
import BreadcrumbCurrent from "@/Components/BreadcrumbCurrent.vue";

// Icons
import { MdOutlinedPendingActions } from "@kalimahapps/vue-icons";
import { BxCheckDouble } from "@kalimahapps/vue-icons";
import { BsSendCheck } from "@kalimahapps/vue-icons";

import { IcCancel } from "@kalimahapps/vue-icons";
import { QuSendCancelled } from "@kalimahapps/vue-icons";
import { SuCreate } from "@kalimahapps/vue-icons";

const form = useForm({
    allocation_source: "",
    total_allocation: "",
    bank: "",
    account_number: ""
})

const props = defineProps({
    refBanks: Array,
})

// Modal Approve
const showConfirmCreateAllocationModal = ref(false)

// Method for "Luluskan" button
const create = () => {
    showConfirmCreateAllocationModal.value = true;
};


const closeModalCreate = () => {
    showConfirmCreateAllocationModal.value = false;
}

const createAllocation = (id) => {
    try{
        form.post(route('allocations.store'), {
            onSuccess: (page) => {
            Toast.fire({
                icon: 'success',
                title: 'Allocation has added successfully'
            })
            },
        })
    }catch (err){
        console.log(err)
    }
    showConfirmCreateAllocationModal.value = false;
};

// Approve SweetAllert
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


</script>

<template>
    <Head title="Create new Allocation" />

    <AdminLayout>
        <div class="mt-14">
            <div class="w-full mx-auto">
                <div class="flex justify-between pt-4">
                    <nav class="text-sm font-semibold" aria-label="Breadcrumb">
                        <ol class="list-none p-0 inline-flex">
                            <li class="flex items-center">
                                <BreadcrumbInitial :href="route('allocations.index')"></BreadcrumbInitial>
                            </li>
                            <li class="flex items-center">
                                <BreadcrumbActive :href="route('allocations.index')">Allocation</BreadcrumbActive>
                            </li>
                            <li class="flex items-center">
                                <BreadcrumbCurrent>Create Allocation</BreadcrumbCurrent>
                            </li>
                        </ol>
                    </nav>
                </div>
                <form @submit.prevent="submit">
                    <Content>
                        <!-- Allocation Source -->
                        <div class="row py-2 px-4">
                            <div class="grid grid-cols-7">
                                <div class="col-span-1">
                                    <InputLabel for="allocation_source" class="mt-4" value="Allocation Name" />
                                </div>
                                <div class="col-span-3">
                                    <TextInput
                                        id="allocation_source"
                                        type="text"
                                        class="mt-2 block w-full"
                                        v-model="form.allocation_source"
                                        autofocus
                                        autocomplete="username"
                                        placeholder="Wakaf"
                                    />
                                    <InputError class="mt-2" :message="form.errors.allocation_source" />
                                </div>
                            </div>
                        </div>
                        <!--Bank-->
                        <div class="row px-4">
                            <div class="grid grid-cols-7">
                                <div class="col-span-1">
                                    <InputLabel class="mt-1" value="Bank" />
                                </div>
                                <div class="col-span-3">
                                    <select class="rounded-md text-xs border border-gray-300 w-4/5" v-model="form.bank" id="refBank">
                                        <option value="" disabled>Pilih Bank</option>
                                        <option v-for="refBank in refBanks" :key="refBank.id" :value="refBank.id">{{ refBank.name }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!--Account Number-->
                        <div class="row px-4">
                            <div class="grid grid-cols-7">
                                <div class="col-span-1">
                                    <InputLabel for="total_allocation" class="mt-3" value="Account Number" />
                                </div>
                                <div class="col-span-3">
                                    <TextInput
                                        id="total_allocation"
                                        type="number"
                                        class="mt-2 block w-full"
                                        v-model="form.account_number"
                                        autofocus
                                        autocomplete="username"
                                        placeholder="161024581113"
                                    />
                                    <InputError class="mt-2" :message="form.errors.account_number" />
                                </div>
                            </div>
                        </div>
                        <!--Total Allocation-->
                        <div class="row px-4 mb-4">
                            <div class="grid grid-cols-7">
                                <div class="col-span-1">
                                    <InputLabel for="total_allocation" class="mt-3" value="Total Allocation" />
                                </div>
                                <div class="col-span-3">
                                    <TextInput
                                        id="total_allocation"
                                        type="number"
                                        class="mt-2 block w-full"
                                        v-model="form.total_allocation"
                                        autofocus
                                        autocomplete="username"
                                        placeholder="RM 800,000.00"
                                    />

                                    <InputError class="mt-2" :message="form.errors.total_allocation" />
                                </div>
                            </div>
                        </div>
                        <!--Button Submit-->
                        <div class="flex items-center justify-end space-x-2 mb-2">
                            <Link :href="route('allocations.index')" class="flex items-center py-2 px-2 text-xs text-white font-mono bg-danger-button hover:bg-danger-button-hover rounded-md">
                                <IcCancel class="text-lg mr-0.5" />
                                Cancel
                            </Link>
                            <button @click="create" :class="{ 'opacity-25 mr-2.5 px-3 py-2 text-md text-white font-semibold bg-primary-500 hover:bg-indigo-700 rounded': form.processing }" :disabled="form.processing" class="flex items-center py-2 px-2 text-xs text-white font-mono bg-success-button hover:bg-success-button-hover rounded-md">
                                <BxCheckDouble class="text-lg mr-0.5" />
                                Submit
                            </button>
                        </div>
                        <!---Approve Modal-->
                        <Modal :show="showConfirmCreateAllocationModal" @close="closeModalCreate">
                            <div class="p-6">
                                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                </svg>
                                <div class="flex justify-center">
                                    <h2 class="text-lg font-semibold text-slate-800">
                                    Are you sure you want to Create this Allocation?
                                    </h2>
                                </div>
                                <div class="mt-6 flex justify-center space-x-2">
                                    <SuccessButton @click="$event => createAllocation()" class="text-xs font-extralight py-3">Yes, I'm sure</SuccessButton>
                                    <SecondaryButton @click="closeModalCreate" class="text-xs font-extralight py-3">No, Cancel</SecondaryButton>
                                </div>
                            </div>
                        </Modal>
                    </Content>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
