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
import GridLayoutCol5 from '@/Components/GridLayoutCol5.vue';
import TextInputXxs from '@/Components/TextInputXxs.vue';
import TextInputMoneyXxs from '@/Components/TextInputMoneyXxs.vue';
import TextInputMoneySpinner from '@/Components/TextInputMoneySpinner.vue';



import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import TableDataCell from "@/Components/TableDataCell.vue";


import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SuccessButton from "@/Components/SuccessButton.vue";
import Content from "@/Components/Content.vue";

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
import { ClCircleWarning } from "@kalimahapps/vue-icons";


const form = useForm({
    allocation_source: "",
    total_allocation: "",
    bank: "",
    account_number: ""
})

const props = defineProps({
    refBanks: Array,
})

const isFormValid = computed(() => {
  const allocationSourceError = form.allocation_source === '';
  const bankError = form.bank === '';
  const accountNumberError = form.account_number === '';
  const totalAllocationError = form.total_allocation === '';
  return !allocationSourceError && !bankError && !accountNumberError && !totalAllocationError;
});

const showConfirmCreateAllocationModal = ref(false)

const create = () => {
    if (isFormValid.value) {
        showConfirmCreateAllocationModal.value = true;
  }

};

const closeModalCreate = () => {
    showConfirmCreateAllocationModal.value = false;
}

const createAllocation = (id) => {
    //console.log(isFormValid.value);

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
const config = {
  spinner: false,
  step: 10,
  min: 0,
  max: 9999999999,
  precision: 2,
  decimal: ".",
  thousands: ",",
  masked: false,
  disableNegative: true,
  align: "start",
  prefix: "RM "
};
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
                        <div class="row py-2 px-4 mb-2">
                            <div class="grid grid-cols-7">
                                <div class="col-span-1">
                                    <InputLabel class="mt-4" value="Allocation Name" />
                                </div>
                                <div class="col-span-3">
                                    <TextInputXxs
                                        id="allocation_source"
                                        type="text"
                                        class="mt-2 block w-2/3"
                                        v-model="form.allocation_source"
                                    />
                                    <div v-if="form.allocation_source === ''" class="text-red-500 text-xxs-custom ml-2">*Allocation Name is required</div>
                                    <InputError class="mt-2" :message="form.errors.allocation_source" />
                                </div>
                            </div>
                        </div>
                        <!--Bank-->
                        <div class="row px-4 mb-2">
                            <div class="grid grid-cols-7">
                                <div class="col-span-1">
                                    <InputLabel class="mt-1" value="Bank" />
                                </div>
                                <div class="col-span-3">
                                    <div class="col-span-2">
                                        <select class="h-[30px] text-primary-700 border-none bg-primary-50 text-xs rounded-lg w-2/3" v-model="form.bank" id="refBank">
                                            <option value="" disabled>Choose Bank</option>
                                            <option v-for="refBank in refBanks" :key="refBank.id" :value="refBank.id">{{ refBank.name }}
                                            </option>
                                        </select>
                                        <div v-if="form.bank === ''" class="text-red-500 text-xxs-custom ml-2">*Bank is required</div>
                                        <InputError class="mt-2" :message="form.errors.bank" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Account Number-->
                        <div class="row px-4 mb-2">
                            <div class="grid grid-cols-7">
                                <div class="col-span-1">
                                    <InputLabel for="account_number" class="mt-3" value="Account Number" />
                                </div>
                                <div class="col-span-3">
                                    <TextInputXxs
                                        id="account_number"
                                        type="number"
                                        class="mt-2 block w-2/3"
                                        v-model="form.account_number"
                                    />
                                    <div v-if="form.account_number === ''" class="text-red-500 text-xxs-custom ml-2">*Account number is required</div>
                                    <InputError class="mt-2" :message="form.errors.account_number" />
                                </div>
                            </div>
                        </div>
                        <!--Total Allocation-->
                        <div class="row px-4 mb-4">
                            <div class="grid grid-cols-7">
                                <div class="col-span-1">
                                    <InputLabel  class="mt-3" value="Total Allocation" />
                                </div>
                                
                                
                                <div class="col-span-3">
                                    <v-money-spinner
                                        id="total_allocation"
                                        class="mt-2 w-2/3 rounded-xl text-xs bg-primary-50 block border border-none focus:ring-0 text-primary-700 focus:ring-primary-50 focus:border-blue-500"
                                        v-model="form.total_allocation"
                                        v-bind="config">
                                    </v-money-spinner>

                                    <div v-if="form.total_allocation === ''" class="text-red-500 text-xxs-custom ml-2">*Total allocation is required</div>
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
                        <!---Add Modal-->
                        <Modal :show="showConfirmCreateAllocationModal" @close="closeModalCreate">
                            <div class="py-4">
                                <div class="justify-center flex">
                                    <ClCircleWarning class="text-5xl text-gray-400"/>
                                </div>
                                <div class="flex justify-center">
                                    <p class="text-xs font-normal text-slate-800 mt-3">
                                        Are you sure you want to Create this Allocation?
                                    </p>
                                </div>
                                <div class="mt-6 flex justify-center space-x-2">
                                    <SuccessButton @click="$event => createAllocation()" class="text-xs font-extralight">Yes, I'm sure</SuccessButton>
                                    <SecondaryButton @click="closeModalCreate" class="text-xs font-extralight">No, Cancel</SecondaryButton>
                                </div>
                            </div>
                        </Modal>
                    </Content>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
