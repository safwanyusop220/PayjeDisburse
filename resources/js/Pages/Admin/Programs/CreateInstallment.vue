<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from "vue";

import { onMounted, watch } from 'vue';


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

import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SuccessButton from "@/Components/SuccessButton.vue";
import BackButton from "@/Components/BackButton.vue";
import Dropdown from '@/Components/Dropdown.vue';

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
import { ClAddPlus } from "@kalimahapps/vue-icons";
import { ClAddPlusCircle } from "@kalimahapps/vue-icons";
import { CgAdd } from "@kalimahapps/vue-icons";
import { FlAddSquare } from "@kalimahapps/vue-icons";
import { FlFilledAddCircle } from "@kalimahapps/vue-icons";
import { BxSolidMessageRoundedAdd } from "@kalimahapps/vue-icons";
import { AkCirclePlusFill } from "@kalimahapps/vue-icons";
import { BxListPlus } from "@kalimahapps/vue-icons";
import { BsBookmarkPlusFill } from "@kalimahapps/vue-icons";
import { ReDeleteBinFill } from "@kalimahapps/vue-icons";
import { ReDeleteBin5Line } from "@kalimahapps/vue-icons";

import { reactive } from 'vue';

const props = defineProps({
    program: {
        type: Object,
        required: true
    },
    programType:true,
    allocation:true,
    programTypes: Array,
    frequencies: Array,
    allocations: Array
})

const form = useForm({
    name: props.program?.name,
    type_id: props.program?.type_id,
    allocation: props.program?.allocation,
    allocation_rate: props.program?.allocation_rate,
    latest_payment: props.program?.latest_payment,
    valid_until: props.program?.valid_until,
    payment_date: props.program?.payment_date,
    frequency: props.program?.frequency,
    status: props.program?.status,
    program_id : props.program?.program_id,
    amount: [],
    installment_payment_date: [],
    installment_name: []
});

watch(
    () => props.program,
    () => {
        form.status = props.program?.status,
        form.type = props.program?.type,
        form.allocation = props.program?.allocation

        form.program_id = props.program?.program_id


    }
);

const programTypes = ref([
      { id: 1, name: 'Individu' },
      { id: 2, name: 'Kumpulan' },
      { id: 3, name: 'Pecahan' },
      { id: 4, name: 'Kelompok' },
    ]);

const showConfirmModal = ref(false)

const create = () => {
    const getProgramId = document.getElementById('program_id')
    form.program_id = getProgramId.value;
    showConfirmModal.value = true;
};


const closeModal = () => {
    showConfirmModal.value = false;
}

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



const inputs = ref([]);

const addInput = () => {
  inputs.value.push({ amount: '', installment_payment_date: '', installment_name: '' });
};

const removeInput = (index) => {
  inputs.value.splice(index, 1);
};

const createInstallment = (id) => {
    try{
        //form.amount = inputs.value
        form.amount = inputs.value.map(input => input.amount);
        form.installment_payment_date = inputs.value.map(input => input.installment_payment_date);
        form.installment_name = inputs.value.map(input => input.installment_name);
        form.post(route('programs.storeInstallment'), {
            onSuccess: (page) => {
                Swal.fire(
                'Success!',
                'Success Submit Installment',
                'success'
                )
            },
        })
    }catch (err){
        console.log(err)
    }
};

</script>

