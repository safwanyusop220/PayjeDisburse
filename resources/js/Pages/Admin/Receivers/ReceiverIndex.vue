<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, watch, defineProps } from "vue";
import { router } from '@inertiajs/vue3'

import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import TableDataCell from "@/Components/TableDataCell.vue";
import TableHeaderCellLeft from "@/Components/TableHeaderCellLeft.vue";
import TableHeaderCellRight from "@/Components/TableHeaderCellRight.vue";
import TablePagination from "@/Components/TablePagination.vue";

import Content from "@/Components/Content.vue";

import Modal from "@/Components/Modal.vue";
import ModalIndex from "@/Components/ModalIndex.vue";

import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import AddButton from "@/Components/AddButton.vue";
import ImportButton from "@/Components/ImportButton.vue";
import DownloadTemplateButton from "@/Components/DownloadTemplateButton.vue";

import SuccessButton from "@/Components/SuccessButton.vue";

import SpanSubmittedStatus from "@/Components/SpanSubmittedStatus.vue";
import SpanRecommendedStatus from "@/Components/SpanRecommendedStatus.vue";
import SpanApprovedStatus from "@/Components/SpanApprovedStatus.vue";
import SpanRejectedStatus from "@/Components/SpanRejectedStatus.vue";
import { CaDocumentImport } from "@kalimahapps/vue-icons";

// BreadCrumb
import BreadcrumbInitial from "@/Components/BreadcrumbInitial.vue";
import BreadcrumbActive from "@/Components/BreadcrumbActive.vue";
import BreadcrumbCurrent from "@/Components/BreadcrumbCurrent.vue";
import axios from 'axios';

import { BsSearch } from "@kalimahapps/vue-icons";
import { ReDeleteBinLine } from "@kalimahapps/vue-icons";
import { UnFileUploadAlt } from "@kalimahapps/vue-icons";

const props = defineProps({
    receivers: {
        type: Object,
        required: true,
    },
    filters: Object,
    programs: Object
});

const form = useForm({
    email: '',
    program_id: ''
})


const selectedFile = ref(null);
const selectedFileName = ref(null);
const selectedFileSize = ref(null);

const cancelUpload = () => {
    showImportModal.value = false;
}

const onChange = (event) => {
  const inputElement = event.target;
  const files = inputElement.files;

  if (files.length > 0) {
    const file = files[0];
    selectedFileName.value = file.name;
    selectedFileSize.value = formatFileSize(file.size); 
    selectedFile.value = file;
  } else {
    selectedFileName.value = null;
    selectedFileSize.value = null;
    selectedFile.value = null;
  }
};

const clearSelectedFile = () => {
  selectedFile.value = null;
  selectedFileName.value = null;
  selectedFileSize.value = null;
};

const formatFileSize = (sizeInBytes) => {
  const sizeInKb = sizeInBytes / 1024;
  return sizeInKb.toFixed(2) + ' KB';
};

const showSuccessMessage = () => {
    showImportModal.value = false;

  Swal.fire({
    width: 400,
    height: 100,
    html: '<span class="text-sm">All Receiver Has Been Successfully Added!</span>',
    icon: 'success',
    confirmButtonText: 'Okay',
    customClass: {
      content: 'text-lg',
      confirmButton: 'px-4 py-2 text-white text-xs rounded',
    },
  }).then(() => {
    window.location.reload();
  });
};

const saveExcelUser = async () => {
  const data = new FormData();
  const selectedProgramId = document.getElementById('program_id').value;

  data.append('importFile', selectedFile.value);
  data.append('program_id', selectedProgramId);

  try {
    await axios.post(route('receivers.import'), data);
    showSuccessMessage();
  } catch (error) {
    console.error('Error:', error);
  }
};

const showImportModal = ref(false);

const importModal = () => {
    showImportModal.value = true;
}

const closeModalImport = () => {
    showImportModal.value = false;
};

const showConfirmDeleteModal = ref(false)
const selectedId = ref(null);

const confirmDelete = (id) => {
    selectedId.value = id;
    console.log(id)
    showConfirmDeleteModal.value = true;
}

const closeModal = () => {
    showConfirmDeleteModal.value = false;
}

