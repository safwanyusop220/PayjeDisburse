<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed  } from "vue";

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

import Content from "@/Components/Content.vue";

import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SuccessButton from "@/Components/SuccessButton.vue";
import BackButton from "@/Components/BackButton.vue";
import Dropdown from '@/Components/Dropdown.vue';

import TextInputXxs from '@/Components/TextInputXxs.vue';
import TextInputMoneyXxs from '@/Components/TextInputMoneyXxs.vue';

// BreadCrumb
import BreadcrumbInitial from "@/Components/BreadcrumbInitial.vue";
import BreadcrumbActive from "@/Components/BreadcrumbActive.vue";
import BreadcrumbCurrent from "@/Components/BreadcrumbCurrent.vue";

// Icons
import { IcCancel } from "@kalimahapps/vue-icons";
import { QuSendCancelled } from "@kalimahapps/vue-icons";
import { BxCheckDouble } from "@kalimahapps/vue-icons";
import { BsBookmarkPlusFill } from "@kalimahapps/vue-icons";
import { ReDeleteBinFill } from "@kalimahapps/vue-icons";
import { ReDeleteBin5Line } from "@kalimahapps/vue-icons";
import { AkCircleCheckFill } from "@kalimahapps/vue-icons";
import { AkCircleCheck } from "@kalimahapps/vue-icons";
import { FaCircleDot } from "@kalimahapps/vue-icons";
import { ClCircleWarning } from "@kalimahapps/vue-icons";

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
    name: "",
    type_id: "",
    allocation: "",
    allocation_rate: "",
    latest_payment: "",
    payment_date: "",
    total_month: "",
    total_year: "",
    frequency: "",
    end_date: props.program?.end_date,
    program_id : props.program?.program_id,
    amount: [],
    installment_payment_date: [],
    installment_name: [],
})

watch(
    () => props.program,
    () => {
        form.end_date = props.program?.end_date
    }
);

const isFormValid = computed(() => {
  const nameError = form.name === '';
  const typeIdError = form.type_id === '';
  const allocationError = form.allocation === '';
  const allocationRateError = form.allocation_rate === '';
  const paymentDateError = form.payment_date === '';
  const frequencyError = form.frequency === '';
  const totalMonthError = form.total_month === '';
  const endDateError = form.end_date === '';
  const totalYearError = form.total_year === '';

  if(form.type_id == 1){
    return !nameError && !typeIdError && !allocationError && !allocationRateError && !paymentDateError;
  }
  if(form.type_id == 2 && form.frequency == 5)
  {
    return !nameError && !typeIdError && !allocationError && !allocationRateError && !paymentDateError && !frequencyError 
    && !totalMonthError;
  }
  if(form.type_id == 2 && form.frequency == 6)
  {
    return !nameError && !typeIdError && !allocationError && !allocationRateError && !paymentDateError && !frequencyError 
    && !totalYearError;
  }
  if(form.type_id == 2)
  {
    return !nameError && !typeIdError && !allocationError && !allocationRateError && !paymentDateError && !frequencyError;
  }
//   if(form.type_id == 3){
//     return !nameError && !typeIdError && !allocationError && !allocationRateError && !amountError && !installmentPaymentDateError;
//   }
if(form.type_id == 3){
    return !nameError && !typeIdError && !allocationError && !allocationRateError;
}

if(form.type_id == 4){
    return !nameError && !typeIdError && !allocationError && !allocationRateError;
}


});


const showConfirmCreateModal = ref(false)

const create = () => {  
    const getEndDate = document.getElementById('end_date')

    if(getEndDate){
        form.end_date = getEndDate.value;
    }
    if (isFormValid.value) {
        showConfirmCreateModal.value = true;
  }
};


const closeModalCreate = () => {
    showConfirmCreateModal.value = false;
}

const createProgram = (id) => {
    try{
        form.amount = inputs.value.map(input => input.amount);
        form.installment_payment_date = inputs.value.map(input => input.installment_payment_date);
        form.installment_name = inputs.value.map(input => input.installment_name);
        form.post(route('programs.store'), {
            onSuccess: (page) => {
            Swal.fire({
                width: 400,
                height: 100,
                html: '<span class="text-sm">Allocation Has Successfully Been Created!</span>',
                icon: 'success',
                confirmButtonText: 'Okay',
                customClass: {
                    content: 'text-lg',
                    confirmButton: 'px-4 py-2 text-white text-xs rounded',
                }
            });
        },

        })
    }catch (err){
        console.log(err)
    }
};

