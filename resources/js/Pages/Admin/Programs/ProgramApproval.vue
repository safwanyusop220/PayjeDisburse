<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from "vue";

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

import { MdOutlinedPendingActions } from "@kalimahapps/vue-icons";
import { BxCheckDouble } from "@kalimahapps/vue-icons";
import { BsSendCheck } from "@kalimahapps/vue-icons";

import { IcCancel } from "@kalimahapps/vue-icons";
import { QuSendCancelled } from "@kalimahapps/vue-icons";
import { BsCalendar4Week } from "@kalimahapps/vue-icons";
import { CdCalendar } from "@kalimahapps/vue-icons";
import { BsCalendar2Week } from "@kalimahapps/vue-icons";

const props = defineProps({
    program: {
        type: Object,
        required: true
    },
    programType:true,
    allocation:true,
    programTypes: Array,
    frequencies: Array,
    installmentPrograms: {
        type: Object,
        required: true
    }
})

const form = useForm({
    name: props.program?.name,
    type_id: props.program?.type_id,
    allocation: props.program?.allocation,
    allocation_rate: props.program?.allocation_rate,
    latest_payment: props.program?.latest_payment,
    valid_until: props.program?.valid_until,
    payment_date: props.program?.payment_date,
    total_month: props.program?.total_month,
    frequency: props.program?.frequency,
    status: props.program?.status,
    reject_reason: props.program?.reject_reason,

});

watch(
    () => props.program,
    () => {
        form.status = props.program?.status,
        form.type_id = props.program?.type_id,
        form.allocation = props.program?.allocation
    }

);


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

const disableButtonReject = ref(true);
const disableButtonApprove = ref(true);

const frequencies = ref([
      { id: 5, name: 'Monthly' },
      { id: 6, name: 'Yearly' },
      { id: 7, name: 'One time' },
    ]);

const frequencyTexts = {
  5: "1 haribulan setiap bulan",
  6: "1 haribulan januari setiap tahun",
};

const approve = () => {
    showConfirmApproveModal.value = true;
};

const showConfirmApproveModal = ref(false)

const closeModalApprove = () => {
    showConfirmApproveModal.value = false;
}