const deleteReceiver = () => {
    try{
        form.delete(route('receivers.destroy', selectedId.value), {
            onSuccess: (page) => {
            Toast.fire({
                icon: 'success',
                title: 'Receiver has successfully Deleted'
            })
            },
        })
    }catch (err){
        console.log(err)
    }
    showConfirmDeleteModal.value = false;
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

const search = ref(props.filters.search);
const perPage = ref(5);

watch(search, (value) =>{
    router.get("/receivers", {search: value, perPage: perPage.value},
    {
        preserveState: true,
        replace: true
    });
})

function getAllocations() {
    router.get(
        "/receivers",
        { perPage: perPage.value, search: search.value },
        {
            preserveState: true,
            replace: true,
        }
    );
}
</script>

<template>
    <Head title="Receivers" />

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
                                <BreadcrumbCurrent>Receiver</BreadcrumbCurrent>
                            </li>
                        </ol>
                    </nav>
                </div>

                <Content>
                    <div class="flex justify-between">
                        <span class="font-medium text-primary-text text-lg mt-2">Receivers</span>

                        <div class="flex space-x-3">
                            <DownloadTemplateButton type="button">Download Template</DownloadTemplateButton>
                            <ImportButton type="button" @click="importModal">Import</ImportButton>
                            <AddButton :href="route('receivers.create')">Create</AddButton>
                        </div>
                    </div>

                    <div class="border-b-2 border-gray-200 mb-2 mt-2.5"></div>

                    <div class="mb-2 flex justify-between">
                        <BsSearch class="absolute ml-2 text-sm text-gray-500 mt-2" />
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Search"
                            class="pl-7 w-1/3 rounded-md bg-primary-50 text-xs border-none focus:ring-primary-100"
                        />

                        <select
                            v-model="perPage"
                            @change="getAllocations"
                            class="rounded-md bg-primary-50 text-xs border-none focus:ring-primary-100">
                            <option value="1">10</option>
                            <option value="2">20</option>
                            <option value="5">50</option>
                            <option value="10">100</option>
                        </select>
                    </div>

                    <div class="mt-2">
                        <Table>
                            <template #header>
                                <TableRow>
                                    <TableHeaderCellLeft class="">No</TableHeaderCellLeft>
                                    <TableHeaderCell class="">Receiver</TableHeaderCell>
                                    <TableHeaderCell class="">No IC</TableHeaderCell>
                                    <TableHeaderCell class="">Address</TableHeaderCell>
                                    <TableHeaderCell class="">Program</TableHeaderCell>
                                    <TableHeaderCell class="text-center">Status</TableHeaderCell>
                                    <TableHeaderCell class="">Bank</TableHeaderCell>
                                    <TableHeaderCellRight class="">Action</TableHeaderCellRight>
                                </TableRow>
                            </template>
                            <template #default>
                                <TableRow v-for="(receiver, index) in receivers.data" :key="receiver.id" class="border-b">
                                    <TableDataCell class="">{{ index + 1 }}</TableDataCell>
                                    <TableDataCell class="">
                                        <div class="col">
                                            <div class="row">
                                                <div>
                                                    {{ receiver.name }}
                                                </div>
                                                <div>
                                                    {{ receiver.email }}
                                                </div>
                                                <div>
                                                    {{ receiver.phone_number }}
                                                </div>
                                            </div>
                                        </div>
                                    </TableDataCell>
                                    <TableDataCell class="">{{ receiver.identification_number }}</TableDataCell>
                                    <TableDataCell class="">{{ receiver.address }}</TableDataCell>
                                    <TableDataCell class="">{{ receiver.program.name }}</TableDataCell>
                                    <TableDataCell class="">
                                        <template v-if="receiver.status == 1">
                                            <SpanSubmittedStatus :value="receiver.status_id.name"/>
                                        </template>
                                        <template v-if="receiver.status == 2">
                                            <SpanRecommendedStatus :value="receiver.status_id.name"/>
                                        </template>
                                        <template v-if="receiver.status == 3">
                                            <SpanApprovedStatus :value="receiver.status_id.name"/>
                                        </template>
                                        <template v-if="receiver.status == 4">
                                            <SpanRejectedStatus :value="receiver.status_id.name"/>
                                        </template>
                                    </TableDataCell>
                                    <TableDataCell class="">
                                        <div class="col">
                                            <div class="row">
                                                <div>
                                                    {{ receiver.bank.name }}
                                                </div>
                                                <div>
                                                    {{ receiver.account_number }}
                                                </div>
                                            </div>
                                        </div>
                                    </TableDataCell>    

                                    <TableDataCell class="">
                                        <div class="row">
                                            <div class="grid grid-cols-2 justify-items-center">
                                                <Link :href="route('receivers.edit', receiver.id)" class="text-blue-400 hover:text-blue-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                    </svg>
                                                </Link>
                                                <button @click="(e)=> confirmDelete(receiver.id)" class="text-red-400 hover:text-red-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>

                                        <!--Delete Receiver-->
                                        <ModalIndex :show="showConfirmDeleteModal" @close="closeModal">
                                            <div class="p-6">
                                                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                </svg>
                                                <div class="flex justify-center">
                                                    <h2 class="text-lg font-semibold text-slate-800">
                                                    Are you sure you want to delete this Receiver?
                                                    </h2>
                                                </div>
                                                <div class="mt-6 flex justify-center space-x-2">
                                                    <DangerButton @click="deleteReceiver" class="text-xs font-extralight">Yes, I'm sure</DangerButton>
                                                    <SecondaryButton @click="closeModal" class="text-xs font-extralight">No, Cancel</SecondaryButton>
                                                </div>
                                            </div>
                                        </ModalIndex>
                                        
                                        <!---Import Modal-->
                                        <ModalIndex :show="showImportModal" @close="closeModalImport">
                                            <div class="px-5 py-5">
                                                <form id="mainFormUsers" @submit.prevent="saveExcelUser">
                                                    <div class="flex items-center justify-center w-full">
                                                        <label v-if="!selectedFile" for="dropzone-file" class="flex flex-col items-center justify-center w-full h-[100px] border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-100 hover:bg-gray-200">
                                                            <div class="flex flex-col items-center justify-center mt-4">
                                                                <UnFileUploadAlt class="text-5xl text-gray-500"/>
                                                                <p class="mb-2 text-sm text-gray-500 "><span class="font-semibold">Click to upload</span></p>
                                                                <p class="text-xs text-gray-500 mb-5">Only Excel files (.xlsx) allowed</p>
                                                            </div>
                                                            <input id="dropzone-file" type="file" accept=".xlsx"  @change="onChange" name="select_users_file"  class="hidden" />
                                                        </label>
                                                    </div> 
                                                    <div v-if="selectedFile" class="h-[100px] ">
                                                        <div class="flex hover:bg-gray-100  justify-between text-xs font-semibold text-gray-700
                                                        border border-gray-300 w-full px-2 py-2 rounded-lg">
                                                            <div class="flex items-center gap-x-3">
                                                                <span class="w-8 h-8 flex justify-center items-center border border-gray-200 text-gray-500 rounded-lg dark:border-neutral-700">
                                                                    <svg class="flex-shrink-0 w-5 h-5" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M15.0243 1.43996H7.08805C6.82501 1.43996 6.57277 1.54445 6.38677 1.73043C6.20077 1.91642 6.09631 2.16868 6.09631 2.43171V6.64796L15.0243 11.856L19.4883 13.7398L23.9523 11.856V6.64796L15.0243 1.43996Z" fill="#21A366"/>
                                                                    <path d="M6.09631 6.64796H15.0243V11.856H6.09631V6.64796Z" fill="#107C41"/>
                                                                    <path d="M22.9605 1.43996H15.0243V6.64796H23.9523V2.43171C23.9523 2.16868 23.8478 1.91642 23.6618 1.73043C23.4758 1.54445 23.2235 1.43996 22.9605 1.43996Z" fill="#33C481"/>
                                                                    <path d="M15.0243 11.856H6.09631V21.2802C6.09631 21.5433 6.20077 21.7955 6.38677 21.9815C6.57277 22.1675 6.82501 22.272 7.08805 22.272H22.9606C23.2236 22.272 23.4759 22.1675 23.6618 21.9815C23.8478 21.7955 23.9523 21.5433 23.9523 21.2802V17.064L15.0243 11.856Z" fill="#185C37"/>
                                                                    <path d="M15.0243 11.856H23.9523V17.064H15.0243V11.856Z" fill="#107C41"/>
                                                                    <path opacity="0.1" d="M12.5446 5.15996H6.09631V19.296H12.5446C12.8073 19.2952 13.0591 19.1904 13.245 19.0046C13.4308 18.8188 13.5355 18.567 13.5363 18.3042V6.1517C13.5355 5.88892 13.4308 5.63712 13.245 5.4513C13.0591 5.26548 12.8073 5.16074 12.5446 5.15996Z" fill="black"/>
                                                                    <path opacity="0.2" d="M11.8006 5.90396H6.09631V20.04H11.8006C12.0633 20.0392 12.3151 19.9344 12.501 19.7486C12.6868 19.5628 12.7915 19.311 12.7923 19.0482V6.8957C12.7915 6.6329 12.6868 6.38114 12.501 6.19532C12.3151 6.0095 12.0633 5.90475 11.8006 5.90396Z" fill="black"/>
                                                                    <path opacity="0.2" d="M11.8006 5.90396H6.09631V18.552H11.8006C12.0633 18.5512 12.3151 18.4464 12.501 18.2606C12.6868 18.0748 12.7915 17.823 12.7923 17.5602V6.8957C12.7915 6.6329 12.6868 6.38114 12.501 6.19532C12.3151 6.0095 12.0633 5.90475 11.8006 5.90396Z" fill="black"/>
                                                                    <path opacity="0.2" d="M11.0566 5.90396H6.09631V18.552H11.0566C11.3193 18.5512 11.5711 18.4464 11.757 18.2606C11.9428 18.0748 12.0475 17.823 12.0483 17.5602V6.8957C12.0475 6.6329 11.9428 6.38114 11.757 6.19532C11.5711 6.0095 11.3193 5.90475 11.0566 5.90396Z" fill="black"/>
                                                                    <path d="M1.13604 5.90396H11.0566C11.3195 5.90396 11.5718 6.00842 11.7578 6.19442C11.9438 6.38042 12.0483 6.63266 12.0483 6.8957V16.8162C12.0483 17.0793 11.9438 17.3315 11.7578 17.5175C11.5718 17.7035 11.3195 17.808 11.0566 17.808H1.13604C0.873012 17.808 0.620754 17.7035 0.434765 17.5175C0.248775 17.3315 0.144287 17.0793 0.144287 16.8162V6.8957C0.144287 6.63266 0.248775 6.38042 0.434765 6.19442C0.620754 6.00842 0.873012 5.90396 1.13604 5.90396Z" fill="#107C41"/>
                                                                    <path d="M2.77283 15.576L5.18041 11.8455L2.9752 8.13596H4.74964L5.95343 10.5071C6.06401 10.7318 6.14015 10.8994 6.18185 11.01H6.19745C6.27683 10.8305 6.35987 10.6559 6.44669 10.4863L7.73309 8.13596H9.36167L7.09991 11.8247L9.41897 15.576H7.68545L6.29489 12.972C6.22943 12.861 6.17387 12.7445 6.12899 12.6238H6.10817C6.06761 12.7419 6.01367 12.855 5.94748 12.9608L4.51676 15.576H2.77283Z" fill="white"/>
                                                                    </svg>
                                                                </span>
                                                                
                                                                <div>
                                                                    <p class="font-semibold text-black">{{ selectedFileName }}</p>
                                                                    <p class="text-xs text-gray-500 dark:text-gray-500">{{ selectedFileSize  }}</p>
                                                                </div>
                                                            </div>
                                                            <ReDeleteBinLine @click="clearSelectedFile" class="text-gray-500 hover:text-gray-700 my-auto cursor-pointer text-xl"/>
                                                        </div>
                                                        <select class="w-full mt-3 text-black border border-gray-300 bg-white text-xs focus:border-gray-300 focus:ring-0 rounded-lg cursor-pointer hover:bg-gray-100" v-model="form.program_id" id="program_id">
                                                            <option value="" disabled>Choose Program</option>
                                                            <option v-for="program in programs" :key="program.id" :value="program.id">{{ program.name }}</option>
                                                        </select>
                                                    </div>
                                                    <div class="flex justify-end mt-4 space-x-1">
                                                        <button @click="cancelUpload" class="bg-white border border-gray-300 hover:bg-gray-100 hover:border-gray-300  text-gray-500 text-xs px-4 py-2 rounded-md">Cancel</button>
                                                        <button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white text-xs px-4 py-2 rounded-md">Upload</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </ModalIndex>
                                    </TableDataCell>
                                </TableRow>
                            </template>
                        </Table>
                        <div class="text-center mx-auto m-2 p-2">
                            <TablePagination :links="receivers.links" />
                        </div>
                    </div>
                </Content>
            </div>
        </div>
    </AdminLayout>
</template>
