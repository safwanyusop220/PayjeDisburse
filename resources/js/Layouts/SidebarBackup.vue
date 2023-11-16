<script setup>
import { ref } from 'vue';
import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import SidebarLink from "@/Components/SidebarLink.vue";
import SidebarLink2 from "@/Components/SidebarLink2.vue";
import { usePermission } from '@/composables/permissions';

import SidebarLink3 from "@/Components/SidebarLink3.vue";
import { CaUserMultiple } from "@kalimahapps/vue-icons";
import { BsSendCheck } from "@kalimahapps/vue-icons";
import { BsUiChecks } from "@kalimahapps/vue-icons";
import { CdPieChart } from "@kalimahapps/vue-icons";
import { BxCheckShield } from "@kalimahapps/vue-icons";
import { BsCardChecklist } from "@kalimahapps/vue-icons";
import { CaGroupSecurity } from "@kalimahapps/vue-icons";
import { CaListBoxes } from "@kalimahapps/vue-icons";
import { LuLayoutList } from "@kalimahapps/vue-icons";
import { CdFileSubmodule } from "@kalimahapps/vue-icons";
import { PhBoldScroll } from "@kalimahapps/vue-icons";
import { FlPeopleSync } from "@kalimahapps/vue-icons";
import { AkPeopleGroup } from "@kalimahapps/vue-icons";
import { FlPeopleCheckmark } from "@kalimahapps/vue-icons";
import { FlPeopleList } from "@kalimahapps/vue-icons";
import { McListCheck3Line } from "@kalimahapps/vue-icons";

import Menu from "@/Components/Menu.vue";
import {
  Sidenav,
  initTE,
} from "tw-elements";

initTE({ Sidenav });


document
.getElementById("slim-toggler")
.addEventListener("click", () => {
  const instance = Sidenav.getInstance(
    document.getElementById("sidenav-4")
  );
  instance.toggleSlim();
});

const showingNavigationDropdown = ref(false);
const { hasRole } = usePermission();
const { hasPermission } = usePermission();
const { hasRoles } = usePermission();


onMounted(() => {
    initFlowbite();
})

const isOpen = ref(false);

const toggleOpen = () => {
  isOpen = !isOpen.value;
};


const isSubmenuVisible = ref(false);

const toggleSubmenu = () => {
  isSubmenuVisible.value = !isSubmenuVisible.value;
};

