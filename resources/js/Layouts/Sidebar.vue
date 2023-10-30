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

import { CaGroupSecurity } from "@kalimahapps/vue-icons";
const showingNavigationDropdown = ref(false);
const { hasRole } = usePermission();




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
  <!-- give the sidebar z-50 class so its higher than the navbar if you want to see the logo -->
  <!-- you will need to add a little "X" button next to the logo in order to close it though -->
  <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-60 h-screen pt-14 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0" aria-label="Sidebar">

    <template v-if="hasRole('admin')">
            <div class="h-full pb-4 pt-5 overflow-y-auto bg-white">
                <div class="mb-4 px-2">
                    <p class="pl-4 text-xs text-gray-500 font-semibold mb-1">MAIN</p>
                    <SidebarLink2 class="hover:bg-primary-100 mb-0.5 " :href="route('dashboards.index')" :active="route().current('dashboards.index')">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="flex-shrink-0 w-5 h-5 mr-2 text-gray-500 transition duration-75 group-hover:text-gray-900">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 16.875h3.375m0 0h3.375m-3.375 0V13.5m0 3.375v3.375M6 10.5h2.25a2.25 2.25 0 002.25-2.25V6a2.25 2.25 0 00-2.25-2.25H6A2.25 2.25 0 003.75 6v2.25A2.25 2.25 0 006 10.5zm0 9.75h2.25A2.25 2.25 0 0010.5 18v-2.25a2.25 2.25 0 00-2.25-2.25H6a2.25 2.25 0 00-2.25 2.25V18A2.25 2.25 0 006 20.25zm9.75-9.75H18a2.25 2.25 0 002.25-2.25V6A2.25 2.25 0 0018 3.75h-2.25A2.25 2.25 0 0013.5 6v2.25a2.25 2.25 0 002.25 2.25z" />
                            </svg>                            
                            Dashboard
                        </div>
                    </SidebarLink2>
                    <SidebarLink2 class="hover:bg-primary-100 mb-0.5" :href="route('roles.index')" :active="route().current('roles.index')">
                        <div class="flex items-center"> 
                            <CaGroupSecurity class="text-xl mr-2 -mt-0.5 text-gray-500" />
                            System Role
                        </div>
                    </SidebarLink2>
                    <SidebarLink2 class="hover:bg-primary-100 mb-0.5" :href="route('users.index')" :active="route().current('users.index')">
                        <div class="flex items-center"> 
                            <CaUserMultiple class="text-xl mr-2 -mt-0.5 text-gray-500" /> 
                            Users List
                        </div>
                    </SidebarLink2>
                </div>
                <div class="mb-4 px-2">
                    <p class="pl-4 text-xs text-gray-500 font-semibold">ALLOCATIONS</p>
                    <SidebarLink2 class="hover:bg-primary-100 mb-0.5" :href="route('allocations.index')" :active="route().current('allocations.index')">
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 w-5 h-5 mr-2 text-gray-500  duration-300 group-hover:text-gray-900" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"
                                    />
                            </svg>
                            All Allocation
                        </div>
                    </SidebarLink2>
                    <SidebarLink2 class="hover:bg-primary-100 mb-0.5" :href="route('allocations.waitingApproval')" :active="route().current('allocations.waitingApproval')">
                        <div class="flex items-center">
                            <BsSendCheck class="text-lg mr-2 mt-0.5 text-gray-500" />
                            Waiting Approval
                        </div>
                    </SidebarLink2>
                </div>
                <div class="mb-4 px-2">
                    <p class="pl-4 text-xs text-gray-500 font-semibold mt-2">PROGRAMS</p>
                    <SidebarLink2 class="hover:bg-primary-100 mb-0.5" :href="route('programs.index')" :active="route().current('programs.index')">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="flex-shrink-0 w-5 mr-2 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0021 18V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v12a2.25 2.25 0 002.25 2.25z" />
                            </svg>                            
                            All Program
                        </div>
                    </SidebarLink2>
                    <SidebarLink2 class="hover:bg-primary-100" :href="route('programs.waitingApproval')" :active="route().current('programs.waitingApproval')">
                        <div class="flex items-center">
                            <BsSendCheck class="text-lg mr-2 mt-0.5 text-gray-500" />
                            Waiting Approval
                        </div>
                    </SidebarLink2>
                </div>
                <div class="mb-4 px-2">
                    <p class="pl-4 text-xs text-gray-500 font-semibold mt-2">RECEIVERS</p>
                    <SidebarLink2 class="hover:bg-primary-100 mb-0.5" :href="route('receivers.index')" :active="route().current('receivers.index')">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" class="flex-shrink-0 w-5 h-5 mr-2 text-gray-500 transition duration-75 group-hover:text-gray-900">
                                <path strokeLinecap="round" strokeLinejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0118 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3l1.5 1.5 3-3.75" />
                            </svg>
                            All Receiver
                        </div>
                    </SidebarLink2>
                    <SidebarLink2 class="hover:bg-primary-100" :href="route('receivers.waitingApproval')" :active="route().current('receivers.waitingApproval')">
                        <div class="flex items-center">
                            <BsSendCheck class="text-lg mr-2 mt-0.5 text-gray-500" />
                            Waiting Approval
                        </div>
                    </SidebarLink2>
                </div>
                <div class="mb-4 px-2">
                    <p class="pl-4 text-xs text-gray-500 font-semibold mt-2">OTHERS</p>
                    <SidebarLink2  :href="route('payments.index')" :active="route().current('payments.index')" class="hover:bg-primary-100 mb-0.5">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" class="flex-shrink-0 w-5 h-5 mr-2 text-gray-500 transition duration-75 group-hover:text-gray-900">
                                <path strokeLinecap="round" strokeLinejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0118 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3l1.5 1.5 3-3.75" />
                            </svg>
                            Payments
                        </div>
                    </SidebarLink2>
                    <SidebarLink2 class="hover:bg-primary-100">
                        <div class="flex items-center">
                            <BsSendCheck class="text-lg mr-2 mt-0.5 text-gray-500" />
                            Audit Trails
                        </div>
                    </SidebarLink2>
                </div>
            </div>
        </template>

        </aside>
</template>
