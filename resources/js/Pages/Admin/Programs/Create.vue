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
    total_month: null,
    total_year: null,
    frequency: "",
    end_date: props.program?.end_date,
    program_id : props.program?.program_id,
    amount: [],
    installment_payment_date: [],
    installment_name: []
})

watch(
    () => props.program,
    () => {
        form.end_date = props.program?.end_date
    }

);

const showConfirmCreateAllocationModal = ref(false)

const create = () => {  
    const getEndDate = document.getElementById('end_date')

    if(getEndDate){
        form.end_date = getEndDate.value;
    }
//if(test){
// console.log(test.value);
//}
    showConfirmCreateAllocationModal.value = true;
};


const closeModalCreate = () => {
    showConfirmCreateAllocationModal.value = false;
}

const createProgram = (id) => {
    try{
        form.amount = inputs.value.map(input => input.amount);
        form.installment_payment_date = inputs.value.map(input => input.installment_payment_date);
        form.installment_name = inputs.value.map(input => input.installment_name);
        form.post(route('programs.store'), {
            onSuccess: (page) => {
            Toast.fire({
                icon: 'success',
                title: 'Program has successfully approved',
            })
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
      { id: 1, name: 'Individu' },
      { id: 2, name: 'Kumpulan' },
      { id: 3, name: 'Pecahan' },
      { id: 4, name: 'Kelompok' },
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
                                <TextInput
                                    id="name"
                                    type="text"
                                    class=" block w-1/2 text-xs"
                                    v-model="form.name"
                                    autofocus
                                    autocomplete="username"
                                    placeholder="Program A"
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
                                                class="mr-2 cursor-pointer"
                                                @click="variable(programType.id)"
                                            />
                                            <label :for="programType.id" class="text-sm">{{ programType.name }}</label>
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
                                    <div class="my-3">
                                        <select class="rounded-md text-xs border border-gray-300 w-1/2" v-model="form.allocation" id="allocation">
                                            <option value="">Choose Allocation</option>
                                            <option v-for="allocation in allocations" :key="allocation.id" :value="allocation.id">{{ allocation.allocation_source }}</option>
                                        </select>
                                    </div>
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
                                        required
                                    />

                                    <InputError class="mt-2" :message="form.errors.allocation_rate" />
                                </div>
                            </div>
                            <!--Payment Date-->
                            <div class="grid grid-cols-12">
                                <div class="col-span-2">
                                    <InputLabel class="mt-2" value="Payment Date" />
                                </div>
                                
                                <div class="col-span-10">
                                    <TextInput
                                        id="payment_date"
                                        type="date"
                                        class="mt-1 block w-1/2 text-xs"
                                        v-model="form.payment_date"
                                        required
                                    />

                                    <InputError class="mt-2" :message="form.errors.payment_date" />
                                </div>
                            </div>

                            <!--<p class="text-xs font-bold mt-20">Nota: Sila tetapkan maklumat peruntukan yang lain pada maklumat penerima</p>-->
                        </div>

                        <!--Textfield based on program type Kumpulan-->
                        <div v-if="showKumpulan">
                            <!--Allocation Source-->
                            <div class="grid grid-cols-12">
                                <div class="col-span-2">
                                    <InputLabel class="mt-4" value="Allocation Source" />
                                </div>
                                
                                <div class="col-span-10">
                                    <div class="my-3">
                                        <select class="rounded-md text-xs border border-gray-300 w-1/2" v-model="form.allocation" id="allocation">
                                            <option value="">Choose Allocation</option>
                                            <option v-for="allocation in allocations" :key="allocation.id" :value="allocation.id">{{ allocation.allocation_source }}</option>
                                        </select>
                                    </div>
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
                                        required
                                    />

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
                                            <div class="mb-4">
                                                <template v-for="frequency in frequencies" :key="frequency.id">
                                                    <div class="flex items-center" >
                                                        <input
                                                            :key="frequency.id"
                                                            type="radio"
                                                            :id="frequency.id"
                                                            v-model="form.frequency"
                                                            :value="frequency.id"
                                                            class="mr-2 mb-1 cursor-pointer"
                                                            @click="variable(frequency.id)"
                                                        />
                                                        <label :for="frequency.id" class="text-xs mb-1 font-medium">{{ frequency.name }}</label>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                        <!--Small note for Kekerapan-->
                                        <div class="col-span-6">
                                            <div v-if="form.frequency == 5">
                                                <p class="text-xxs-custom font-bold mt-0.5 -ml-9" v-if="form.payment_date">(Every {{ getDayWithSuffix }} of the month)</p>
                                            </div>
                                            <div v-if="form.frequency == 6">
                                                <p class="text-xxs-custom font-bold mt-5 -ml-12" v-if="form.payment_date">(Every {{ getSelectedMonth }} {{ getDayWithSuffix }})</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            <!--Date Payment-->
                            <div class="grid grid-cols-12 mb-2">
                                <div class="col-span-2">
                                    <InputLabel class="mt-2" value="Date Payment" />
                                </div>
                                
                                <div class="col-span-10">
                                    <TextInput
                                        id="payment_date"
                                        type="date"
                                        class="mt-1 block w-1/2 text-xs"
                                        v-model="form.payment_date"
                                        required
                                    />
                                    <InputError class="mt-2" :message="form.errors.payment_date" />
                                </div>
                            </div>
                            <!--Month Selected-->
                            <div v-if="form.frequency == 5">
                                <!--Total Month-->
                                <div  class="grid grid-cols-12 mb-2">
                                    <div class="col-span-2">
                                        <InputLabel class="mt-2" value="Total Month" />
                                    </div>
                                    
                                    <div class="col-span-10">
                                        <TextInput
                                            id="valid_until"
                                            type="number"
                                            class="mt-1 block w-1/2 text-xs"
                                            v-model="form.total_month"
                                            required
                                        />
                                        <InputError class="mt-2" :message="form.errors.total_month" />
                                    </div>
                                </div>
                                <!--End Date-->
                                <div class="grid grid-cols-12">
                                    <div class="col-span-2">
                                        <InputLabel class="mt-2" value="End Date" />
                                    </div>

                                    <div class="col-span-10">
                                        <TextInput
                                            id="end_date"
                                            type="date"
                                            class="mt-1 block w-1/2 text-xs"
                                            v-model="form.end_date"
                                            :value="calculateEndDate"
                                        />
                                    </div>
                                </div>
                            </div>
                            <!--Year Selected-->
                            <div v-if="form.frequency == 6">
                                <!--Total Year-->
                                <div class="grid grid-cols-12 mb-2">
                                    <div class="col-span-2">
                                        <InputLabel class="mt-2" value="Total Year" />
                                    </div>
                                    
                                    <div class="col-span-10">
                                        <TextInput
                                            id="valid_until"
                                            type="number"
                                            class="mt-1 block w-1/2 text-xs"
                                            v-model="form.total_year"
                                            required
                                        />
                                        <InputError class="mt-2" :message="form.errors.total_year" />
                                    </div>
                                </div>
                                <!--End Date-->
                                <div class="grid grid-cols-12">
                                    <div class="col-span-2">
                                        <InputLabel class="mt-2" value="End Date" />
                                    </div>

                                    <div class="col-span-10">
                                        <TextInput
                                            id="end_date"
                                            type="date"
                                            v-model="form.end_date"
                                            class="mt-1 block w-1/2 text-xs"
                                            :value="calculateEndDateYear"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--Textfield based on program type Pecahan-->
                        <div v-if="showPecahan">
                            <!--Allocation Source-->
                            <div class="grid grid-cols-12">
                                <div class="col-span-2">
                                    <InputLabel class="mt-4" value="Allocation Source" />
                                </div>
                                
                                <div class="col-span-10">
                                    <div class="my-3">
                                        <select class="rounded-lg text-xs border border-gray-300 w-1/2" v-model="form.allocation" id="allocation">
                                            <option value="">Choose Allocation</option>
                                            <option v-for="allocation in allocations" :key="allocation.id" :value="allocation.id">{{ allocation.allocation_source }}</option>
                                        </select>
                                    </div>
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
                                        required
                                        style="appearance: textfield; -webkit-appearance: textfield;"
                                    />

                                    <InputError class="mt-2" :message="form.errors.allocation_rate" />
                                </div>
                            </div>
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

                        <!--Textfield based on program type Kelompok-->
                        <div v-if="showKelompok">
                            <!--Allocation Source-->
                            <div class="grid grid-cols-12">
                                <div class="col-span-2">
                                    <InputLabel class="mt-4" value="Allocation Source" />
                                </div>
                                
                                <div class="col-span-10">
                                    <div class="my-3">
                                        <select class="rounded-lg text-xs border border-gray-300 w-1/2" v-model="form.allocation" id="allocation">
                                            <option value="">Choose Alloction</option>
                                            <option v-for="allocation in allocations" :key="allocation.id" :value="allocation.id">{{ allocation.allocation_source }}</option>
                                        </select>
                                    </div>
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
                                        required
                                        style="appearance: textfield; -webkit-appearance: textfield;"
                                    />

                                    <InputError class="mt-2" :message="form.errors.allocation_rate" />
                                </div>
                            </div>
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
                        <Modal :show="showConfirmCreateAllocationModal" @close="closeModalCreate">
                            <div class="p-6">
                                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                </svg>
                                <div class="flex justify-center">
                                    <h2 class="text-lg font-semibold text-slate-800">
                                    Are you sure you want add this program?
                                    </h2>
                                </div>
                                <div class="mt-6 flex justify-center space-x-2">
                                    <SuccessButton @click="$event => createProgram()" class="text-xs font-extralight py-3">Yes, I'm sure</SuccessButton>
                                    <SecondaryButton @click="closeModalCreate" class="text-xs font-extralight py-3">No, Cancel</SecondaryButton>
                                </div>
                            </div>
                        </Modal>
                    </form>
                </Content>
            </div>
        </div>
    </AdminLayout>
</template>
