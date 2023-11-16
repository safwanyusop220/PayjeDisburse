<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from "vue";

import { onMounted, watch } from 'vue';

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

import BreadcrumbInitial from "@/Components/BreadcrumbInitial.vue";
import BreadcrumbActive from "@/Components/BreadcrumbActive.vue";
import BreadcrumbCurrent from "@/Components/BreadcrumbCurrent.vue";

// Icons
import { IcCancel } from "@kalimahapps/vue-icons";
import { QuSendCancelled } from "@kalimahapps/vue-icons";
import { BxCheckDouble } from "@kalimahapps/vue-icons";
import { ClCircleWarning } from "@kalimahapps/vue-icons";

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
    name: "",
    identification_number: "",
    address: "",
    phone_number: "",
    email: "",
    bank: "",
    account_number: "",
    status: "",
    program_id: "",
    type: props.program?.type,
    allocation_rate: props.program?.allocation_rate,
})

const showConfirmCreateModal = ref(false)

const create = () => {
    showConfirmCreateModal.value = true;
};

const closeModalCreate = () => {
    showConfirmCreateModal.value = false;
}

const createReceiver = (id) => {
    try{
        form.post(route('receivers.store'), {
            onSuccess: (page) => {
            Swal.fire({
                width: 400,
                height: 100,
                html: '<span class="text-sm">Receiver Has Successfully Added!</span>',
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

</script>

<template>
    <Head title="Create new Receiver" />

    <AdminLayout>
        <div class="mt-14">
            <div class="w-full mx-auto">
                <div class="overflow-hidden">
                    <div class="flex justify-between pt-4">
                        <nav class="text-sm font-semibold" aria-label="Breadcrumb">
                            <ol class="list-none inline-flex">
                                <li class="flex items-center">
                                    <BreadcrumbInitial :href="route('receivers.index')"></BreadcrumbInitial>
                                </li>
                                <li class="flex items-center">
                                    <BreadcrumbActive :href="route('receivers.index')">Receiver</BreadcrumbActive>
                                </li>
                                <li class="flex items-center">
                                    <BreadcrumbCurrent>Create</BreadcrumbCurrent>
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <Content>
                        <form @submit.prevent="submit">
                            <div class="grid grid-cols-2">
                                <div class="col-span-1">
                                    <!--Receiver Name-->
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
                                                <select class="rounded-lg text-xs border border-gray-300 text-gray-500 w-4/5" v-model="form.bank" id="refBank">
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
                                            />
                                            <InputError class="mt-2" :message="form.errors.account_number" />
                                        </div>
                                    </div>
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
                                                        :value="program.id">
                                                        {{ program.name }}
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
                                            <div class="">
                                                <select class="rounded-lg text-xs border border-gray-300 text-gray-600 w-4/5" v-model="form.status">
                                                    <option value="" disabled selected>Choose Status</option>
                                                    <option value="1" >Aktif</option>
                                                    <option value="2" >Tidak Aktif</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-end space-x-2 mt-4 mb-2">
                                <Link :href="route('receivers.index')" class="flex items-center py-2 px-2 text-xs text-white font-mono bg-danger-button hover:bg-danger-button-hover rounded-md">
                                    <IcCancel class="text-lg mr-0.5" />
                                    Cancel
                                </Link>
                                <button @click="create"  :disabled="form.processing" class="flex items-center py-2 px-2 text-xs text-white font-mono bg-success-button hover:bg-success-button-hover rounded-md">
                                    <BxCheckDouble class="text-lg mr-0.5" />
                                    Submit
                                </button>
                            </div>

                            <!---Addd Modal-->
                            <Modal :show="showConfirmCreateModal" @close="closeModalCreate">
                                <div class="py-4">
                                    <div class="justify-center flex">
                                        <ClCircleWarning class="text-5xl text-gray-400"/>
                                    </div>
                                    <div class="flex justify-center">
                                        <p class="text-xs font-normal text-slate-800 mt-3">
                                            Are you sure you want add this Receiver?
                                        </p>
                                    </div>
                                    <div class="mt-6 flex justify-center space-x-2">
                                        <SuccessButton @click="$event => createReceiver()" class="text-xs font-extralight">Yes, I'm sure</SuccessButton>
                                        <SecondaryButton @click="closeModalCreate" class="text-xs font-extralight">No, Cancel</SecondaryButton>
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
