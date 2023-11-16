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

import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import AddButton from "@/Components/AddButton.vue";

import Content from "@/Components/Content.vue";

// BreadCrumb
import BreadcrumbInitial from "@/Components/BreadcrumbInitial.vue";
import BreadcrumbActive from "@/Components/BreadcrumbActive.vue";
import BreadcrumbCurrent from "@/Components/BreadcrumbCurrent.vue";

defineProps(['receivers'])

const form = useForm({})


</script>

<template>
    <Head title="Payments" />

    <AdminLayout>
        <div class="mt-14">
            <div class="w-full mx-auto">
                <div class="overflow-hidden">
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
                                    <BreadcrumbActive :href="route('payments.index')">Request</BreadcrumbActive>
                                </li>
                                <li class="flex items-center">
                                    <BreadcrumbCurrent>Receiver List</BreadcrumbCurrent>
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <Content>
                        <div class="flex justify-between mt-1 mb-3">
                            <span class="font-medium text-primary-text text-lg mt-1.5">
                                Program : {{ receivers.data[0].program.name }}
                            </span>
                        </div>
                    
                        <div class="border-b-2 border-gray-200 mb-2"></div>

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
                                    <TableHeaderCellLeft>No</TableHeaderCellLeft>
                                    <TableHeaderCell>Payment Date</TableHeaderCell>
                                    <TableHeaderCell>Receiver Name</TableHeaderCell>
                                    <TableHeaderCell>Program</TableHeaderCell>
                                    <TableHeaderCell>Bank</TableHeaderCell>
                                    <TableHeaderCell>Account Number</TableHeaderCell>
                                    <TableHeaderCellRightTextStart>Allocation Rate</TableHeaderCellRightTextStart>
                                </TableRow>
                            </template>
                            <template #default>
                                <TableRow v-for="receiver in receivers.data" :key="receiver.id" class="border-b">
                                    <TableDataCell>{{ receiver.id }}</TableDataCell>
                                    <TableDataCell>{{ receiver.program.payment_date }}</TableDataCell>
                                    <TableDataCell>{{ receiver.name }}</TableDataCell>
                                    <TableDataCell>{{ receiver.program.name }}</TableDataCell>
                                    <TableDataCell>{{ receiver.bank.name }}</TableDataCell>
                                    <TableDataCell>{{ receiver.account_number }}</TableDataCell>
                                    <TableDataCell>RM {{ receiver.program.allocation_rate }}</TableDataCell>
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
        </div>
    </AdminLayout>
</template>