const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  width: 410,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer);
    toast.addEventListener('mouseleave', Swal.resumeTimer);
  },
});


const programTypes = ref([
      { id: 1, name: 'Individual', description:"Please specify allocation information for the recipient's details" },
      { id: 2, name: 'Group', description:"The allocation rate is uniform for all recipients within the same group" },
      { id: 3, name: 'Schedule' },
      { id: 4, name: 'Batch' },
    ]);
    
const showIndividu = ref(false);
const showKumpulan = ref(false);
const showPecahan = ref(false);
const showKelompok = ref(false);


const variable = (programTypeName) => {

    if(programTypeName == "1"){
        showIndividu.value = true;
        showKumpulan.value = false;
        showPecahan.value = false;
        showKelompok.value = false;

    }else if(programTypeName == "2"){
        showIndividu.value = false;
        showKumpulan.value = true;
        showPecahan.value = false;
        showKelompok.value = false;

    }else if(programTypeName == "3"){
        showIndividu.value = false;
        showKumpulan.value = false;
        showPecahan.value = true;
        showKelompok.value = false;

    }else if(programTypeName == "4"){
        showIndividu.value = false;
        showKumpulan.value = false;
        showPecahan.value = false;
        showKelompok.value = true;
    }
      console.log(`Clicked program type: ${programTypeName}`);
};

const frequencies = ref([
      { id: 5, name: 'Monthly' },
      { id: 6, name: 'Yearly' },
      { id: 7, name: 'One time' },
    ]);

const selectedDate = computed(() => {
  return form.payment_date ? new Date(form.payment_date) : null;
});

const getDayWithSuffix = computed(() => {
  if (selectedDate.value) {
    const day = selectedDate.value.getDate();
    const dayStr = day.toString();
    const lastDigit = dayStr.charAt(dayStr.length - 1);
    let suffix = 'th';

    if (dayStr !== '11' && dayStr !== '12' && dayStr !== '13') {
      if (lastDigit === '1') {
        suffix = 'st';
      } else if (lastDigit === '2') {
        suffix = 'nd';
      } else if (lastDigit === '3') {
        suffix = 'rd';
      }
    }

    return `${day}${suffix}`;
  }

  return '';
});

const getSelectedMonth = computed(() => {
  if (selectedDate.value) {
    const month = selectedDate.value.toLocaleString('default', { month: 'long' });
    return month;
  }
  return '';
});

const calculateEndDate = computed(() => {
  if (form.payment_date && form.total_month) {
    const startDate = new Date(form.payment_date);
    const endDate = new Date(startDate);
    endDate.setMonth(startDate.getMonth() + parseInt(form.total_month));
    return endDate.toISOString().slice(0, 10); // Format as yyyy-mm-dd
  }
  return '';
});

const calculateEndDateYear = computed(() => {
  if (form.payment_date && form.total_year) {
    const startDate = new Date(form.payment_date);
    const endDate = new Date(startDate);
    endDate.setFullYear(startDate.getFullYear() + parseInt(form.total_year));
    endDate.setDate(endDate.getDate());
    return endDate.toISOString().slice(0, 10); // Format as yyyy-mm-dd
  }
  return '';
});


const inputs = ref([]);

const addInput = () => {
  inputs.value.push({ amount: '', installment_payment_date: '', installment_name: ''  });
};

