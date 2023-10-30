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

const props = defineProps({
    receiver: {
        type: Object,
        required: true
    },
    programType:true,
    allocation:true,
    program:true,
    refBank:true,
    programTypes: Array,
    frequencies: Array,
    allocations: Array,
    programs: Array,
    refBanks: Array,
})

const form = useForm({
    name: props.receiver.name,
    identification_number: props.receiver?.identification_number,
    address: props.receiver?.address,
    phone_number: props.receiver?.phone_number,
    email: props.receiver?.email,
    bank: props.receiver?.bank,
    account_number: props.receiver?.account_number,
    program_id: props.receiver?.program_id,
    type: props.receiver?.type,
    allocation_rate: props.receiver?.allocation_rate,
    status: props.receiver?.status,
    reject_reason: props.receiver?.reject_reason,
});

//Output Value
watch(
    () => props.receiver,
    () => form.status = props.receiver?.status,

);


// Method for "Luluskan" button
const approve = () => {
    showConfirmApproveModal.value = true;
};


// Modal Approve
const showConfirmApproveModal = ref(false)


const closeModalApprove = () => {
    showConfirmApproveModal.value = false;
}

const approveProgram = (id) => {
    try{
        form.put(route('receivers.approve', props.receiver?.id), {
            onSuccess: (page) => {
            Toast.fire({
                icon: 'success',
                title: 'Receiver has successfully approved'
            })
            showConfirmApproveModal.value = false;
            },
        })
    }catch (err){
        console.log(err)
    }
};

// Approve SweetAllert
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

// Method for "Tolak" button
const reject = () => {
    showConfirmRejectModal.value = true;
};

// Modal Decline
const showConfirmRejectModal = ref(false)


const closeModalReject = () => {
    showConfirmRejectModal.value = false;
}

const rejectReceiver = async (id) => {

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

    await form.post(route('receivers.reject', props.receiver?.id), {
    value, 
    onSuccess: (page) => {
        Toast.fire({
        icon: 'success',
        title: 'Receivers has successfully rejected'
        });
        showConfirmRejectModal.value = false;
    },
    });
  } catch (err) {
    console.error(err);
    Swal.fire('Error!', 'There was an error while rejecting the allocation.', 'error');
  }
};

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
      { id: 5, name: 'Bulanan' },
      { id: 6, name: 'Tahunan' },
      { id: 7, name: 'Sekali' },
    ]);

const frequencyTexts = {
  5: "1 haribulan setiap bulan",
  6: "1 haribulan januari setiap tahun",
};

const getTypeText = (type) => {
    switch (type) {
        case 1:
            return 'Individu';
        case 2:
            return 'Kumpulan';
        case 3:
            return 'Pecahan';
        case 4:
            return 'Kelompok';
        default:
            return 'Unknown Status';
    }
};

const getStatusText = (status) => {
    switch (status) {
        case 1:
            return 'Sedang Diproses';
        case 2:
            return 'Telah Diproses';
        case 3:
            return 'Diluluskan';
        case 4:
            return 'Ditolak';
        default:
            return 'Unknown Status';
    }
};

</script>