const approveProgram = (id) => {
    try{
        form.put(route('programs.approve', props.program?.id), {
            onSuccess: (page) => {
            Toast.fire({
                icon: 'success',
                title: 'Program has successfully approved'
            })
            showConfirmApproveModal.value = false;
            disableButtonReject.value = false
            disableButtonApprove.value = false
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


const reject = () => {
    showConfirmRejectModal.value = true;
};

const showConfirmRejectModal = ref(false)


const closeModalReject = () => {
    showConfirmRejectModal.value = false;
}

const rejectProgram = async (id) => {
const { value } = await Swal.fire({
    input: 'textarea',
    inputLabel: 'Reason for reject',
    inputPlaceholder: 'Type your message here...',
    inputAttributes: {
        'aria-label': 'Type your message here'
    },
    showCancelButton: false,
    allowOutsideClick: false,
    preConfirm: (value) => {
      if (!value.trim()) {
        Swal.showValidationMessage('Message is required');
      }
      showConfirmRejectModal.value = false;
      return value;
    }
  });

  form.reject_reason = value;

  try {

    await form.post(route('programs.reject', props.program?.id), {
    value, 
    onSuccess: (page) => {
        Toast.fire({
        icon: 'success',
        title: 'Program has rejected successfully'
        });
        showConfirmRejectModal.value = false;
        disableButtonReject.value = false
        disableButtonApprove.value = false
    },
    });
  } catch (err) {
    console.error(err);
    Swal.fire('Error!', 'There was an error while rejecting the allocation.', 'error');
  }
};

const getStatusText = (status) => {
    switch (status) {
        case 1:
            return 'Processing';
        case 2:
            return 'Processed';
        case 3:
            return 'Approved';
        case 4:
            return 'Rejected';
        default:
            return 'Unknown Status';
    }
};

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


</script>

<template>
    <Head title="Approve Receivers" />

    <AdminLayout>
        <div class="mt-14">
            <div class="w-full mx-auto">
                <div class="flex justify-between pt-4">
                    <nav class="text-sm font-semibold" aria-label="Breadcrumb">
                        <ol class="list-none p-0 inline-flex">
                            <li class="flex items-center">
                                <BreadcrumbInitial :href="route('programs.index')">Program</BreadcrumbInitial>
                            </li>
                            <li class="flex items-center">
                                <BreadcrumbActive :href="route('programs.waitingApproval')">Waiting Approval</BreadcrumbActive>
                            </li>
                            <li class="flex items-center">
                                <BreadcrumbCurrent>Approval</BreadcrumbCurrent>
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
                                <p class="text-xs mt-2 font-bold">
                                    {{ program.name }}
                                </p>
                            </div>
                        </div>
                        <!--Program Type-->
                        <div class="grid grid-cols-12">
                            <div class="col-span-2">
                                <InputLabel class="mt-5 font-bold" value="Program Type" />
                            </div>
                            <div class="col-span-10">
                                <p class="text-xs mt-5 font-bold">
                                    {{ program.program_type.name }}
                                </p>
                            </div>
                        </div>
                        
                        <!--Textfield based on program type Individu-->
                        <div v-if="form.type_id == 1">
                            <!--Allocation Source-->
                            <div class="grid grid-cols-12">
                                <div class="col-span-2">
                                    <InputLabel class="mt-5" value="Allocation Source" />
                                </div>
                                
                                <div class="col-span-10">
                                    <p class="text-xs mt-5 font-bold">
                                        {{ program.allocation_id.allocation_source }}
                                    </p>
                                </div>
                            </div>
                            <!--Allocation Rate-->
                            <div class="grid grid-cols-12">
                                <div class="col-span-2">
                                    <InputLabel class="mt-5" value="Allocation Rate" />
                                </div>
                                
                                <div class="col-span-10">
                                    <div class="text-xs mt-5 font-bold">
                                        <span class="mr-0.5">RM</span>
                                        {{ program.allocation_rate }}
                                    </div>
                                </div>
                            </div>
                            <!--Payment Date-->
                            <div class="grid grid-cols-12">
                                <div class="col-span-2">
                                    <InputLabel class="mt-5" value="Payment Date" />
                                </div>
                                
                                <div class="col-span-10">
                                    <p class="text-xs mt-5 font-bold">
                                        {{ program.payment_date }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!--Textfield based on program type Kumpulan-->
                        <div v-if="form.type_id == 2">
                            <!--Allocation Source-->
                            <div class="grid grid-cols-12">
                                <div class="col-span-2">
                                    <InputLabel class="mt-5" value="Allocation Source" />
                                </div>
                                
                                <div class="col-span-10">
                                    <p class="text-xs mt-5 font-bold">
                                        {{ program.allocation_id.allocation_source }}
                                    </p>
                                </div>
                            </div>
                            <!--Allocation Rate-->
                            <div class="grid grid-cols-12">
                                <div class="col-span-2">
                                    <InputLabel class="mt-5" value="Allocation Rate" />
                                </div>
                                
                                <div class="col-span-10">
                                    <div class="text-xs mt-5 font-bold">
                                        <span class="mr-0.5">RM</span>
                                        {{ program.allocation_rate }}
                                    </div>
                                </div>
                            </div>
                            <!--Frequency-->
                            <div class="grid grid-cols-12">
                                <div class="col-span-2">
                                    <InputLabel class="mt-5 font-bold" value="Frequency" />
                                </div>
                                <div class="col-span-10">
                                    <p class="text-xs mt-5 font-bold">
                                        <div v-if="program.frequency == 5">
                                            <p>Monthly</p>
                                            <p class="text-xxs-custom">(Every {{ getDayWithSuffix }} of the month)
                                            </p>
                                        </div>
                                        <div v-if="program.frequency == 6">
                                            <p>Yearly</p>
                                            <p class="text-xxs-custom">(Every {{ getSelectedMonth }} {{ getDayWithSuffix }})
                                            </p>
                                        </div>
                                    </p>                                                
                                </div>
                            </div>
                            <!--Payment Date-->
                            <div class="grid grid-cols-12 mt-2">
                                <div class="col-span-2">
                                    <InputLabel class="mt-2" value="Payment Date" />
                                </div>
                                
                                <div class="col-span-10">
                                    <p class="text-xs mt-2 font-bold">
                                        {{ program.payment_date }}
                                    </p>
                                </div>
                            </div>
                            <!--Total Month-->
                            <div v-if="program.frequency == 5">
                                <div class="grid grid-cols-12 mt-2">
                                    <div class="col-span-2">
                                        <InputLabel class="mt-2" value="Total Month" />
                                    </div>
                                    
                                    <div class="col-span-10">
                                        <p class="text-xs mt-2 font-bold">
                                            {{ program.total_month }} Month(s)
                                        </p>
                                    </div>
                                </div>
                                <!--End Date-->
                                <div class="grid grid-cols-12 mt-2">
                                    <div class="col-span-2">
                                        <InputLabel class="mt-2" value="End Date" />
                                    </div>
                                    
                                    <div class="col-span-10">
                                        <p class="text-xs mt-2 font-bold">
                                            {{ program.end_date }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!--Total Year-->
                            <div v-if="program.frequency == 6">
                                <div class="grid grid-cols-12 mt-2">
                                    <div class="col-span-2">
                                        <InputLabel class="mt-2" value="Total Year" />
                                    </div>
                                    
                                    <div class="col-span-10">
                                        <p class="text-xs mt-2 font-bold">
                                            {{ program.total_month }} Year(s)
                                        </p>
                                    </div>
                                </div>
                                <!--End Date-->
                                <div class="grid grid-cols-12 mt-2">
                                    <div class="col-span-2">
                                        <InputLabel class="mt-2" value="End Date" />
                                    </div>
                                    
                                    <div class="col-span-10">
                                        <p class="text-xs mt-2 font-bold">
                                            {{ program.end_date }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--Textfield based on program type Pecahan-->
                        <div v-if="form.type_id == 3">
                            <!--Allocation Source-->
                            <div class="grid grid-cols-12 mt-2">
                                <div class="col-span-2">
                                    <InputLabel class="mt-2" value="Allocation Source" />
                                </div>
                                
                                <div class="col-span-10">
                                    <p class="text-xs mt-2 font-bold">
                                        {{ program.allocation_id.allocation_source }}
                                    </p>
                                </div>
                            </div>
                            <!--Allocation Rate-->
                            <div class="grid grid-cols-12 mt-2">
                                <div class="col-span-2">
                                    <InputLabel class="mt-2" value="Allocation Rate" />
                                </div>
                                
                                <div class="col-span-10">
                                    <div class="text-xs mt-2 font-bold">
                                        <span class="mr-0.5">RM</span>
                                        {{ program.allocation_rate }}
                                    </div>
                                </div>
                            </div>
                            <!--Installment-->
                            <div class="grid grid-cols-12 mt-2">
                                <div class="col-span-2">
                                    <InputLabel class="mt-2 mb-4" value="Payemnt Installment" />
                                </div>
                                <div class="col-span-10">
                                    <div v-for="(installmentProgram, index) in installmentPrograms" :key="index" class="flex  space-x-5">
                                        <div class="w-1/7">
                                            <p class="text-xs mt-2 font-bold flex items-center justify-between">
                                                {{ installmentProgram.installment_payment_date }}
                                            </p>
                                        </div>
                                        <div class="w-1/7">
                                            <p class="text-xs mt-2 font-bold flex items-center">
                                                <p class="ml-2 mr-0.5">RM</p>
                                                <p class="mr-14">{{installmentProgram.amount }}</p>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--Textfield based on program type Kelompok-->
                        <div v-if="form.type_id == 4">
                            <!--Allocation Source-->
                            <div class="grid grid-cols-12 mt-2">
                                <div class="col-span-2">
                                    <InputLabel class="mt-2" value="Allocation Source" />
                                </div>
                                
                                <div class="col-span-10">
                                    <p class="text-xs mt-2 font-bold">
                                        {{ program.allocation_id.allocation_source }}
                                    </p>
                                </div>
                            </div>
                            <!--Allcotion Rate-->
                            <div class="grid grid-cols-12 mt-2">
                                <div class="col-span-2">
                                    <InputLabel class="mt-2" value="Allocation Rate" />
                                </div>
                                
                                <div class="col-span-10">
                                    <div class="text-xs mt-2 font-bold">
                                        <span class="mr-0.5">RM</span>
                                        {{ program.allocation_rate }}
                                    </div>
                                </div>
                            </div>
                            <!--Installment-->
                            <div class="grid grid-cols-12 mt-2">
                                <div class="col-span-2">
                                    <InputLabel class="mt-2 mb-4" value="Payemnt Installment" />
                                </div>
                                <div class="col-span-10">
                                    <div v-for="(installmentProgram, index) in installmentPrograms" :key="index" class="flex  space-x-5">
                                        <div class="w-1/7">
                                            <p class="text-xs mt-2 font-bold flex items-center">
                                                <p class="mr-14">{{installmentProgram.installment_name }}</p>
                                            </p>
                                        </div>
                                        <div class="w-1/7">
                                            <p class="text-xs mt-2 font-bold flex items-center justify-between">
                                                {{ installmentProgram.installment_payment_date }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--Status-->
                        <div class="grid grid-cols-12">
                            <div class="col-span-2">
                                <InputLabel class="mt-6" value="Approval Status" />
                            </div>
                            <div class="col-span-10">
                                <div class="mt-6 text-xs font-bold text-gray-900">
                                    {{ getStatusText(form.status)}}
                                </div>
                            </div>
                        </div>

                        <div v-if="form.status == 4" class="grid grid-cols-12">
                            <div class="col-span-2">
                                <InputLabel class="mt-6" value="Reason" />
                            </div>
                            <div class="col-span-10">
                                <div class="mt-5 text-xs font-bold text-gray-900">
                                    {{ form.reject_reason}}
                                </div>
                            </div>
                        </div>
                        

                        <div class="flex justify-end space-x-2 mb-2">
                            <button v-if="disableButtonReject"
                                    :class="{ 'opacity-25 px-3 py-3 text-md text-white font-semibold bg-red-500 hover:bg-red-700 rounded-full': form.processing }"
                                    :disabled="form.processing"
                                    class="flex items-center py-2 px-2 text-xs text-white font-mono bg-danger-button hover:bg-danger-button-hover rounded-md"
                                    @click="reject"
                                    >
                                    <IcCancel class="text-lg mr-0.5" />
                                    Reject
                                </button>

                                <a v-if="!disableButtonReject"
                                    class="flex items-center py-2 px-2 text-xs text-white font-mono bg-danger-button-hover rounded-md"
                                    >
                                    <IcCancel class="text-lg mr-0.5" />
                                    Reject
                                </a>

                                <button v-if="disableButtonApprove"
                                    :disabled="form.processing"
                                    class="flex items-center py-2 px-2 text-xs text-white font-mono bg-success-button hover:bg-success-button-hover rounded-md"
                                    @click="approve">
                                    <BxCheckDouble class="text-lg mr-0.5" />
                                    Approve
                                </button>

                                <a v-if="!disableButtonApprove"
                                    class="flex items-center py-2 px-2 text-xs text-white font-mono bg-success-button-hover rounded-md"
                                    >
                                    <BxCheckDouble class="text-lg mr-0.5" />
                                    Approve
                                </a>
                        </div>
                        <!---Approve Modal-->
                        <Modal :show="showConfirmApproveModal" @close="closeModalApprove">
                            <div class="p-6">
                                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                </svg>
                                <div class="flex justify-center">
                                    <h2 class="text-lg font-semibold text-slate-800">
                                    Are you sure you want to Approve this Program?
                                    </h2>
                                </div>
                                <div class="mt-6 flex justify-center space-x-2">
                                    <SuccessButton @click="$event => approveProgram(program.id)" class="text-xs font-extralight py-3">Yes, I'm sure</SuccessButton>
                                    <SecondaryButton @click="closeModalApprove" class="text-xs font-extralight py-3">No, Cancel</SecondaryButton>
                                </div>
                            </div>
                        </Modal>
                        <!--Reject Modal-->
                        <Modal :show="showConfirmRejectModal" @close="closeModalReject">
                            <div class="p-6">
                                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                </svg>
                                <div class="flex justify-center">
                                    <h2 class="text-lg font-semibold text-slate-800">
                                    Are you sure you want to reject this Program?
                                    </h2>
                                </div>
                                <div class="mt-6 flex justify-center space-x-2">
                                    <DangerButton @click="$event => rejectProgram(program.id)" class="text-xs font-extralight py-3">Yes, I'm sure</DangerButton>
                                    <SecondaryButton @click="closeModalReject" class="text-xs font-extralight py-3">No, Cancel</SecondaryButton>
                                </div>
                            </div>
                        </Modal>
                    </form>
                </Content>
            </div>
        </div>
    </AdminLayout>
</template>
