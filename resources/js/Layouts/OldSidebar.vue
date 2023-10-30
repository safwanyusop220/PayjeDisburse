<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import SidebarLink from "@/Components/SidebarLink.vue";
import { usePermission } from '@/composables/permissions';

const showingNavigationDropdown = ref(false);
const { hasRole } = usePermission();

import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'

const isSubmenuVisible = ref(false);

const toggleSubmenu = () => {
  isSubmenuVisible.value = !isSubmenuVisible.value;
};

onMounted(() => {
    initFlowbite();
})

import { CgChevronDown } from "@kalimahapps/vue-icons";
import { PhFillContactlessPayment } from "@kalimahapps/vue-icons";
import { McBankFill } from "@kalimahapps/vue-icons";
import OldSidebar from './OldSidebar.vue';


const isOpen = ref(false);

const toggleOpen = () => {
  isOpen = !isOpen.value;
};

</script>
<template>
  <!-- give the sidebar z-50 class so its higher than the navbar if you want to see the logo -->
  <!-- you will need to add a little "X" button next to the logo in order to close it though -->
  <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-14 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0" aria-label="Sidebar">
            <div class="h-full px-3 pb-4 pt-5 overflow-y-auto bg-primary-100">
                <ul class="space-y-2 font-medium">
                    <!--<li>
                        <SidebarLink href="/dashboard" :active="false" >
                        <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                            <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
                            <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
                        </svg>
                        <span class="ml-3">Dashboard test</span>
                        </SidebarLink>
                    </li>-->
                    <li>
                        <SidebarLink class="hover:bg-primary-200" :href="route('dashboards.index')" :active="route().current('dashboards.index')" >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 16.875h3.375m0 0h3.375m-3.375 0V13.5m0 3.375v3.375M6 10.5h2.25a2.25 2.25 0 002.25-2.25V6a2.25 2.25 0 00-2.25-2.25H6A2.25 2.25 0 003.75 6v2.25A2.25 2.25 0 006 10.5zm0 9.75h2.25A2.25 2.25 0 0010.5 18v-2.25a2.25 2.25 0 00-2.25-2.25H6a2.25 2.25 0 00-2.25 2.25V18A2.25 2.25 0 006 20.25zm9.75-9.75H18a2.25 2.25 0 002.25-2.25V6A2.25 2.25 0 0018 3.75h-2.25A2.25 2.25 0 0013.5 6v2.25a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                        <span class="ml-3 text-sm font-bold">Dashboard</span>
                        </SidebarLink>
                    </li>
                    
                    <template v-if="hasRole('admin')">
                        <li>
                            <SidebarLink class="hover:bg-primary-200" :href="route('roles.index')" :active="route().current('roles.index')" >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12a7.5 7.5 0 0015 0m-15 0a7.5 7.5 0 1115 0m-15 0H3m16.5 0H21m-1.5 0H12m-8.457 3.077l1.41-.513m14.095-5.13l1.41-.513M5.106 17.785l1.15-.964m11.49-9.642l1.149-.964M7.501 19.795l.75-1.3m7.5-12.99l.75-1.3m-6.063 16.658l.26-1.477m2.605-14.772l.26-1.477m0 17.726l-.26-1.477M10.698 4.614l-.26-1.477M16.5 19.794l-.75-1.299M7.5 4.205L12 12m6.894 5.785l-1.149-.964M6.256 7.178l-1.15-.964m15.352 8.864l-1.41-.513M4.954 9.435l-1.41-.514M12.002 12l-3.75 6.495" />
                                </svg>
                            <span class="ml-3 text-sm font-bold">System Role</span>
                            </SidebarLink>
                        </li>
                        <li>
                            <SidebarLink class="hover:bg-primary-200" :href="route('users.index')" :active="route().current('users.index')" >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                </svg>
                            <span class="ml-3 text-sm font-bold">Users</span>
                            </SidebarLink>
                        </li>
                        <li>
                            <SidebarLink class="hover:bg-primary-200" :href="route('permissions.index')" :active="route().current('permissions.index')" >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.864 4.243A7.5 7.5 0 0119.5 10.5c0 2.92-.556 5.709-1.568 8.268M5.742 6.364A7.465 7.465 0 004.5 10.5a7.464 7.464 0 01-1.15 3.993m1.989 3.559A11.209 11.209 0 008.25 10.5a3.75 3.75 0 117.5 0c0 .527-.021 1.049-.064 1.565M12 10.5a14.94 14.94 0 01-3.6 9.75m6.633-4.596a18.666 18.666 0 01-2.485 5.33" />
                                </svg>
                            <span class="ml-3 text-sm font-bold">User Permissions</span>
                            </SidebarLink>
                        </li>
                        <li>
                            <button type="button" class="flex items-center w-full p-2 text-base text-gray-900  duration-300 rounded-lg group hover:bg-primary-200" aria-controls="dropdown-peruntukkan" data-collapse-toggle="dropdown-peruntukkan">
                                <svg class="flex-shrink-0 w-5 h-5 text-gray-500  duration-300 group-hover:text-gray-900" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"
                                     />
                                </svg>
                                <span class="flex-1 ml-3 text-sm font-bold text-left whitespace-nowrap">Allocations</span>
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                                </svg>
                            </button>
                            <ul id="dropdown-peruntukkan" class="hidden py-2 space-y-2">
                                <li>
                                    <SidebarLink :href="route('allocations.index')" :active="route().current('allocations.index')" class="text-xs font-semibold flex items-center w-full px-2 py-2 text-gray-600 transition duration-75 rounded-lg pl-11 group hover:bg-primary-200">All</SidebarLink>
                                </li>
                                <li>
                                    <SidebarLink :href="route('allocation-waiting-approvals.index')" :active="route().current('allocation-waiting-approvals.index')" class="text-xs font-semibold flex items-center w-full px-2 py-2 text-gray-600 transition duration-75 rounded-lg pl-11 group hover:bg-primary-200">Waiting Approval</SidebarLink>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <button type="button" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-primary-200" aria-controls="dropdown-program" data-collapse-toggle="dropdown-program">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0021 18V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v12a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                                <span class="flex-1 ml-3 text-sm font-bold text-left whitespace-nowrap">Program</span>
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                                </svg>
                            </button>
                            <ul id="dropdown-program" class="hidden py-2 space-y-2">
                                <li>
                                    <SidebarLink :href="route('programs.index')" :active="route().current('programs.index')"  class="text-xs font-semibold flex items-center w-full px-2 py-2 text-gray-600 transition duration-75 rounded-lg pl-11 group hover:bg-primary-200 ">All</SidebarLink>
                                </li>
                                <li>
                                    <SidebarLink :href="route('programs.waitingApproval')" :active="route().current('programs.waitingApproval')"  class="text-xs font-semibold flex items-center w-full px-2 py-2 text-gray-600 transition duration-75 rounded-lg pl-11 group hover:bg-primary-200 ">Waiting Approval</SidebarLink>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <button type="button" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-primary-200" aria-controls="dropdown-penerima" data-collapse-toggle="dropdown-penerima">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0118 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3l1.5 1.5 3-3.75" />
                                </svg>
                                <span class="flex-1 ml-3 text-sm font-bold text-left whitespace-nowrap">Receivers</span>
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                                </svg>
                            </button>
                            <ul id="dropdown-penerima" class="hidden py-2 space-y-2">
                                <li>
                                    <SidebarLink :href="route('receivers.index')" :active="route().current('receivers.index')"  class="text-xs font-semibold flex items-center w-full px-2 py-2 text-gray-600 transition duration-75 rounded-lg pl-11 group hover:bg-primary-200 ">All</SidebarLink>
                                </li>
                                <li>
                                    <SidebarLink :href="route('receivers.waitingApproval')" :active="route().current('receivers.waitingApproval')"  class="text-xs font-semibold flex items-center w-full px-2 py-2 text-gray-600 transition duration-75 rounded-lg pl-11 group hover:bg-primary-200 ">Waiting Approval</SidebarLink>
                                </li>
                                <li>
                                    <a href="#" class="text-xs font-semibold flex items-center w-full px-2 py-2 text-gray-600 transition duration-75 rounded-lg pl-11 group hover:bg-primary-200">Rejected Approval</a>
                                </li>
                            </ul>
                        </li>
                    </template>
                    <li>
                        <SidebarLink :href="route('posts.index')" :active="route().current('posts.index')" >
                        <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                            <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
                            <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
                        </svg>
                        <span class="ml-3 text-sm font-bold">Posts</span>
                        </SidebarLink>
                    </li>
                </ul>
            </div>
        </aside>

</template>
