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
import { BsSendCheck } from "@kalimahapps/vue-icons";
import { MdOutlinedUpdate } from "@kalimahapps/vue-icons";

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
});

watch(
    () => props.program,
    () => {
        form.status = props.program?.status,
        form.type = props.program?.type,
        form.allocation = props.program?.allocation

    }
);


const programTypes = ref([
      { id: 1, name: 'Individu' },
      { id: 2, name: 'Kumpulan' },
      { id: 3, name: 'Pecahan' },
      { id: 4, name: 'Kelompok' },
    ]);
    
const frequencies = ref([
      { id: 5, name: 'Bulanan' },
      { id: 6, name: 'Tahunan' },
      { id: 7, name: 'Sekali' },
    ]);

const frequencyTexts = {
  5: "1 haribulan setiap bulan",
  6: "1 haribulan januari setiap tahun",
};

// Modal Approve
const showConfirmModal = ref(false)

// Method for "Luluskan" button
const update = () => {
    showConfirmModal.value = true;
};

const closeModal = () => {
    showConfirmModal.value = false;
}

const updateProgram = (id) => {
    try{
        form.put(route('programs.update', props.program?.id), {
            onSuccess: (page) => {
            Toast.fire({
                icon: 'success',
                title: 'Program has successfully updated'
            })
            showConfirmModal.value = false;
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
  width: 400,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer);
    toast.addEventListener('mouseleave', Swal.resumeTimer);
  },
});

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
    <Head title="Update Program" />

    <AdminLayout>
        <div class="mt-14">
            <div class="w-full mx-auto">
                <div class="flex justify-between pt-4">
                    <nav class="text-sm font-semibold" aria-label="Breadcrumb">
                        <ol class="list-none p-0 inline-flex">
                            <li class="flex items-center">
                                <BreadcrumbInitial :href="route('programs.index')">Home</BreadcrumbInitial>
                            </li>
                            <li class="flex items-center">
                                <BreadcrumbActive :href="route('programs.index')">Program</BreadcrumbActive>
                            </li>
                            <li class="flex items-center">
                                <BreadcrumbCurrent>Update Program</BreadcrumbCurrent>
                            </li>
                        </ol>
                    </nav>
                </div>
                <Content>
                    <form @submit.prevent="submit">
                        <!--Program Name-->
                        <div class="grid grid-cols-9 mt-4">
                            <div class="col-span-2">
                                <InputLabel class="mt-1.5" value="Program Name" />
                            </div>
                            <div class="col-span-5">
                                <TextInput
                                    id="name"
                                    type="text"
                                    class=" block w-4/5 text-xs"
                                    v-model="form.name"
                                    autofocus
                                    autocomplete="username"
                                    placeholder="Program A"
                                />

                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>
                        </div>
                        <!--Program Type-->
                        <div class="grid grid-cols-9">
                            <div class="col-span-2">
                                <InputLabel class="mt-5 font-bold" value="Program Type" />
                            </div>
                            <div class="col-span-5">
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
                                                @click="variable(programType.id)"
                                            />
                                            <label :for="programType.id" class="text-sm">{{ programType.name }}</label>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                        
                        <!--Textfield based on program type Individu-->
                        <div v-if="form.type == 1">
                            <div class="grid grid-cols-9">
                                <div class="col-span-2">
                                    <InputLabel class="mt-4" value="Sumber Peruntukkan" />
                                </div>
                                
                                <div class="col-span-5">
                                    <div class="my-3">
                                        <select  class="rounded-lg text-xs border border-none w-1/2" v-model="form.allocation" id="allocation">
                                            <option value="" disabled>Pilih Peruntukan</option>
                                            <option v-for="allocation in allocations" :key="allocation.id" :value="allocation.id">{{ allocation.allocation_source }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-9 mb-4">
                                <div class="col-span-2">
                                    <InputLabel class="mt-2" value="Kadar Peruntukkan" />
                                </div>
                                
                                <div class="col-span-5">
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

                            <div class="grid grid-cols-9">
                                <div class="col-span-2">
                                    <InputLabel class="mt-2" value="Tarikh Pembayaran" />
                                </div>
                                
                                <div class="col-span-5">
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
                        </div>

                        <!--Textfield based on program type Kumpulan-->
                        <div v-if="form.type == 2">
                            <div class="grid grid-cols-9">
                                <div class="col-span-2">
                                    <InputLabel class="mt-4" value="Sumber Peruntukkan" />
                                </div>
                                
                                <div class="col-span-5">
                                    <div class="my-3">
                                        <select class="rounded-lg text-xs border border-none w-1/2" v-model="form.allocation" id="allocation">
                                            <option value="" disabled>Pilih Peruntukan</option>
                                            <option v-for="allocation in allocations" :key="allocation.id" :value="allocation.id">{{ allocation.allocation_source }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-9 mb-4">
                                <div class="col-span-2">
                                    <InputLabel class="mt-2" value="Kadar Peruntukkan" />
                                </div>
                                
                                <div class="col-span-5">
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

                            <div class="grid grid-cols-9 mb-4">
                                <div class="col-span-2">
                                    <InputLabel class="mt-2" value="Tarikh Pembayaran" />
                                </div>
                                
                                <div class="col-span-5">
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

                            <div class="grid grid-cols-9">
                                <div class="col-span-2">
                                    <InputLabel class="mt-2" value="Sah Sehingga" />
                                </div>
                                
                                <div class="col-span-5">
                                    <TextInput
                                        id="valid_until"
                                        type="date"
                                        class="mt-1 block w-1/2 text-xs"
                                        v-model="form.valid_until"
                                        required
                                    />

                                    <InputError class="mt-2" :message="form.errors.valid_until" />
                                </div>
                            </div>

                            <div class="grid grid-cols-9">
                                <div class="col-span-2">
                                    <InputLabel class="mt-5 font-bold" value="Kekerapan" />
                                </div>
                                <div class="col-span-5">
                                    <div class="grid grid-cols-7">
                                        <div class="col-span-1">
                                            <div class="mt-5 mb-4">
                                                <template v-for="frequency in frequencies" :key="frequency.id">
                                                    <div class="flex items-center" >
                                                        <input
                                                            :key="frequency.id"
                                                            type="radio"
                                                            :id="frequency.id"
                                                            v-model="form.frequency"
                                                            :value="frequency.id"
                                                            class="mr-2"
                                                            @click="variable(frequency.id)"
                                                        />
                                                        <label :for="frequency.id" class="text-sm font-bold">{{ frequency.name }}</label>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                        <!--Small note for Kekerapan-->
                                        <div class="col-span-6">
                                            <div v-if="form.frequency == 5">
                                                <p class="text-xs font-bold ml-4 mt-6">({{ frequencyTexts[form.frequency] }})</p>
                                            </div>
                                            <div v-if="form.frequency == 6">
                                                <p class="text-xs font-bold ml-4 mt-11">({{ frequencyTexts[form.frequency] }})</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>

                            <!--Tempoh Bayaran-->
                            <div v-if="form.frequency == 5">
                                <div class="grid grid-cols-9">
                                    <div class="col-span-2">
                                        <InputLabel class="mt-2" value="Tempoh Bayaran" />
                                    </div>
                                    <div class="col-span-2">
                                        <p class="text-sm font-bold ml-1 mt-1.5">12 bulan</p>
                                    </div>
                                </div>
                            </div>

                            <div v-if="form.frequency == 6">
                                <div class="grid grid-cols-9">
                                    <div class="col-span-2">
                                        <InputLabel class="mt-2" value="Tempoh Bayaran" />
                                    </div>
                                    <div class="col-span-2">
                                        <p class="text-sm font-bold ml-1 mt-1.5">1 tahun</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--Textfield based on program type Pecahan-->
                        <div v-if="form.type == 3">
                            <div class="grid grid-cols-9">
                                <div class="col-span-2">
                                    <InputLabel class="mt-4" value="Sumber Peruntukkan" />
                                </div>
                                
                                <div class="col-span-5">
                                    <div class="my-3">
                                        <select class="rounded-lg text-xs border border-none w-1/2" v-model="form.allocation" id="allocation">
                                            <option value="" disabled>Pilih Peruntukan</option>
                                            <option v-for="allocation in allocations" :key="allocation.id" :value="allocation.id">{{ allocation.allocation_source }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-9 mb-4">
                                <div class="col-span-2">
                                    <InputLabel class="mt-2" value="Kadar Peruntukkan" />
                                </div>
                                
                                <div class="col-span-5">
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

                            <div class="grid grid-cols-9">
                                <div class="col-span-2">
                                    <InputLabel class="mt-2" value="Pecahan Pembayaran" />
                                </div>
                            </div>
                        </div>

                        <!--Textfield based on program type Kelompok-->
                        <div v-if="form.type == 4">
                            <div class="grid grid-cols-9">
                                <div class="col-span-2">
                                    <InputLabel class="mt-4" value="Sumber Peruntukkan" />
                                </div>
                                
                                <div class="col-span-5">
                                    <div class="my-3">
                                        <select class="rounded-lg text-xs border border-none w-1/2" v-model="form.allocation" id="allocation">
                                            <option value="" disabled>Pilih Peruntukan</option>
                                            <option v-for="allocation in allocations" :key="allocation.id" :value="allocation.id">{{ allocation.allocation_source }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-9 mb-4">
                                <div class="col-span-2">
                                    <InputLabel class="mt-2" value="Kadar Peruntukkan" />
                                </div>
                                
                                <div class="col-span-5">
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

                            <div class="grid grid-cols-9">
                                <div class="col-span-2">
                                    <InputLabel class="mt-2" value="Kelompok Pembayaran" />
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-9">
                            <div class="col-span-2">
                                <InputLabel class="mt-4" value="Status Kelulusan" />
                            </div>
                            <div class="col-span-5">
                                <div class="mt-5 text-xs font-bold text-gray-900">{{ getStatusText(form.status) }}</div>

                                <InputError class="mt-2" :message="form.errors.status" />
                            </div>
                        </div>

                        <!-- Button submit & back-->
                        <div class="flex items-center justify-end space-x-2 mt-4 mb-2">
                            <Link :href="route('programs.index')" class="flex items-center py-2 px-2 text-xs text-white font-mono bg-danger-button hover:bg-danger-button-hover rounded-md">
                                <IcCancel class="text-lg mr-0.5" />
                                Cancel
                            </Link>
                            <button @click="update" :class="{ 'opacity-25 mr-2.5 px-3 py-2 text-md text-white font-semibold bg-primary-500 hover:bg-indigo-700 rounded': form.processing }" :disabled="form.processing" class="flex items-center py-2 px-2 text-xs text-white font-mono bg-success-button hover:bg-success-button-hover rounded-md">
                                <BxCheckDouble class="text-lg mr-0.5" />
                                Update
                            </button>
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
                                    <SuccessButton @click="$event => updateProgram(program.id)" class="text-xs font-extralight py-3">Yes, I'm sure</SuccessButton>
                                    <SecondaryButton @click="closeModal" class="text-xs font-extralight py-3">No, Cancel</SecondaryButton>
                                </div>
                            </div>
                        </Modal>
                    </form>
                </Content>
            </div>
        </div>
    </AdminLayout>
</template>
