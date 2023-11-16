<script setup>
defineProps({
    value: {
        type: String,
    },
});
</script>

<template>
    <label class="flex flex-col items-center justify-center font-bold text-xs mt-2 text-primary-700">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
