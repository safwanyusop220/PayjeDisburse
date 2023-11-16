<script setup>
defineProps({
    value: {
        type: String,
    },
});
</script>

<template>
    <label>
        <span class="flex justify-center w-[90px] rounded-md bg-blue-100 px-2.5 text-xxs font-normal text-blue-700"> {{ value }}</span>
    </label>
</template>
