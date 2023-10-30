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

//Icon
import { AkArrowLeft } from "@kalimahapps/vue-icons";
import { PhFillContactlessPayment } from "@kalimahapps/vue-icons";
import { RaDashboard } from "@kalimahapps/vue-icons";
import { CgChevronDown } from "@kalimahapps/vue-icons";

import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'


onMounted(() => {
    initFlowbite();
})

const isOpen = ref(true);
const submenuOpen = ref(false);

const toggleSubmenuOpen = () => {
    submenuOpen = true;
}

const toggleOpen = () => {
  isOpen.value = !isOpen.value;
};

const menus = [
  { id: 1, label: 'Home', route: '/' },
  { id: 2, label: 'About', route: '/about' },
  {
    id: 3,
    label: 'Services',
    route: '/services',
    submenu: true,
    submenuItems: [
      { id: 31, label: 'Service 1', route: '/service1' },
      { id: 32, label: 'Service 2', route: '/service2' },
    ],
  },
  { id: 4, label: 'Contact', route: '/contact' },
];
</script>

<template>
    <div>
        <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <div class="px-3 py-3 lg:px-5 lg:pl-3">
                <div class="flex items-center justify-between">
                <div class="flex items-center justify-start">
                    <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                        </svg>
                    </button>
                    <a href="https://flowbite.com" class="flex ml-2 md:mr-24">
                    <img src="https://flowbite.com/docs/images/logo.svg" class="h-8 mr-3" alt="FlowBite Logo" />
                    <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white">{{ $page.props.auth.user.name }}</span>
                    </a>
                </div>
                <div class="flex items-center">
                    <div class="flex items-center ml-3">
                        <div>
                        <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                            <span class="sr-only">Open user menu</span>
                            <img class="w-8 h-8 rounded-full" src="https://flowbite.com/docs/images/people/profile-picture-5.jpg" alt="user photo">
                        </button>
                        </div>
                        <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600" id="dropdown-user">
                        <div class="px-4 py-3" role="none">
                            <p class="text-sm text-gray-900 dark:text-white" role="none">
                            Neil Sims
                            </p>
                            <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300" role="none">
                            neil.sims@flowbite.com
                            </p>
                        </div>
                        <ul class="py-1" role="none">
                            <li>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Dashboard</a>
                            </li>
                            <li>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Settings</a>
                            </li>
                            <li>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Earnings</a>
                            </li>
                            <li>
                            <Link :href="route('logout')" method="post" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Sign out</Link>
                            </li>
                        </ul>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </nav>

        <div class="flex pt-14">
            <div :class="isOpen ? 'bg-indigo-900 h-screen p-5 pt-8 text-white duration-300 relative w-72' : 'bg-indigo-900 h-screen p-5 pt-8 text-white duration-300 relative w-20'">
                <AkArrowLeft :class="!isOpen ? 'bg-white text-indigo-900 text-2xl rounded-full absolute -right-3 top-9 border border-indigo-900 cursor-pointer rotate-180' : 'bg-white text-indigo-900 text-2xl rounded-full absolute -right-3 top-9 border border-indigo-900 cursor-pointe '" @click="toggleOpen" />
                <div class="inline-flex">
                    <PhFillContactlessPayment :class="!isOpen ? 'bg-amber-300 text-3xl rounded cursor-pointer block float-left duration-500':'bg-amber-300 text-3xl rounded cursor-pointer block float-left duration-500 rotate-180'"/>
                    <h1 :class="isOpen ? 'text-white origin-left font-medium text-2xl duration-300 ml-4' : 'scale-0'">PayjeeDisburse</h1>
                </div>

                <!---Menu-->
                <ul>
                    <li v-for="menu in menus" :key="menu.id" class="text-gray-300 text-sm flex items-center gap-x-4 cursor-pointer p-2 hover:bg-gray-400 rounded-md mt-2">
                        <span class="text-2xl block float-left">
                            <RaDashboard/>
                        </span>
                        <router-link :to="menu.route" :class="isOpen ? 'text-base font-medium flex-1' : 'hidden'">{{ menu.label }}</router-link>
                        <ul v-if="menu.submenu" class="submenu">
                            <CgChevronDown @click="toggleSubmenuOpen"/>
                            <li v-for="subitem in menu.submenuItems" :key="subitem.id">
                                <router-link :to="subitem.route">{{ subitem.label }}</router-link>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="p-7">
                <h1 class="text-2xl font-semibold">Homepage</h1>
            </div>
        </div>

        <!--<div class="ml-60 px-6 pt-6 2xl:container">-->
        <div class="ml-60 px-6 pt-6">
            <slot/>
        </div>

        
    </div>
</template>
