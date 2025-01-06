<template>
    <span
        class="italic text-sm md:text-base py-1 px-2 md:px-3 rounded-2xl"
        :class="{
            'border border-slate-200 md:border-none  md:bg-slate-200 md:text-slate-700': theme === 'light',
            'border border-green-400 md:border-none md:bg-green-400 md:text-white': theme === 'success',
            'border border-slate-700 md:border-none  md:bg-slate-700 md:text-gray-200': theme === 'dark',
            'border border-yellow-400 md:border-none  md:bg-yellow-400 md:text-white': theme === 'warning',
        }"
        v-text="status"
    />
</template>

<script setup>

import {computed} from 'vue';

const props = defineProps({
    status: { type: String, required: true }
});

const theme = computed(() => {
    switch (props.status) {
    case 'draft':
    case 'pending':
    case 'loss f/u':
        return 'light';
    case 'submitted':
    case 'published':
    case 'approved':
    case 'completed':
    case 'active':
        return 'success';
    case 'canceled':
    case 'deleted':
    case 'offed':
    case 'dead':
        return 'dark';
    case 'disapproved':
    case 'scheduling':
    case 'edited':
    case 'graft loss':
        return 'warning';
    default:
        return 'light';
    }
});
</script>