const removeInput = (index) => {
  inputs.value.splice(index, 1);
};

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
    <Head title="Create new Program" />

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
                                <BreadcrumbCurrent>Create</BreadcrumbCurrent>
                            </li>
                        </ol>
                    </nav>
                </div>
                <Content>
                    <form @submit.prevent="submit">
                        <!--Program Name-->
                        <div class="grid grid-cols-12 mt-4">
                            <div class="col-span-2">
                                <InputLabel class="mt-1.5" value="Program Name" />
                            </div>
                            <div class="col-span-10">
                                <TextInputXxs
                                    id="name"
                                    type="text"
                                    class=" block w-1/3 text-xs"
                                    v-model="form.name"
                                    autofocus
                                    autocomplete="username"
                                    placeholder="Program A"
                                />
                                <div v-if="form.name === ''" class="text-red-500 text-xxs-custom ml-2">* Program Name is required</div>
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
                                                class="mr-2 mb-1 cursor-pointer focus:ring-transparent"
                                                @click="variable(programType.id)"
                                            />
                                            <label :for="programType.id" class="text-xxs mb-1">{{ programType.name }}</label>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                        
                        <!--Textfield based on program type Individu-->
                        <div v-if="showIndividu">
                            <!--Allocation Source-->
                            <div class="grid grid-cols-12">
                                <div class="col-span-2">
                                    <InputLabel class="mt-4" value="Allocation Source" />
                                </div>
                                
                                <div class="col-span-10">
                                    <div class="mt-3">
                                        <select class="h-[30px] text-primary-700 border-none bg-primary-50 text-xs rounded-xl w-1/3" v-model="form.allocation" id="allocation">
                                            <option value="" disabled>Choose Allocation</option>
                                            <option v-for="allocation in allocations" :key="allocation.id" :value="allocation.id">{{ allocation.allocation_source }}
                                            </option>
                                        </select>

                                        <div v-if="form.allocation === ''" class="text-red-500 text-xxs-custom">* Allocation source is required</div>
                                        <InputError class="mt-2" :message="form.errors.allocation" />
                                    </div>
                                </div>
                            </div>
                            <!--Allocation Rate-->
                            <div class="grid grid-cols-12">
                                <div class="col-span-2">
                                    <InputLabel class="mt-4" value="Allocation Rate" />
                                </div>
                                
                                <div class="col-span-10 w-1/3">
                                    <v-money-spinner
                                        id="total_allocation"
                                        class="mt-3 rounded-xl text-xs bg-primary-50 block border border-none focus:ring-0 text-primary-700 focus:ring-primary-50 focus:border-blue-500"
                                        v-model="form.allocation_rate"
                                        v-bind="config">
                                    </v-money-spinner>

                                    <div v-if="form.allocation_rate === ''" class="text-red-500 text-xxs-custom">* Allocation Rate is required</div>
                                    <InputError class="mt-2" :message="form.errors.allocation_rate" />
                                </div>
                            </div>
                            <!--Payment Date-->
                            <div class="grid grid-cols-12">
                                <div class="col-span-2">
                                    <InputLabel class="mt-4" value="Payment Date" />
                                </div>
                                
                                <div class="col-span-10">
                                    <TextInputXxs
                                        id="payment_date"
                                        type="date"
                                        class="mt-3 block w-1/3 text-xs"
                                        v-model="form.payment_date"
                                    />
                                    <div v-if="form.payment_date === ''" class="text-red-500 text-xxs-custom">* Payment date is required</div>
                                    <InputError class="mt-2" :message="form.errors.payment_date" />
                                </div>
                            </div>
                        </div>

                        <!--Textfield based on program type Kumpulan-->
                        <div v-if="showKumpulan">
                            <!--Allocation Source-->
                            <div class="grid grid-cols-12">
                                <div class="col-span-2">
                                    <InputLabel class="mt-4" value="Allocation Source" />
                                </div>
                                
                                <div class="col-span-10">
                                    <div class="mt-3">
                                        <select class="h-[30px] text-primary-700 border-none bg-primary-50 text-xs rounded-lg w-1/3" v-model="form.allocation" id="allocation">
                                            <option value="" disabled>Choose Allocation</option>
                                            <option v-for="allocation in allocations" :key="allocation.id" :value="allocation.id">{{ allocation.allocation_source }}
                                            </option>
                                        </select>

                                        <div v-if="form.allocation === ''" class="text-red-500 text-xxs-custom">* Allocation source is required</div>
                                        <InputError class="mt-2" :message="form.errors.allocation" />
                                    </div>
                                </div>
                            </div>
                            <!--Allocation Rate-->
                            <div class="grid grid-cols-12 mb-3">
                                <div class="col-span-2">
                                    <InputLabel class="mt-2" value="Allocation Rate" />
                                </div>
                                
                                <div class="col-span-10  w-1/3">
                                    <v-money-spinner
                                        id="total_allocation"
                                        class="mt-3 rounded-xl text-xs bg-primary-50 block border border-none focus:ring-0 text-primary-700 focus:ring-primary-50 focus:border-blue-500"
                                        v-model="form.allocation_rate"
                                        v-bind="config">
                                    </v-money-spinner>

                                    <div v-if="form.allocation_rate === ''" class="text-red-500 text-xxs-custom">* Allocation rate is required</div>
                                    <InputError class="mt-2" :message="form.errors.allocation_rate" />
                                </div>
                            </div>
                            <!--Frequency-->
                            <div class="grid grid-cols-12">
                                <div class="col-span-2">
                                    <InputLabel class="font-bold" value="Frequency" />
                                </div>
                                
                                <div class="col-span-10">
                                    <div class="grid grid-cols-7">
                                        <div class="col-span-1">
                                            <div class="">
                                                <template v-for="frequency in frequencies" :key="frequency.id">
                                                    <div class="flex items-center" >
                                                        <input
                                                            :key="frequency.id"
                                                            type="radio"
                                                            :id="frequency.id"
                                                            v-model="form.frequency"
                                                            :value="frequency.id"
                                                            class="mr-2 mb-1 cursor-pointer focus:ring-transparent"
                                                            @click="variable(frequency.id)"
                                                        />
                                                        <label :for="frequency.id" class="text-xxs mb-1 font-normal">{{ frequency.name }}</label>
                                                    </div>
                                                </template>
                                                <div v-if="form.frequency === ''" class="text-red-500 text-xxs-custom">* Frequency is required</div>
                                                <InputError class="mt-2" :message="form.errors.frequency" />
                                            </div>
                                        </div>
                                        <!--Small note for Kekerapan-->
                                        <div class="col-span-6">
                                            <div v-if="form.frequency == 5">
                                                <p class="text-xxs-custom font-bold -ml-14" v-if="form.payment_date">(Every {{ getDayWithSuffix }} of the month)</p>
                                            </div>
                                            <div v-if="form.frequency == 6">
                                                <p class="text-xxs-custom font-bold mt-5 -ml-16" v-if="form.payment_date">(Every {{ getSelectedMonth }} {{ getDayWithSuffix }})</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            <!--Date Payment-->
                            <div class="grid grid-cols-12 mt-1">
                                <div class="col-span-2">
                                    <InputLabel class="mt-2" value="Date Payment" />
                                </div>
                                
                                <div class="col-span-10">
                                    <TextInputXxs
                                        id="payment_date"
                                        type="date"
                                        class="mt-1 block w-1/3 text-xs"
                                        v-model="form.payment_date"
                                    />
                                    <div v-if="form.payment_date === ''" class="text-red-500 text-xxs-custom">* Payment date is required</div>
                                    <InputError class="mt-2" :message="form.errors.payment_date" />
                                </div>
                            </div>
                            <!--Month Selected-->
                            <div v-if="form.frequency == 5">
                                <!--Total Month-->
                                <div  class="grid grid-cols-12 mt-2">
                                    <div class="col-span-2">
                                        <InputLabel class="mt-2" value="Total Month" />
                                    </div>
                                    
                                    <div class="col-span-10">
                                        <TextInputXxs
                                            id="valid_until"
                                            type="number"
                                            class="mt-1 block w-1/3 text-xs"
                                            v-model="form.total_month"
                                        />
                                        <div v-if="form.total_month === ''" class="text-red-500 text-xxs-custom">* Total month is required</div>
                                        <InputError class="mt-2" :message="form.errors.total_month" />
                                    </div>
                                </div>
                                <!--End Date-->
                                <div class="grid grid-cols-12 mt-2">
                                    <div class="col-span-2">
                                        <InputLabel class="mt-2" value="End Date" />
                                    </div>

                                    <div class="col-span-10">
                                        <TextInputXxs
                                            id="end_date"
                                            type="text"
                                            class="mt-1 block w-1/3 text-xs"
                                            v-model="form.end_date"
                                            :value="calculateEndDate"
                                            disabled
                                        />
                                    </div>
                                </div>
                            </div>
                            <!--Year Selected-->
                            <div v-if="form.frequency == 6">
                                <!--Total Year-->
                                <div class="grid grid-cols-12 mt-2">
                                    <div class="col-span-2">
                                        <InputLabel class="mt-2" value="Total Year" />
                                    </div>
                                    
                                    <div class="col-span-10">
                                        <TextInputXxs
                                            id="valid_until"
                                            type="number"
                                            class="mt-1 block w-1/3 text-xs"
                                            v-model="form.total_year"
                                        />
                                        <div v-if="form.total_year === ''" class="text-red-500 text-xxs-custom">* Total year is required</div>
                                        <InputError class="mt-2" :message="form.errors.total_year" />
                                    </div>
                                </div>
                                <!--End Date-->
                                <div class="grid grid-cols-12 mt-2">
                                    <div class="col-span-2">
                                        <InputLabel class="mt-2" value="End Date" />
                                    </div>

                                    <div class="col-span-10">
                                        <TextInputXxs
                                            id="end_date"
                                            type="date"
                                            v-model="form.end_date"
                                            class="mt-1 block w-1/3 text-xs"
                                            :value="calculateEndDateYear"
                                            disabled
                                        />
                                        <div v-if="form.end_date === ''" class="text-red-500 text-xxs-custom">* End date is required</div>
                                        <InputError class="mt-2" :message="form.errors.end_date" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--Textfield based on program type Pecahan-->
                        <div v-if="showPecahan">
                            <!--Allocation Source-->
                            <div class="grid grid-cols-12 mt-3">
                                <div class="col-span-2">
                                    <InputLabel class="" value="Allocation Source" />
                                </div>
                                
                                <div class="col-span-10">
                                    <div class="">
                                        <select class="h-[30px] text-primary-700 border-none bg-primary-50 text-xs rounded-xl w-1/3" v-model="form.allocation" id="allocation">
                                            <option value="" disabled>Choose Allocation</option>
                                            <option v-for="allocation in allocations" :key="allocation.id" :value="allocation.id">{{ allocation.allocation_source }}
                                            </option>
                                        </select>

                                        <div v-if="form.allocation === ''" class="text-red-500 text-xxs-custom">* Allocation source is required</div>
                                        <InputError class="mt-2" :message="form.errors.allocation" />
                                    </div>
                                </div>
                            </div>
                            <!--Allocation Rate-->
                            <div class="grid grid-cols-12 mt-3">
                                <div class="col-span-2">
                                    <InputLabel value="Allocation Rate" />
                                </div>
                                
                                <div class="col-span-10  w-1/3">
                                    <v-money-spinner
                                        id="total_allocation"
                                        class="rounded-xl text-xs bg-primary-50 block border border-none focus:ring-0 text-primary-700 focus:ring-primary-50 focus:border-blue-500"
                                        v-model="form.allocation_rate"
                                        v-bind="config">
                                    </v-money-spinner>

                                    <div v-if="form.allocation_rate === ''" class="text-red-500 text-xxs-custom">* Allocation rate is required</div>
                                    <InputError class="mt-2" :message="form.errors.allocation_rate" />
                                </div>
                            </div>
                            <!--Payment Installment-->
                            <div class="grid grid-cols-12 mt-3">
                                    <div class="col-span-2">
                                        <InputLabel class="mt-2" value="Payment Installment" />
                                    </div>

                                    <div class="col-span-4 flex justify-end">
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
                                            <div class="flex">
                                                <TextInputXxs v-model="input.installment_payment_date" type="date" class="mb-2 w-1/6 mr-2.5 border-gray-300 text-xs focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />

                                                <div>
                                                    <v-money-spinner
                                                    id="total_allocation"
                                                    class="w-[125px] rounded-xl text-xs bg-primary-50 block border border-none focus:ring-0 text-primary-700 focus:ring-primary-50 focus:border-blue-500"
                                                    v-model="input.amount"
                                                    v-bind="config">
                                                    </v-money-spinner>
                                                </div>
                                                <div class="my-auto">
                                                    <button  type="button" class="bg-danger-button hover:bg-danger-button-hover rounded-md text-white py-1.5 px-1.5 ml-7 mb-2 text-xs" @click="removeInput(index)">
                                                        <ReDeleteBin5Line class="text-sm"/>
                                                    </button>
                                                </div>
                                            </div> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--Textfield based on program type Kelompok-->
                        <div v-if="showKelompok">
                            <!--Allocation Source-->
                            <div class="grid grid-cols-12 mt-3">
                                <div class="col-span-2">
                                    <InputLabel value="Allocation Source" />
                                </div>
                                
                                <div class="col-span-10">
                                    <div>
                                        <select class="h-[30px] text-primary-700 border-none bg-primary-50 text-xs rounded-lg w-1/3" v-model="form.allocation" id="allocation">
                                            <option value="" disabled>Choose Allocation</option>
                                            <option v-for="allocation in allocations" :key="allocation.id" :value="allocation.id">{{ allocation.allocation_source }}
                                            </option>
                                        </select>

                                        <div v-if="form.allocation === ''" class="text-red-500 text-xxs-custom">* Allocation source is required</div>
                                        <InputError class="mt-2" :message="form.errors.allocation" />
                                    </div>
                                </div>
                            </div>
                            <!--Allocation Rate-->
                            <div class="grid grid-cols-12 mt-3">
                                <div class="col-span-2">
                                    <InputLabel value="Allocation Rate" />
                                </div>
                                
                                <div class="col-span-10  w-1/3">
                                    <v-money-spinner
                                        id="total_allocation"
                                        class="rounded-xl text-xs bg-primary-50 block border border-none focus:ring-0 text-primary-700 focus:ring-primary-50 focus:border-blue-500"
                                        v-model="form.allocation_rate"
                                        v-bind="config">
                                    </v-money-spinner>

                                    <div v-if="form.allocation_rate === ''" class="text-red-500 text-xxs-custom">* Allocation rate is required</div>
                                    <InputError class="mt-2" :message="form.errors.allocation_rate" />
                                </div>
                            </div>
                            <!--Payment Installment-->
                            <div class="grid grid-cols-12 mt-3">
                                <div class="col-span-2">
                                    <InputLabel class="mt-2" value="Payment Installment" />
                                </div>

                                <div class="col-span-4 flex justify-end">
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
                                            <div class="flex">
                                                <TextInputXxs v-model="input.installment_name" required type="text" class="mb-2 w-1/6 mr-2.5  border-gray-300 text-xs focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="title"/>

                                                <TextInputXxs v-model="input.installment_payment_date" required type="date" class="w-1/6  border-gray-300 text-xs focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
                                                <div v-if="$page.props.errors.installment_payment_date" 
                                                v-text="$page.props.errors.installment_payment_date" class="text-red-500 text-xs"></div>

                                                <div class="my-auto">
                                                    <button  type="button" class="bg-danger-button hover:bg-danger-button-hover rounded-md text-white mb-2 px-1.5 py-1.5 ml-5 text-xs" @click="removeInput(index)">
                                                    <ReDeleteBin5Line class="text-sm"/>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--Button Submit-->
                        <div class="flex items-center justify-end space-x-2 mt-4 mb-2">
                            <Link :href="route('programs.index')" class="flex items-center py-2 px-2 text-xs text-white font-mono bg-danger-button hover:bg-danger-button-hover rounded-md">
                                <IcCancel class="text-lg mr-0.5" />
                                Cancel
                            </Link>
                            <button @click="create" :class="{ 'opacity-25 mr-2.5 px-3 py-2 text-md text-white font-semibold bg-primary-500 hover:bg-indigo-700 rounded': form.processing }" :disabled="form.processing" class="flex items-center py-2 px-2 text-xs text-white font-mono bg-success-button hover:bg-success-button-hover rounded-md">
                                <BxCheckDouble class="text-lg mr-0.5" />
                                Submit
                            </button>
                        </div>
                        <!---Approve Modal-->
                        <Modal :show="showConfirmCreateModal" @close="closeModalCreate">
                            <div class="py-4">
                                <div class="justify-center flex">
                                    <ClCircleWarning class="text-5xl text-gray-400"/>
                                </div>
                                <div class="flex justify-center">
                                    <p class="text-xs font-normal text-slate-800 mt-3">
                                        Are you sure you want to Create this Program?
                                    </p>
                                </div>
                                <div class="mt-6 flex justify-center space-x-2">
                                    <SuccessButton @click="$event => createProgram()" class="text-xs font-extralight">Yes, I'm sure</SuccessButton>
                                    <SecondaryButton @click="closeModalCreate" class="text-xs font-extralight">No, Cancel</SecondaryButton>
                                </div>
                            </div>
                        </Modal>
                    </form>
                </Content>
            </div>
        </div>
    </AdminLayout>
</template>
