<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { FlDocumentOnePageAdd } from "@kalimahapps/vue-icons";

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
    <Link :href="href" class="flex items-center px-3 py-1.5 mt-1 text-xxs text-white font-mono bg-primary-500 hover:bg-primary-400 rounded-md">
        <FlDocumentOnePageAdd class="text-lg mr-0.5" />
        <slot />

    </Link>
</template>
