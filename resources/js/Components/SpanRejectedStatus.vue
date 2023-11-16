<script setup>
defineProps({
    value: {
        type: String,
    },
});
</script>

<template>
    <label>
        <div class="flex justify-center w-[90px] rounded-md bg-red-100 px-2.5 text-xxs-custom font-normal text-red-600"> {{ value }}</div>
    </label>
</template>
