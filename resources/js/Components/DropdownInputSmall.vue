<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    modelValue: {
        type: String,
        required: true,
    },
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
<input
    class="border-none bg-primary-50 text-xxs  focus:ring-primary-50 text-primary-700 rounded-xl h-[30px]"
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
    ref="input"
/>

</template>
