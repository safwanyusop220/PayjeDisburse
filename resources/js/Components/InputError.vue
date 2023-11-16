<script setup>
defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <div v-show="message">
        <p class="text-xxs-custom ml-2 text-red-600">
            *{{ message }}
        </p>
    </div>
</template>
