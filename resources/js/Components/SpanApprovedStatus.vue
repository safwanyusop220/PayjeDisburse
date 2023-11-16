<script setup>
defineProps({
    value: {
        type: String,
    },
});
</script>

<template>
    <label>
        <div class="flex justify-center w-[90px] rounded-md bg-green-100 px-2.5 text-xxs-custom font-normal text-green-500"> {{ value }}</div>
    </label>
</template>
