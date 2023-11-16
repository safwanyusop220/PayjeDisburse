<script setup>
defineProps({
    value: {
        type: String,
    },
});
</script>

<template>
    <label class="block font-bold text-xs mt-2 ml-3 text-black">
        <span v-if="value">RM {{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
