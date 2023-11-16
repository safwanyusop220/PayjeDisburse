<script setup>
import { onMounted, ref } from 'vue';
import MoneySpinner from 'v-money-spinner'

defineProps({
    modelValue: {
        type: String,
        required: true,
    },
});

defineEmits(['update:modelValue']);

const input = ref(null);

defineExpose({ focus: () => input.value.focus() });
const config = {
  spinner: false,
  step: 10,
  min: 0,
  max: 9999999999,
  precision: 2,
  decimal: ".",
  thousands: ",",
  masked: false,
  disableNegative: true,
  align: "start",
  prefix: "RM "
};
</script>

<template>
    <v-money-spinner
        id="total_allocation"
        class="w-2/3 rounded-xl text-xs bg-primary-50 border border-none focus:ring-0 focus:border-blue-500"
        :value="modelValue"
        v-bind="config"
        @input="$emit('update:modelValue', $event.target.value)">
    </v-money-spinner>
</template>
