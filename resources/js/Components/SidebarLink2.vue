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
        ? 'flex items-center rounded-lg bg-primary-100'
        : 'flex items-center rounded-lg'
);
</script>

<template>
    <Link :href="href" :class="classes">
        <div class="w-full flex items-center text-blue-400 h-8 pl-4 rounded-lg cursor-pointer">
            <span class="text-gray-500 text-xs"><slot /></span>
        </div>
    </Link>
</template>
