<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

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
        ? 'flex items-center p-2 text-gray-900 rounded-lg bg-primary-200'
        : 'flex items-center p-2 text-gray-900 rounded-lg'
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
