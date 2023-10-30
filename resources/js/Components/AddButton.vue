<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { ClAddPlus } from "@kalimahapps/vue-icons";
import { MdOutlinedCreateNewFolder } from "@kalimahapps/vue-icons";
import { SuCreate } from "@kalimahapps/vue-icons";
const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'flex items-center text-gray-900 rounded-lg bg-primary-200'
        : 'flex items-center text-gray-900 rounded-lg'
);
</script>

<template>
    <Link :href="href" class="flex items-center px-3 text-xs text-white font-mono bg-primary-button hover:bg-primary-button-hover rounded-md">
        <SuCreate class="text-lg mr-0.5" />
        <slot />

    </Link>
</template>
