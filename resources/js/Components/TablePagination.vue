<template>
    <template v-for="(link, key) in links" :key="key">
        <Link v-if="link.url === null" v-html="link.label" class="mb-1 mr-1 px-4 py-3 text-gray-400 text-sm leading-4 border rounded"/>
        <Link v-else :href="link.url" v-html="link.label" 
        class="mb-1 mr-1 px-4 py-3 text-primary-400 font-semibold text-sm leading-4 border rounded focus:text-primary-500 focus:border-primary-500"
        :class="{'bg-primary-100 text-primary-700': link.active}"
        />
    </template>
</template>


<script setup>
import { Link } from '@inertiajs/vue3';
import { defineProps } from "vue";

defineProps({
    links: Array
})
</script>