<template>
    <Head title="Update Program" />

    <AdminLayout>
        <div class="py-12">
            <div class="w-full mx-auto">
                <div class="overflow-hidden">
                    <div class="flex justify-between mt-2">
                        <div class="flex items-center">
                            <span class="font-bold text-black text-xl mr-1">Create Installment</span>
                        </div>
                        <div v-if="errorMessage" class="alert alert-danger">
                            {{ errorMessage }}
                        </div>
                        <nav class="text-sm font-semibold" aria-label="Breadcrumb">
                            <ol class="list-none p-0 inline-flex">
                                <li class="flex items-center">
                                    <BreadcrumbInitial :href="route('programs.index')">Home</BreadcrumbInitial>
                                </li>
                                <li class="flex items-center">
                                    <BreadcrumbActive :href="route('programs.index')">Program</BreadcrumbActive>
                                </li>
                                <li class="flex items-center">
                                    <BreadcrumbCurrent>Create Installment</BreadcrumbCurrent>
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <div class="mt-8 rounded-xl py-6 col-span-7">
                        <form @submit.prevent="submit">
                            <!-- Content for the 4/5 width column -->
                            <div class="bg-primary-50 rounded-3xl p-6">
                                <!--Program Name-->
                                <div class="grid grid-cols-12">
                                    <div class="col-span-2">
                                        <InputLabel class="mt-1.5" value="Program Name" />
                                    </div>
                                    <div class="col-span-10">
                                        <TextInput
                                            id="name"
                                            type="text"
                                            class=" block w-1/2 text-xs"
                                            v-model="form.name"
                                            autofocus
                                            autocomplete="username"
                                            placeholder="Program A"
                                            disabled
                                        />

                                        <InputError class="mt-2" :message="form.errors.name" />
                                    </div>
                                </div>
                                <!--Program Type-->
                                <div class="grid grid-cols-12">
                                    <div class="col-span-2">
                                        <InputLabel class="mt-5 font-bold" value="Program Type" />
                                    </div>
                                    <div class="col-span-10">
                                        <div class="mt-5 mb-4">
                                            <template v-for="programType in programTypes" :key="programType.id">
                                                <div class="flex items-center" >
                                                    <input
                                                        :key="programType.id"
                                                        type="radio"
                                                        :id="programType.id"
                                                        v-model="form.type_id"
                                                        :value="programType.id"
                                                        class="mr-2"
                                                        disabled
                                                        @click="variable(programType.id)"
                                                    />
                                                    <label :for="programType.id" class="text-sm">{{ programType.name }}</label>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <!--Textfield based on program type Pecahan-->
                                <div>
                                    <!--Allocation Source-->
                                    <div class="grid grid-cols-12">
                                        <div class="col-span-2">
                                            <InputLabel class="mt-4" value="Allocation Source" />
                                        </div>
                                        
                                        <div class="col-span-10 my-2">
                                            <p class="w-1/2 bg-white text-xs p-2 border border-gray-300 rounded-md">
                                                {{ program.allocation_id.allocation_source }}
                                            </p>                                            
                                        </div>
                                    </div>
                                    <!--Allocation Rate-->
                                    <div class="grid grid-cols-12 mb-4">
                                        <div class="col-span-2">
                                            <InputLabel class="mt-2" value="Allocation Rate" />
                                        </div>
                                        
                                        <div class="col-span-10">
                                            <TextInput
                                                id="allocation_rate"
                                                type="number"
                                                class="mt-1 block w-1/2 text-xs"
                                                v-model="form.allocation_rate"
                                                disabled
                                                required
                                                style="appearance: textfield; -webkit-appearance: textfield;"
                                            />

                                            <InputError class="mt-2" :message="form.errors.allocation_rate" />
                                        </div>
                                    </div>
                                    <!--For Pecahan-->
                                    <div v-if="program.type_id == 3">
                                        <!--Payment Installment-->
                                        <div class="grid grid-cols-12 mt-8">
                                            <div class="col-span-2">
                                                <InputLabel class="mt-2" value="Payment Installment" />
                                            </div>

                                            <div class="col-span-5 flex justify-end">
                                                <button class="py-1 px-1.5 text-white font-mono bg-success-button hover:bg-success-button-hover rounded-md" @click.prevent="addInput">
                                                    <div class="flex items-center">
                                                        <span class=" text-xxs-custom">Add</span>
                                                        <BsBookmarkPlusFill class="ml-0.5 text-xs"/>
                                                    </div>
                                                </button>
                                            </div>
                                        </div>
                                        <!--Dynamic Field-->
                                        <div class="grid grid-cols-12">
                                            <div class="col-span-2">
                                            </div>
                                            <div class="col-span-10">
                                                <div class="mt-2">
                                                    <div v-for="(input, index) in inputs" :key="index">
                                                        <input v-model="input.installment_payment_date" required type="date" class="w-1/6 mr-2.5 border-gray-300 text-xs focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
                                                        <input v-model="input.amount" required type="number" class="w-1/4  border-gray-300 text-xs focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
                                                        <button  type="button" class="bg-danger-button hover:bg-danger-button-hover rounded-md text-white px-1.5 py-1.5 m-2 ml-5 text-xs" @click="removeInput(index)">
                                                        <ReDeleteBin5Line class="text-sm"/>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--For Kelompok-->
                                    <div v-if="program.type_id == 4">
                                        <!--Payment Installment-->
                                        <div class="grid grid-cols-12 mt-8">
                                            <div class="col-span-2">
                                                <InputLabel class="mt-2" value="Payment Installment" />
                                            </div>

                                            <div class="col-span-5 flex justify-end">
                                                <button class="py-1 px-1.5 text-white font-mono bg-success-button hover:bg-success-button-hover rounded-md" @click.prevent="addInput">
                                                    <div class="flex items-center">
                                                        <span class=" text-xxs-custom">Add</span>
                                                        <BsBookmarkPlusFill class="ml-0.5 text-xs"/>
                                                    </div>
                                                </button>
                                            </div>
                                        </div>
                                        <!--Dynamic Field-->
                                        <div class="grid grid-cols-12">
                                            <div class="col-span-2">
                                            </div>
                                            <div class="col-span-10">
                                                <div class="mt-2">
                                                    <div v-for="(input, index) in inputs" :key="index">
                                                        <input v-model="input.installment_name" required type="text" class="w-1/5 mr-2.5  border-gray-300 text-xs focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="title"/>

                                                        <input v-model="input.installment_payment_date" required type="date" class="w-1/5  border-gray-300 text-xs focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />

                                                        <button  type="button" class="bg-danger-button hover:bg-danger-button-hover rounded-md text-white px-1.5 py-1.5 m-2 ml-5 text-xs" @click="removeInput(index)">
                                                        <ReDeleteBin5Line class="text-sm"/>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--Hidden ID-->
                                <TextInput
                                    id="program_id"
                                    type="hidden"
                                    class=" block w-1/2 text-xs mt-3"
                                    autofocus
                                    :value="program.id"
                                    disabled
                                />

                                <!-- Button submit & back-->
                                <div class="flex items-center justify-end space-x-2 mt-8">
                                    <Link :href="route('programs.installment')" class="flex items-center py-2 px-2 text-xs text-white font-mono bg-danger-button hover:bg-danger-button-hover rounded-md">
                                        <IcCancel class="text-lg mr-0.5" />
                                        Cancel
                                    </Link>
                                    <button @click="create" :class="{ 'opacity-25 mr-2.5 px-3 py-2 text-md text-white font-semibold bg-primary-500 hover:bg-indigo-700 rounded': form.processing }" 
                                    :disabled="form.processing" class="flex items-center py-2 px-2 text-xs text-white font-mono bg-success-button hover:bg-success-button-hover rounded-md">
                                        <BxCheckDouble class="text-lg mr-0.5" />
                                        Submit
                                    </button>
                                </div>
                            </div>
                            <!---Approve Modal-->
                            <Modal :show="showConfirmModal" @close="closeModal">
                                <div class="p-6">
                                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg>
                                    <div class="flex justify-center">
                                        <h2 class="text-lg font-semibold text-slate-800">
                                        Are you sure you want to save this changes?
                                        </h2>
                                    </div>
                                    <div class="mt-6 flex justify-center space-x-2">
                                        <SuccessButton @click="$event => createInstallment(program.id)" class="text-xs font-extralight py-3">Yes, I'm sure</SuccessButton>
                                        <SecondaryButton @click="closeModal" class="text-xs font-extralight py-3">No, Cancel</SecondaryButton>
                                    </div>
                                </div>
                            </Modal>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
