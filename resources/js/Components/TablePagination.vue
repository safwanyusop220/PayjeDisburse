<template>
    <template v-for="(link, key) in links" :key="key">
        <Link v-if="link.url === null" v-html="link.label" class="mb-1 mr-1 px-3 py-2 text-gray-400 text-xs leading-4 border rounded"/>
        <Link v-else :href="link.url" v-html="link.label" 
        class="mb-1 mr-1 px-3 py-2 text-primary-400 text-xs font-semibold leading-4 border rounded "
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