<template>
    <Head title="Update Receiver" />

    <AdminLayout>
        <div class="mt-14">
            <div class="w-full mx-auto">
                <div class="overflow-hidden">
                    <div class="flex justify-between pt-4">
                        <nav class="text-sm font-semibold" aria-label="Breadcrumb">
                            <ol class="list-none p-0 inline-flex">
                                <li class="flex items-center">
                                    <BreadcrumbInitial :href="route('receivers.index')">Receiver</BreadcrumbInitial>
                                </li>
                                <li class="flex items-center">
                                    <BreadcrumbActive :href="route('receivers.waitingApproval')">Waiting Approval</BreadcrumbActive>
                                </li>
                                <li class="flex items-center">
                                    <BreadcrumbCurrent>Approval</BreadcrumbCurrent>
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <Content>
                        <form @submit.prevent="submit">
                            <div class="grid grid-cols-2">
                                <div class="col-span-1">
                                    <!--Name-->
                                    <div class="grid grid-cols-7 mb-2 mt-4">
                                        <div class="col-span-2">
                                            <InputLabel class="mt-1.5" value="Receiver Name" />
                                        </div>
                                        <div class="col-span-5">
                                            <TextInput
                                                id="name"
                                                type="text"
                                                class=" block w-4/5 text-xs"
                                                v-model="form.name"
                                                autofocus
                                            />

                                            <InputError class="mt-2" :message="form.errors.name" />
                                        </div>
                                    </div>
                                    <!--Identification Number-->
                                    <div class="grid grid-cols-7 mb-2">
                                        <div class="col-span-2">
                                            <InputLabel class="mt-1.5" value="IC Number" />
                                        </div>
                                        <div class="col-span-5">
                                            <TextInput
                                                id="identification_number"
                                                type="text"
                                                class=" block w-4/5 text-xs"
                                                v-model="form.identification_number"
                                                autofocus
                                                autocomplete="username"
                                            />

                                            <InputError class="mt-2" :message="form.errors.identification_number" />
                                        </div>
                                    </div>
                                    <!--Address-->
                                    <div class="grid grid-cols-7 mb-2">
                                        <div class="col-span-2">
                                            <InputLabel class="mt-1.5" value="Address" />
                                        </div>
                                        <div class="col-span-5">
                                            <TextInput
                                                id="address"
                                                type="text"
                                                class=" block w-4/5 text-xs"
                                                v-model="form.address"
                                                autofocus
                                                autocomplete="username"
                                            />
                                            <InputError class="mt-2" :message="form.errors.address" />
                                        </div>
                                    </div>
                                    <!--Postcode-->
                                    <div class="grid grid-cols-7 mb-2">
                                        <div class="col-span-2">
                                            <InputLabel class="mt-1.5" value="Postcode" />
                                        </div>
                                        <div class="col-span-5">
                                            <TextInput
                                                id="postcode"
                                                type="text"
                                                class=" block w-4/5 text-xs"
                                                v-model="form.postcode"
                                                autofocus
                                                autocomplete="username"
                                            />

                                            <InputError class="mt-2" :message="form.errors.postcode" />
                                        </div>
                                    </div>
                                    <!--Phone Number-->
                                    <div class="grid grid-cols-7 mb-2">
                                        <div class="col-span-2">
                                            <InputLabel class="mt-1.5" value="Phone Number" />
                                        </div>
                                        <div class="col-span-5">
                                            <TextInput
                                                id="phone_number"
                                                type="text"
                                                class=" block w-4/5 text-xs"
                                                v-model="form.phone_number"
                                                autofocus
                                                autocomplete="username"
                                                placeholder="43000"
                                            />
                                            <InputError class="mt-2" :message="form.errors.phone_number" />
                                        </div>
                                    </div>
                                    <!--Email-->
                                    <div class="grid grid-cols-7 mb-2">
                                        <div class="col-span-2">
                                            <InputLabel class="mt-1.5" value="Email" />
                                        </div>
                                        <div class="col-span-5">
                                            <TextInput
                                                id="email"
                                                type="text"
                                                class=" block w-4/5 text-xs"
                                                v-model="form.email"
                                                autofocus
                                                autocomplete="username"
                                            />
                                            <InputError class="mt-2" :message="form.errors.email" />
                                        </div>
                                    </div>
                                    <!--Bank-->
                                    <div class="grid grid-cols-7 mb-2">
                                        <div class="col-span-2">
                                            <InputLabel class="mt-1.5" value="Bank" />
                                        </div>
                                        
                                        <div class="col-span-5">
                                            <div class="">
                                                <select class="rounded-lg text-xs border border-gray-300 text-gray-600 w-4/5" v-model="form.bank" id="refBank">
                                                    <option value="" disabled>Choose Bank</option>
                                                    <option v-for="refBank in refBanks" :key="refBank.id" :value="refBank.id">{{ refBank.name }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <!--Account Number-->
                                    <div class="grid grid-cols-7 mb-2">
                                        <div class="col-span-2">
                                            <InputLabel class="mt-1.5" value="Account Number" />
                                        </div>
                                        <div class="col-span-5">
                                            <TextInput
                                                id="account_number"
                                                type="text"
                                                class=" block w-4/5 text-xs"
                                                v-model="form.account_number"
                                                autofocus
                                                autocomplete="username"
                                            />
                                            <InputError class="mt-2" :message="form.errors.account_number" />
                                        </div>
                                    </div>

                                    <div v-for="program in 5">{{ n }}</div>
                                    
                                    <!--Programs-->
                                    <div class="grid grid-cols-7 mb-2">
                                        <div class="col-span-2">
                                            <InputLabel class="mt-1.5" value="Program" />
                                        </div>
                                        
                                        <div class="col-span-5">
                                            <div class="">
                                                <select class="rounded-lg text-xs border border-gray-300 text-gray-600 w-4/5" v-model="form.program_id">
                                                    <option value="" disabled>Choose Program</option>
                                                    <option 
                                                        v-for="program in programs" 
                                                        :key="program.id" 
                                                        :value="program.type_id">
                                                        {{ program.type_id }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <!--Status-->
                                    <div class="grid grid-cols-7 mb-2">
                                        <div class="col-span-2">
                                            <InputLabel class="mt-1.5" value="Status" />
                                        </div>
                                        <div class="col-span-5">
                                            <div class="mt-2 text-xs font-bold text-gray-900">{{ getStatusText(form.status) }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-1">
                                    <!---Type Individu--->
                                    <div v-if="form.program_id.type == 1">
                                        
                                        <div class="grid grid-cols-7 mb-2">
                                            <div class="col-span-3">
                                                <InputLabel class="mt-1.5 ml-6" value="Jenis Program" />
                                            </div>
                                            <div class="col-span-4">
                                                <TextInput
                                                    id="type"
                                                    type="text"
                                                    class=" block w-4/5 text-xs"
                                                    autofocus
                                                    autocomplete="username"
                                                    placeholder="161024581113"
                                                    :value="form.program_id.type"
                                                    disabled
                                                /> 
                                                <InputError class="mt-2" :message="form.errors.type" />
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-7 mb-2">
                                            <div class="col-span-3">
                                                <InputLabel class="mt-1.5 ml-6" value="Kadar Peruntukkan" />
                                            </div>
                                            <div class="col-span-4">
                                                <TextInput
                                                    id="allocation_rate"
                                                    type="text"
                                                    class=" block w-4/5 text-xs"
                                                    autofocus
                                                    autocomplete="username"
                                                    placeholder="161024581113"
                                                    :value="'RM ' +(form.program_id.allocation_rate)"
                                                    disabled
                                                /> 
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-7 mb-2">
                                            <div class="col-span-3">
                                                <InputLabel class="mt-1.5 ml-6" value="Tarikh Pembayaran" />
                                            </div>
                                            <div class="col-span-4">
                                                <TextInput
                                                    id="date_payment"
                                                    type="text"
                                                    class=" block w-4/5 text-xs"
                                                    autofocus
                                                    autocomplete="username"
                                                    :value="(form.program_id.payment_date)"
                                                    disabled
                                                /> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end space-x-2">
                                <button
                                    :class="{ 'opacity-25 px-3 py-3 text-md text-white font-semibold bg-red-500 hover:bg-red-700 rounded-full': form.processing }"
                                    :disabled="form.processing"
                                    class="flex items-center py-2 px-2 text-xs text-white font-mono bg-danger-button hover:bg-danger-button-hover rounded-md"
                                    @click="reject">
                                    <IcCancel class="text-lg mr-0.5" />
                                    Reject
                                </button>

                                <button
                                    :class="{ 'opacity-25 px-3 py-2 text-md text-white font-semibold bg-green-500 hover:bg-green-700 rounded': form.processing }"
                                    :disabled="form.processing"
                                    class="flex items-center py-2 px-2 text-xs text-white font-mono bg-success-button hover:bg-success-button-hover rounded-md"
                                    @click="approve">
                                    <BxCheckDouble class="text-lg mr-0.5" />
                                    Approve
                                </button>
                            </div>

                            <!---Approve Modal-->
                            <Modal :show="showConfirmApproveModal" @close="closeModalApprove">
                                <div class="p-6">
                                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg>
                                    <div class="flex justify-center">
                                        <h2 class="text-lg font-semibold text-slate-800">
                                        Are you sure you want to Approve this Receiver?
                                        </h2>
                                    </div>
                                    <div class="mt-6 flex justify-center space-x-2">
                                        <SuccessButton @click="$event => approveProgram(receiver.id)" class="text-xs font-extralight py-3">Yes, I'm sure</SuccessButton>
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
                                        Are you sure you want to reject this Receiver?
                                        </h2>
                                    </div>
                                    <div class="mt-6 flex justify-center space-x-2">
                                        <DangerButton @click="$event => rejectReceiver(receiver.id)" class="text-xs font-extralight py-3">Yes, I'm sure</DangerButton>
                                        <SecondaryButton @click="closeModalReject" class="text-xs font-extralight py-3">No, Cancel</SecondaryButton>
                                    </div>
                                </div>
                            </Modal>
                            <!---Approve Modal-->
                            <Modal :show="showConfirmUpdateModal" @close="closeModalUpdate">
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
                                        <SuccessButton @click="$event => updateReceiver(receiver.id)" class="text-xs font-extralight py-3">Yes, I'm sure</SuccessButton>
                                        <SecondaryButton @click="closeModalUpdate" class="text-xs font-extralight py-3">No, Cancel</SecondaryButton>
                                    </div>
                                </div>
                            </Modal>
                        </form>
                    </Content>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
