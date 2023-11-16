<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from "vue";

// Form
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import TextInputMoneyXxs from '@/Components/TextInputMoneyXxs.vue';
import TextInputXxs from '@/Components/TextInputXxs.vue';
import InputBold from '@/Components/InputBold.vue';
import InputMoneyBold from '@/Components/InputMoneyBold.vue';
import GridLayout from '@/Components/GridLayout.vue';
import InputLabelCenter from '@/Components/InputLabelCenter.vue';
import GridLayoutCol5 from '@/Components/GridLayoutCol5.vue';




import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import TableHeaderCellSmall from "@/Components/TableHeaderCellSmall.vue";
import TableDataCellSmall from "@/Components/TableDataCellSmall.vue";
import TableHeaderCellLeftSmall from "@/Components/TableHeaderCellLeftSmall.vue";
import TableHeaderCellRightSmall from "@/Components/TableHeaderCellRightSmall.vue";


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
import { IcCancel } from "@kalimahapps/vue-icons";
import { QuSendCancelled } from "@kalimahapps/vue-icons";
import { BxCheckDouble } from "@kalimahapps/vue-icons";
import { BsSendCheck } from "@kalimahapps/vue-icons";
import { MdOutlinedUpdate } from "@kalimahapps/vue-icons";


const props = defineProps({
    allocation: {
        type: Object,
        required: true
    },
    refBanks: Array,
    programs: {
        type: Object,
        required: true
    },
})

const form = useForm({
    allocation_source: props.allocation?.allocation_source,
    total_allocation: props.allocation?.total_allocation,
    allocation_balance: props.allocation?.allocation_balance,
    account_number: props.allocation?.account_number,
    bank: props.allocation?.bank,
})

const showConfirmUpdateAllocationModal = ref(false)

const update = () => {
    showConfirmUpdateAllocationModal.value = true;
};

const closeModalUpdate = () => {
    showConfirmUpdateAllocationModal.value = false;
}

