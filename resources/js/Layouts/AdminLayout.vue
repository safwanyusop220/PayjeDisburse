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


onMounted(() => {
    initFlowbite();
})

import { CgChevronDown } from "@kalimahapps/vue-icons";
import { PhFillContactlessPayment } from "@kalimahapps/vue-icons";
import { McBankFill } from "@kalimahapps/vue-icons";
import OldSidebar from './OldSidebar.vue';
import SidebarCheck from './SidebarCheck.vue';
import Sidebar from './Sidebar.vue';
import Navbar from './Navbar.vue';
import Footer from './Footer.vue';



const isOpen = ref(false);

const toggleOpen = () => {
  isOpen = !isOpen.value;
};

</script>

<template>
        <Navbar/>

        <Sidebar/>

        <!--<div class="ml-60 px-6 pt-6 2xl:container">-->
        <div class="ml-60 px-6 h-screen bg-primary-100">
            <slot/>
        </div>
        <!--<Footer/>-->
</template>
