<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from "vue";

import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import TableDataCell from "@/Components/TableDataCell.vue";
import TableHeaderCellLeft from "@/Components/TableHeaderCellLeft.vue";
import TableHeaderCellRightTextStart from "@/Components/TableHeaderCellRightTextStart.vue";
import TablePagination from "@/Components/TablePagination.vue";

import Content from "@/Components/Content.vue";

import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';


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

import { SuCreate } from "@kalimahapps/vue-icons";
import { McFileExportLine } from "@kalimahapps/vue-icons";
import { CaDocumentImport } from "@kalimahapps/vue-icons";

import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'

defineProps([
    'requestPayments',
    'requestProcessings',
    'requestProceeds',
    'requiredProcessPaymentsCount',
])

const form = useForm({
    select_users_file : ""
})

const activeTab = ref('request');

const activateTab = (tabName) => {
  activeTab.value = tabName;
};

</script>

<template>
    <Head title="Payments" />

    <AdminLayout>
        <div class="mt-14">
            <div class="w-full mx-auto">
                <div class="flex justify-between pt-4">
                    <nav class="text-sm font-semibold" aria-label="Breadcrumb">
                        <ol class="list-none p-0 inline-flex">
                            <li class="flex items-center">
                                <BreadcrumbInitial :href="route('payments.index')"></BreadcrumbInitial>
                            </li>
                            <li class="flex items-center">
                                <BreadcrumbActive :href="route('payments.index')">Payment</BreadcrumbActive>
                            </li>
                            <li class="flex items-center">
                                <BreadcrumbCurrent>
                                    <div>
                                        {{ activeTab === 'request' ? 'Request' : '' }}
                                        {{ activeTab === 'processing' ? 'Processing' : '' }}
                                        {{ activeTab === 'proceed' ? 'Proceed' : '' }}
                                    </div>
                                </BreadcrumbCurrent>
                            </li>
                        </ol>
                    </nav>
                </div>
                
                <Content>
                    <div>
                        <div class="mb-4 mt-1 flex justify-between">
                            <ul class="flex px-1 space-x-1 rounded-xl bg-primary-500 py-1" id="myTab" data-tabs-toggle="#myTabContent" role="tablist">
                                <li role="presentation">
                                    <button class="w-[110px] rounded-lg px-2 py-1 leading-7 text-xxs font-medium"
                                            :class="{ 'bg-white': activeTab === 'request', '': activeTab === 'request','text-primary-700': activeTab === 'request','hover:text-primary-700': activeTab === 'request','text-white': activeTab !== 'request', 'hover:bg-primary-400': activeTab !== 'request','hover:text-white': activeTab !== 'request', }"
                                            id="request-tab" data-tabs-target="#request" type="button" role="tab" aria-controls="request" aria-selected="false"
                                            @click="activateTab('request')">
                                        Request
                                    </button>
                                </li>

                                <li role="presentation">
                                    <button class="w-[110px] rounded-lg px-2 py-1 leading-7 text-xxs font-medium"
                                            :class="{ 'bg-white': activeTab === 'processing','text-primary-700': activeTab === 'processing','hover:text-primary-700': activeTab === 'processing','text-white': activeTab !== 'processing', 'hover:bg-primary-400': activeTab !== 'processing','hover:text-white': activeTab !== 'processing', }"
                                            id="processing-tab" data-tabs-target="#processing" type="button" role="tab" aria-controls="processing" aria-selected="false"
                                            @click="activateTab('processing')">
                                        Processing
                                    </button>
                                </li>

                                <li role="presentation">
                                    <button class="w-[110px] rounded-lg px-2 py-1 leading-7 text-xxs font-medium"
                                            :class="{ 'bg-white': activeTab === 'proceed','text-primary-700': activeTab === 'proceed','hover:text-primary-700': activeTab === 'proceed','text-white': activeTab !== 'proceed', 'hover:bg-primary-400': activeTab !== 'proceed','hover:text-white': activeTab !== 'proceed', }"
                                            id="proceed-tab" data-tabs-target="#proceed" type="button" role="tab" aria-controls="proceed" aria-selected="false"
                                            @click="activateTab('proceed')">
                                        Proceed
                                    </button>
                                </li>

                            </ul>
                            <div>
                                <a :href="route('payments.export')" class="button ml-4 flex items-center mt-2 px-3 py-2 text-xs text-white font-mono bg-primary-500 hover:bg-primary-600 rounded-md">
                                    <McFileExportLine class="text-lg mr-0.5" />
                                    Exports
                                </a>
                            </div>
                        </div>
                        
                        <div id="myTabContent">
                            <!--Request-->
                            <div class="hidden rounded-lg bg-gray-50" id="request" role="tabpanel" aria-labelledby="request-tab">
                                <div class="mt-2">
                                    <Table>
                                        <template #header>
                                            <TableRow>
                                                <TableHeaderCellLeft>No</TableHeaderCellLeft>
                                                <TableHeaderCell>Payment Date</TableHeaderCell>
                                                <TableHeaderCell>Program/Receiver Name</TableHeaderCell>
                                                <TableHeaderCellRightTextStart>Total Receiver</TableHeaderCellRightTextStart>
                                            </TableRow>
                                        </template>
                                        <template #default>
                                            <TableRow v-for="payment in requestPayments.data" :key="payment.id" class="border-b">
                                                <TableDataCell>{{ payment.id }}</TableDataCell>
                                                <TableDataCell> {{ payment.payment_date }}</TableDataCell>
                                                <TableDataCell v-if="payment.receiver_id === null">
                                                    {{ payment.program.name }}
                                                </TableDataCell>
                                                <TableDataCell v-else>
                                                    {{ payment.receiver.name }}
                                                </TableDataCell>
                                                <TableDataCell v-if="payment.receiver_id === null">
                                                    <Link
                                                        v-if="payment.total_receiver > 0"
                                                        :href="route('payments.programReceiverList', payment.program.id)"
                                                        class="underline text-blue-600"
                                                        >
                                                        {{ payment.total_receiver }}
                                                    </Link>
                                                    <span v-else>
                                                        {{ payment.total_receiver }}
                                                    </span>
                                                </TableDataCell>
                                                <TableDataCell v-else>
                                                    1
                                                </TableDataCell>
                                            </TableRow>
                                        </template>
                                    </Table>
                                    <div class="text-center mx-auto m-2 p-2">
                                        <TablePagination :links="requestPayments.links" />
                                    </div>
                                </div>
                            </div>
                            <!--Processing-->
                            <div class="hidden rounded-lg bg-gray-50" id="processing" role="tabpanel" aria-labelledby="processing-tab">
                                <div class="mt-2">
                                    <Table>
                                        <template #header>
                                            <TableRow>
                                                <TableHeaderCellLeft>No</TableHeaderCellLeft>
                                                <TableHeaderCell>Payment Date</TableHeaderCell>
                                                <TableHeaderCell>Program/Receiver Name</TableHeaderCell>
                                                <TableHeaderCellRightTextStart>Total Receiver</TableHeaderCellRightTextStart>
                                            </TableRow>
                                        </template>
                                        <template #default>
                                            <TableRow v-for="payment in requestProcessings.data" :key="payment.id" class="border-b">
                                                <TableDataCell>{{ payment.id }}</TableDataCell>
                                                <TableDataCell> {{ payment.payment_date }}</TableDataCell>
                                                <TableDataCell v-if="payment.receiver_id === null">
                                                    {{ payment.program.name }}
                                                </TableDataCell>
                                                <TableDataCell v-else>
                                                    {{ payment.receiver.name }}
                                                </TableDataCell>
                                                <TableDataCell v-if="payment.receiver_id === null">
                                                    <Link :href="route('payments.programReceiverList', payment.program.id)" class="underline text-blue-600 ">
                                                        {{ payment.total_receiver }}
                                                    </Link>
                                                </TableDataCell>
                                                <TableDataCell v-else>
                                                    1
                                                </TableDataCell>
                                            </TableRow>
                                        </template>
                                    </Table>
                                    <div class="text-center mx-auto m-2 p-2">
                                        <TablePagination :links="requestProcessings.links" />
                                    </div>
                                </div>
                            </div>
                            <!--Proceed-->
                            <div class="hidden rounded-lg bg-gray-50" id="proceed" role="tabpanel" aria-labelledby="proceed-tab">
                                <div class="mt-2">
                                    <Table>
                                        <template #header>
                                            <TableRow>
                                                <TableHeaderCellLeft>No</TableHeaderCellLeft>
                                                <TableHeaderCell>Payment Date</TableHeaderCell>
                                                <TableHeaderCell>Program/Receiver Name</TableHeaderCell>
                                                <TableHeaderCellRightTextStart>Total Receiver</TableHeaderCellRightTextStart>
                                            </TableRow>
                                        </template>
                                        <template #default>
                                            <TableRow v-for="payment in requestProceeds.data" :key="payment.id" class="border-b">
                                                <TableDataCell>{{ payment.id }}</TableDataCell>
                                                <TableDataCell> {{ payment.payment_date }}</TableDataCell>
                                                <TableDataCell v-if="payment.receiver_id === null">
                                                    {{ payment.program.name }}
                                                </TableDataCell>
                                                <TableDataCell v-else>
                                                    {{ payment.receiver.name }}
                                                </TableDataCell>
                                                <TableDataCell v-if="payment.receiver_id === null">
                                                    <Link :href="route('payments.programReceiverList', payment.program.id)" class="underline text-blue-600 ">
                                                        {{ payment.total_receiver }}
                                                    </Link>
                                                </TableDataCell>
                                                <TableDataCell v-else>
                                                    1
                                                </TableDataCell>
                                            </TableRow>
                                        </template>
                                    </Table>
                                    <div class="text-center mx-auto m-2 p-2">
                                        <TablePagination :links="requestProceeds.links" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </Content>
            </div>
        </div>
    </AdminLayout>
</template>