const updateAllocation = (id) => {
    try{
        form.put(route('allocations.update', props.allocation?.id), {
            onSuccess: (page) => {
            Toast.fire({
                icon: 'success',
                title: 'Allocation has successfully updated',
                width: 410
            })
            },
        })
    }catch (err){
        console.log(err)
    }
    showConfirmUpdateAllocationModal.value = false;
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

</script>

<template>
    <Head title="Update Allocations" />

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
                                <BreadcrumbCurrent>Update Allocation</BreadcrumbCurrent>
                            </li>
                        </ol>
                    </nav>
                </div>
                <form @submit.prevent="submit">
                    <template v-if="allocation.status == 1">
                        <div class="row">
                            <div class="grid grid-cols-5 space-x-5">
                                <div class="col-span-5">
                                    <Content class="h-[300px] flex flex-col justify-between">
                                        <div>
                                            <!---Allocation Source-->
                                            <GridLayoutCol5 class="mt-4">
                                                <InputLabel value="Allocation Source"/>

                                                <div class="col-span-2">
                                                    <TextInputXxs
                                                    id="allocation_source"
                                                    type="text"
                                                    class="mt-1 block w-2/3"
                                                    v-model="form.allocation_source"
                                                    :value="form.allocation_source"
                                                    />
                                                </div>
                                            </GridLayoutCol5>
                                            <!--Bank Name-->
                                            <GridLayoutCol5>
                                                <InputLabel value="Bank Name" />

                                                <div class="col-span-2">
                                                    <select class="h-[30px] text-primary-700 border-none bg-primary-50 text-xs rounded-lg w-2/3" v-model="form.bank" id="refBank">
                                                        <option value="" disabled>Choose Bank</option>
                                                        <option v-for="refBank in refBanks" :key="refBank.id" :value="refBank.id">{{ refBank.name }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </GridLayoutCol5>
                                            <!--Account Number-->
                                            <GridLayoutCol5>
                                                <InputLabel value="Account Number" />
                                                <div class="col-span-2">
                                                    <TextInputXxs
                                                    id="account_number"
                                                    type="text"
                                                    class="mt-1 block w-2/3"
                                                    v-model="form.account_number"
                                                    :value="form.account_number"
                                                    />

                                                    <InputError class="mt-2" :message="form.errors.account_number" />
                                                </div>
                                            </GridLayoutCol5>
                                            <!--Allocation Amount-->
                                            <GridLayoutCol5>
                                                <InputLabel value="Allocation Amount" />

                                                <div class="col-span-2">
                                                    <TextInputMoneyXxs
                                                    id="total_allocation"
                                                    type="text"
                                                    class="mt-1 block w-2/3"
                                                    v-model="form.total_allocation"
                                                    :value="form.total_allocation"
                                                    />
                                                </div>
                                            </GridLayoutCol5>
                                        </div>
                                        
                                        <!--Button-->
                                        <div class="flex items-center justify-end space-x-2 mt-2 mb-2 mr-2">
                                            <Link :href="route('allocations.index')" class="flex items-center py-2 px-2 text-xs text-white font-mono bg-danger-button hover:bg-danger-button-hover rounded-md">
                                                <IcCancel class="text-lg mr-0.5" />
                                                Cancel
                                            </Link>
                                            <button @click="update" :class="{ 'opacity-25 mr-2.5 px-3 py-2 text-md text-white font-semibold bg-primary-500 hover:bg-indigo-700 rounded': form.processing }" :disabled="form.processing" class="flex items-center py-2 px-2 text-xs text-white font-mono bg-success-button hover:bg-success-button-hover rounded-md">
                                                <BxCheckDouble class="text-lg mr-0.5" />
                                                Update
                                            </button>
                                        </div>
                                    </Content>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template v-if="allocation.status == 3">
                        <div class="row">
                            <div class="grid grid-cols-5 space-x-5">
                                <div class="col-span-3">
                                    <Content class="h-[500px] flex flex-col justify-between">
                                        <div>
                                            <!---Allocation Source-->
                                            <GridLayout class="mt-4">
                                                <InputLabel value="Allocation Source"/>

                                                <div class="col-span-2">
                                                    <InputBold :value="allocation.allocation_source"/>
                                                </div>
                                            </GridLayout>
                                            <!--Bank Name-->
                                            <GridLayout>
                                                <InputLabel value="Bank Name" />

                                                <div class="col-span-2">
                                                    <select class="h-[30px] text-primary-700 border-none bg-primary-50 text-xs rounded-lg w-2/3" v-model="form.bank" id="refBank">
                                                        <option value="" disabled>Choose Bank</option>
                                                        <option v-for="refBank in refBanks" :key="refBank.id" :value="refBank.id">{{ refBank.name }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </GridLayout>
                                            <!--Account Number-->
                                            <GridLayout>
                                                <InputLabel value="Account Number" />
                                                <div class="col-span-2">
                                                    <TextInputMoneyXxs
                                                    id="account_number"
                                                    type="text"
                                                    class="mt-1 block w-2/3"
                                                    v-model="form.account_number"
                                                    :value="form.account_number"
                                                    />

                                                    <InputError class="mt-2" :message="form.errors.account_number" />
                                                </div>
                                            </GridLayout>
                                            <!--Current Allocation Amount-->
                                            <GridLayout>
                                                <InputLabel value="Current Allocation Amount" />

                                                <div class="col-span-2">
                                                    <InputMoneyBold :value="allocation.total_allocation"/>
                                                </div>
                                            </GridLayout>
                                            <!--Additional Amount-->
                                            <GridLayout>
                                                <InputLabel value="Additional Amount" />

                                                <div class="col-span-2">
                                                    <TextInputMoneyXxs
                                                    id="account_number"
                                                    type="text"
                                                    class="mt-1 block w-2/3"
                                                    v-model="form.account_number"
                                                    :value="form.account_number"
                                                    />
                                                </div>
                                            </GridLayout>
                                            <!--Total Amount-->
                                            <GridLayout>
                                                <InputLabel value="Total Amount" />

                                                <div class="col-span-2">
                                                    <TextInputMoneyXxs
                                                    id="account_number"
                                                    type="text"
                                                    class="mt-1 block w-2/3"
                                                    v-model="form.account_number"
                                                    :value="form.account_number"
                                                    />
                                                </div>
                                            </GridLayout>
                                        </div>
                                        
                                        <!--Button-->
                                        <div class="flex items-center justify-end space-x-2 mt-2 mb-2 mr-2">
                                            <Link :href="route('allocations.index')" class="flex items-center py-2 px-2 text-xs text-white font-mono bg-danger-button hover:bg-danger-button-hover rounded-md">
                                                <IcCancel class="text-lg mr-0.5" />
                                                Cancel
                                            </Link>
                                            <button @click="update" :class="{ 'opacity-25 mr-2.5 px-3 py-2 text-md text-white font-semibold bg-primary-500 hover:bg-indigo-700 rounded': form.processing }" :disabled="form.processing" class="flex items-center py-2 px-2 text-xs text-white font-mono bg-success-button hover:bg-success-button-hover rounded-md">
                                                <BxCheckDouble class="text-lg mr-0.5" />
                                                Add
                                            </button>
                                        </div>
                                    </Content>
                                </div>
                                
                                <div class="col-span-2">
                                    <Content class="h-[500px] flex flex-col justify-between">
                                        <div>
                                            <InputLabelCenter value="List of Program"/>

                                            <div class="mt-5 bg-white shadow-lg rounded-lg">
                                                <Table>
                                                    <template #header>
                                                        <TableRow class="">
                                                            <TableHeaderCellLeftSmall>No</TableHeaderCellLeftSmall>
                                                            <TableHeaderCellSmall>Program</TableHeaderCellSmall>
                                                            <TableHeaderCellRightSmall>Status</TableHeaderCellRightSmall>
                                                        </TableRow>
                                                    </template>
                                                </Table>
                                                
                                                <div class="max-h-[350px] overflow-y-auto">
                                                    <Table v-if="programs.length > 0">
                                                        <template #default>
                                                            <TableRow v-for="program in programs" :key="program.id" class="border-b">
                                                                <TableDataCellSmall>{{ program.id }}</TableDataCellSmall>
                                                                <TableDataCellSmall>{{ program.name }}</TableDataCellSmall>
                                                                <TableDataCellSmall>{{ program.status_id.name }}</TableDataCellSmall>
                                                            </TableRow>
                                                        </template>
                                                    </Table>
                                                    <div v-else class="text-center text-xs font-bold text-gray-500 my-4">Program Currently Empty</div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <InputLabel v-if="programs.length > 0" class="mt-8 my-2" value="Notes : Program that is currently running"/>
                                    </Content>
                                </div>
                            </div>
                        </div>
                    </template>
                    <!---Approve Modal-->
                    <Modal :show="showConfirmUpdateAllocationModal" @close="closeModalUpdate">
                        <div class="p-6">
                            <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                            <div class="flex justify-center">
                                <h2 class="text-lg font-semibold text-slate-800">
                                Are you sure you want to Update this Allocation?
                                </h2>
                            </div>
                            <div class="mt-6 flex justify-center space-x-2">
                                <SuccessButton @click="updateAllocation" class="text-xs font-extralight py-3">Yes, I'm sure</SuccessButton>
                                <SecondaryButton @click="closeModalUpdate" class="text-xs font-extralight py-3">No, Cancel</SecondaryButton>
                            </div>
                        </div>
                    </Modal>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
