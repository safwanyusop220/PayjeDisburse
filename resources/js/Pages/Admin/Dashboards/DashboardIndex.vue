<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { initFlowbite } from 'flowbite';
import NeedApprovalButton from "@/Components/NeedApprovalButton.vue";
import Chart from "@/Components/Chart.vue";

import { ByUser } from "@kalimahapps/vue-icons";
import { FaUserLarge } from "@kalimahapps/vue-icons";
import { FaRegUser } from "@kalimahapps/vue-icons";
import { LaHandsHelpingSolid } from "@kalimahapps/vue-icons";
import { LuHelpingHand } from "@kalimahapps/vue-icons";
import { HeOutlineMoneyBag } from "@kalimahapps/vue-icons";
import { FlMoneyHand } from "@kalimahapps/vue-icons";

// BreadCrumb
import BreadcrumbInitial from "@/Components/BreadcrumbInitial.vue";
import BreadcrumbActive from "@/Components/BreadcrumbActive.vue";
import BreadcrumbCurrent from "@/Components/BreadcrumbCurrent.vue";


// Icon
import { FlMoneyCalculator } from "@kalimahapps/vue-icons";
import { MdOutlinedCreateNewFolder } from "@kalimahapps/vue-icons";

const props = defineProps({
  receivers: Number,
  newReceivers: Number,
  programs: Number,
  newPrograms: Number,
  totalAllocations: Number
});
onMounted(() => {
  // Call initFlowbite here, outside the setup block
  initFlowbite();
})


</script>

<template>
    <Head title="Dashboard" />

    <AdminLayout>

        

        <div class="flex justify-between mt-14">
            <div class="flex items-center">
                <span class="font-bold text-black text-xl mr-1">Dashboard</span>
            </div>

            

            <nav class="text-sm font-semibold" aria-label="Breadcrumb">
                <ol class="list-none p-0 inline-flex">
                    <li class="flex items-center">
                        <BreadcrumbInitial :href="route('dashboards.index')">Home</BreadcrumbInitial>
                    </li>
                    <li class="flex items-center">
                        <BreadcrumbCurrent>Dashbaord</BreadcrumbCurrent>
                    </li>
                </ol>
            </nav>
        </div>
        <div class="">
            <div class=" mx-auto">
                <div class="overflow-hidden">
                    <div class="py-6 ">
                        <div class="grid grid-cols-12 gap-4">
                            <!--Receiver-->
                            <div class="col-span-12 sm:col-span-6 md:col-span-4 lg:col-span-3 xl:col-span-3">
                                <a :href="route('receivers.index')">
                                    <div class="bg-card-first overflow-hidden rounded-lg shadow-md">
                                        <div class="p-4">
                                            <div class="flex items-center justify-center h-full">
                                                <div class="my-2">
                                                    <div class="flex justify-between">
                                                        <h2 class="text-3xl font-bold mt-1 text-white">{{ receivers }}</h2>
                                                        <FaRegUser class="text-white mt-2" style="font-size: 2em;"/>
                                                    </div>
                                                    <h6 class="text-sm font-normal text-white mr-16">Total Receiver</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <!--Receiver-->
                            <div class="col-span-12 sm:col-span-6 md:col-span-4 lg:col-span-3 xl:col-span-3">
                                <a :href="route('programs.index')">
                                    <div class="bg-card-second overflow-hidden rounded-lg shadow-md">
                                        <div class="p-4">
                                            <div class="flex items-center justify-center h-full">
                                                <div class="my-2">
                                                    <div class="flex justify-between">
                                                        <h2 class="text-3xl font-bold mt-1 text-white">{{ programs }}</h2>
                                                        <LaHandsHelpingSolid class="text-white" style="font-size: 2.5em;"/>
                                                    </div>
                                                    <h6 class="text-sm font-normal text-white  mr-16">Total Program</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!--Allocation-->
                            <div class="col-span-12 sm:col-span-6 md:col-span-4 lg:col-span-3 xl:col-span-3">
                                <a :href="route('allocations.index')">
                                    <div class="bg-card-third overflow-hidden rounded-lg shadow-md">
                                        <div class="p-4">
                                            <div class="flex items-center justify-center h-full">
                                                <div class="my-2">
                                                    <div class="flex justify-between">
                                                        <h2 class="text-3xl font-bold mt-1 text-white">{{ totalAllocations }}</h2>
                                                        <HeOutlineMoneyBag  class="text-white" style="font-size: 2.5em;"/>
                                                    </div>
                                                    <h6 class="text-sm font-normal text-white mr-16">Total Allocation</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <!--Today Payment-->
                            <div class="col-span-12 sm:col-span-6 md:col-span-4 lg:col-span-3 xl:col-span-3">
                                <div class="bg-card-fourth overflow-hidden rounded-lg shadow-md">
                                    <div class="p-4">
                                        <div class="flex items-center justify-center h-full">
                                            <div class="my-2">
                                                <div class="flex justify-between">
                                                    <h2 class="text-3xl font-bold mt-1 text-white">{{ programs }}</h2>
                                                    <FlMoneyHand  class="text-white" style="font-size: 2.5em;"/>
                                                </div>
                                                <h6 class="text-sm font-normal text-white mr-16">Today Payment</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row py-2">
                        <div class="grid grid-cols-12 gap-5">
                            <div class="col-span-6 w-full bg-slate-50 rounded-lg shadow-lg p-4">
                                <div class="flex flex-col mb-4">
                                    <div class="flex items-center ml-4">
                                        <h3 class="text-5xl font-bold text-black">{{ newReceivers }}</h3>
                                        <p class="text-sm font-extralight text-black mt-5">New Receivers</p>
                                    </div>
                                </div>
                                <div class="flex justify-center">
                                    <NeedApprovalButton :href="route('receivers.waitingApproval')"/>
                                </div>
                            </div>
                            <div class="col-span-6 w-full bg-slate-50 rounded-lg shadow-lg p-4">
                                <div class="flex flex-col mb-4">
                                    <div class="flex items-center ml-4">
                                        <h3 class="text-5xl font-bold text-black mr-1">{{ newPrograms }}</h3>
                                        <p class="text-sm font-extralight text-black mt-5">New Programs</p>
                                    </div>
                                </div>
                                <div class="flex justify-center">
                                    <NeedApprovalButton :href="route('programs.waitingApproval')"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="py-3">                   
                        <div class="row">
                            <div class="grid grid-cols-5 gap-4">
                            <div class="col-span-5">
                                <Chart class="bg-slate-50 rounded-lg shadow-lg p-4"/>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
