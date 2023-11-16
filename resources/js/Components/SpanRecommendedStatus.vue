<script setup>
defineProps({
    value: {
        type: String,
    },
});
</script>

<template>
    <label>
        <div class="flex justify-center w-[90px] rounded-md bg-yellow-100 px-2.5 text-xxs-custom font-normal text-yellow-500"> {{ value }}</div>
    </label>
</template>
