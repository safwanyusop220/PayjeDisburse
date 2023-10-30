<script setup>
import { Link } from '@inertiajs/vue3';
import { BsSendCheck } from "@kalimahapps/vue-icons";
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
        class="flex w-2/5 px-5 py-2.5 text-xs font-mono bg-need-approval-button text-white rounded-md hover:bg-blue-700"
        >
        <BsSendCheck class="text-lg mr-2"/>Need Approval
        <slot />
    </Link>
</template>