</script> 
<template>

  <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-52 h-screen pt-14 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0" aria-label="Sidebar">
    
        <div class="h-full pb-4 pt-5 overflow-y-auto bg-white">
            <!--Main-->
            <div class="mb-4 px-2">
                <p class="pl-4 text-xs text-gray-500 font-semibold mb-1">MAIN</p>
                <!--Dashboard-->
                <SidebarLink2 class="hover:bg-primary-100 mb-0.5 " :href="route('dashboard')" :active="route().current('dashboard')">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="flex-shrink-0 w-5 h-5 mr-2 text-gray-500 transition duration-75 group-hover:text-gray-900">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 16.875h3.375m0 0h3.375m-3.375 0V13.5m0 3.375v3.375M6 10.5h2.25a2.25 2.25 0 002.25-2.25V6a2.25 2.25 0 00-2.25-2.25H6A2.25 2.25 0 003.75 6v2.25A2.25 2.25 0 006 10.5zm0 9.75h2.25A2.25 2.25 0 0010.5 18v-2.25a2.25 2.25 0 00-2.25-2.25H6a2.25 2.25 0 00-2.25 2.25V18A2.25 2.25 0 006 20.25zm9.75-9.75H18a2.25 2.25 0 002.25-2.25V6A2.25 2.25 0 0018 3.75h-2.25A2.25 2.25 0 0013.5 6v2.25a2.25 2.25 0 002.25 2.25z" />
                        </svg>                            
                        Dashboard
                    </div>
                </SidebarLink2>
                <!--Permission
                <SidebarLink2 class="hover:bg-primary-100 mb-0.5" :href="route('permissions.index')" :active="route().current('permissions.index')">
                    <div class="flex items-center"> 
                        <CaGroupSecurity class="text-xl mr-2 -mt-0.5 text-gray-500" />
                        permissions
                    </div>
                </SidebarLink2>-->
                <!--Role-->
                <SidebarLink2 class="hover:bg-primary-100 mb-0.5" :href="route('roles.index')" :active="route().current('roles.index')">
                    <div class="flex items-center"> 
                        <CaGroupSecurity class="text-xl mr-2 -mt-0.5 text-gray-500" />
                        System Role
                    </div>
                </SidebarLink2>
                <!--User-->
                <SidebarLink2 class="hover:bg-primary-100 mb-0.5" :href="route('users.index')" :active="route().current('users.index')">
                    <div class="flex items-center"> 
                        <CaUserMultiple class="text-xl mr-2 -mt-0.5 text-gray-500" /> 
                        System User
                    </div>
                </SidebarLink2>
            </div>
            <!--Allocation-->
            <div class="mb-4 px-2">
                <p class="pl-4 text-xs text-gray-500 font-semibold">ALLOCATIONS</p>
                <SidebarLink2 class="hover:bg-primary-100 mb-0.5" :href="route('allocations.index')" :active="route().current('allocations.index') || route().current('allocations.create') || route().current('allocations.edit')">
                    <div class="flex items-center">
                        <CdPieChart class="text-lg mr-2 mt-0.5 text-gray-500" />
                        All
                    </div>
                </SidebarLink2>  
                <template v-if="hasPermission('Allocation Recommendation') || hasRole('Super Admin') || hasRole('Recommender')">
                    <SidebarLink2 class="hover:bg-primary-100 mb-0.5" :href="route('allocations.waitingRecommendation')" :active="route().current('allocations.waitingRecommendation') || route().current('allocations.allocationRecommendation')">
                        <div class="flex items-center">
                            <McListCheck3Line class="text-xl mr-1.5 mt-0.5 text-gray-500" />
                            Recommendation
                        </div>
                    </SidebarLink2>
                </template>
                
                <template v-if="hasPermission('Allocation Approval') || hasRole('Super Admin')">
                    <SidebarLink2 class="hover:bg-primary-100 mb-0.5" :href="route('allocations.waitingApproval')" :active="route().current('allocations.waitingApproval') || route().current('allocations.allocationApproval')">
                        <div class="flex items-center">
                            <BsUiChecks class="text-base mr-2 ml-0.5 mt-0.5 text-gray-500" />
                            Approval
                        </div>
                    </SidebarLink2>
                </template>
            </div>
            <!--Program-->
            <div class="mb-4 px-2">
                <p class="pl-4 text-xs text-gray-500 font-semibold mt-2">PROGRAMS</p>
                <SidebarLink2 class="hover:bg-primary-100 mb-0.5" :href="route('programs.index')" :active="route().current('programs.index') || route().current('programs.create')|| route().current('programs.edit')">
                    <div class="flex items-center">
                        <PhBoldScroll class="text-base mr-2 -mt-0.5 text-gray-500" />                        
                        All
                    </div>
                </SidebarLink2>

                <template v-if="hasPermission('Program Recommendation') || hasRole('Super Admin') || hasRole('Recommender')">
                    <SidebarLink2 class="hover:bg-primary-100 mb-0.5" :href="route('programs.waitingRecommendation')" :active="route().current('programs.waitingRecommendation')|| route().current('programs.programRecommendation')">
                        <div class="flex items-center">
                            <McListCheck3Line class="text-xl mr-1.5 mt-0.5 text-gray-500" />
                            Recommendation
                        </div>
                    </SidebarLink2>
                </template>

                <template v-if="hasPermission('Program Approval') || hasRole('Super Admin') || hasRole('Approver')">
                    <SidebarLink2 class="hover:bg-primary-100" :href="route('programs.waitingApproval')" :active="route().current('programs.waitingApproval') || route().current('programs.programApproval')">
                        <div class="flex items-center">
                            <BsUiChecks class="text-base ml-0.5 mr-2 mt-0.5 text-gray-500" />
                            Approval
                        </div>
                    </SidebarLink2>
                </template>
            </div>
            <!--Receiver-->
            <div class="mb-4 px-2">
                <p class="pl-4 text-xs text-gray-500 font-semibold mt-2">RECEIVERS</p>
                <SidebarLink2 class="hover:bg-primary-100 mb-0.5" :href="route('receivers.index')" :active="route().current('receivers.index')|| route().current('receivers.create')|| route().current('receivers.edit')">
                    <div class="flex items-center">
                        <FlPeopleList class="text-xl  mr-2 -mt-0.5 text-gray-500" />
                        All
                    </div>
                </SidebarLink2>
                <template v-if="hasPermission('Receiver Recommendation') || hasRole('Super Admin') || hasRole('Recommender')">
                    <SidebarLink2 class="hover:bg-primary-100 mb-0.5" :href="route('receivers.waitingRecommendation')" :active="route().current('receivers.waitingRecommendation')">
                        <div class="flex items-center">
                            <McListCheck3Line class="text-xl mr-1.5 mt-0.5 text-gray-500" />
                            Recommendation
                        </div>
                    </SidebarLink2>
                </template>

                <template v-if="hasPermission('Receiver Approval') || hasRole('Super Admin') || hasRole('Approver')">
                    <SidebarLink2 class="hover:bg-primary-100" :href="route('receivers.waitingApproval')" :active="route().current('receivers.waitingApproval')|| route().current('receivers.receiverApproval')">
                        <div class="flex items-center">
                            <FlPeopleCheckmark class="text-xl mr-2 mt-0.5 text-gray-500" />
                            Approval
                        </div>
                    </SidebarLink2>
                </template>
            </div>
            <!--Other-->
            <div class="mb-4 px-2">
                <p class="pl-4 text-xs text-gray-500 font-semibold mt-2">OTHERS</p>
                <SidebarLink2  :href="route('payments.index')" :active="route().current('payments.index') || route().current('payments.programReceiverList')" class="hover:bg-primary-100 mb-0.5">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" class="flex-shrink-0 w-5 h-5 mr-2 text-gray-500 transition duration-75 group-hover:text-gray-900">
                            <path strokeLinecap="round" strokeLinejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0118 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3l1.5 1.5 3-3.75" />
                        </svg>
                        Payments
                    </div>
                </SidebarLink2>
                <SidebarLink2 :href="route('audits.index')" :active="route().current('audits.index')" class="hover:bg-primary-100">
                    <div class="flex items-center">
                        <BsSendCheck class="text-lg mr-2 mt-0.5 text-gray-500" />
                        Audit Trails
                    </div>
                </SidebarLink2>
            </div>
        </div>

  </aside>
</template>
