<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <Link
        :type="type"
        class="mr-1 px-5 py-2 text-md text-white font-semibold bg-gray-300 hover:bg-gray-400 rounded-lg"
    >
        <slot />
    </Link>
</template>
