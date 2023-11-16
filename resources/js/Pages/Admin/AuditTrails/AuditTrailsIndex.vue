<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, watch, defineProps } from "vue";
import { router } from '@inertiajs/vue3'

import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import TableDataCell from "@/Components/TableDataCell.vue";
import TableHeaderCellLeft from "@/Components/TableHeaderCellLeft.vue";
import TableHeaderCellRight from "@/Components/TableHeaderCellRight.vue";
import TableHeaderCellRightTextStart from "@/Components/TableHeaderCellRightTextStart.vue";
import TablePagination from "@/Components/TablePagination.vue";

import SpanSubmittedStatus from "@/Components/SpanSubmittedStatus.vue";
import SpanRecommendedStatus from "@/Components/SpanRecommendedStatus.vue";
import SpanApprovedStatus from "@/Components/SpanApprovedStatus.vue";
import SpanRejectedStatus from "@/Components/SpanRejectedStatus.vue";

import Content from "@/Components/Content.vue";

import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SuccessButton from "@/Components/SuccessButton.vue";
import AddButton from "@/Components/AddButton.vue";

import BreadcrumbInitial from "@/Components/BreadcrumbInitial.vue";
import BreadcrumbActive from "@/Components/BreadcrumbActive.vue";
import BreadcrumbCurrent from "@/Components/BreadcrumbCurrent.vue";

import { BsSearch } from "@kalimahapps/vue-icons";
import { SuCreate } from "@kalimahapps/vue-icons";
import { FlDelete } from "@kalimahapps/vue-icons";

const props = defineProps({
    auditTrails: {
        type: Object,
        required: true,
    },
    filters: Object,
});

const formatDate = (dateTime) => {
  const date = new Date(dateTime);
  return date.toLocaleDateString();
};

const formatTime = (dateTime) => {
  const date = new Date(dateTime);

  const hours = date.getHours() % 12 || 12;
  const minutes = date.getMinutes();
  const ampm = date.getHours() >= 12 ? 'PM' : 'AM';

  return `${hours}:${minutes < 10 ? '0' : ''}${minutes} ${ampm}`;
};

const search = ref(props.filters.search);
const perPage = ref(5);

watch(search, (value) =>{
    router.get("/audits", {search: value, perPage: perPage.value},
    {
        preserveState: true,
        replace: true
    });
})

function getAllocations() {
    router.get(
        "/audits",
        { perPage: perPage.value, search: search.value },
        {
            preserveState: true,
            replace: true,
        }
    );
}

</script>

<template>
    <Head title="Allocation" />

    <AdminLayout>
        <div class="mt-14">
            <div class="mx-auto w-full">
                <div class="flex justify-between pt-4">
                    <nav class="text-sm font-semibold" aria-label="Breadcrumb">
                        <ol class="inline-flex list-none">
                            <li class="flex items-center">
                                <BreadcrumbInitial :href="route('audits.index')"></BreadcrumbInitial>
                            </li>
                            <li class="flex items-center">
                                <BreadcrumbCurrent>
                                    Audit Trails
                                </BreadcrumbCurrent>
                            </li>
                        </ol>
                    </nav>
                </div>
                <Content>
                    <div class="flex justify-between">
                        <span class="font-medium text-primary-text text-lg mt-2">Audit Trails</span>
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
                            class="rounded-md bg-primary-50 text-xs text-primary-500 border-none focus:ring-primary-100">
                            <option value="1">10</option>
                            <option value="2">20</option>
                            <option value="5">50</option>
                            <option value="10">100</option>
                        </select>
                    </div>

                    <div class="mt-2 bg-white">
                        <Table>
                            <template #header>
                                <TableRow>
                                    <TableHeaderCellLeft>No</TableHeaderCellLeft>
                                    <TableHeaderCell>Date Created</TableHeaderCell>
                                    <TableHeaderCell>Time Created</TableHeaderCell>
                                    <TableHeaderCell>Action</TableHeaderCell>
                                    <TableHeaderCell>Activity</TableHeaderCell>
                                    <TableHeaderCellRightTextStart>User</TableHeaderCellRightTextStart>
                                </TableRow>
                            </template>
                            <template #default>
                                <TableRow v-for="(auditTrail, index) in auditTrails.data" :key="auditTrail.id" class="border-b">
                                    <TableDataCell>{{ index + 1 }}</TableDataCell>
                                    <TableDataCell>{{formatDate(auditTrail.created_at)}}</TableDataCell>
                                    <TableDataCell>{{formatTime(auditTrail.created_at)}}</TableDataCell>
                                    <TableDataCell>{{auditTrail.action}}</TableDataCell>
                                    <TableDataCell>{{auditTrail.activity}}</TableDataCell>
                                    <TableDataCell>{{auditTrail.user.name}}</TableDataCell>
                                </TableRow>
                            </template>
                        </Table>
                        
                    </div>

                    <div class="m-2 mx-auto p-2 text-center">
                        <TablePagination :links="auditTrails.links" />
                    </div>
                </Content>
            </div>
        </div>
    </AdminLayout>
    
</template>