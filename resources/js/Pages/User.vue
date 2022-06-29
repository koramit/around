<template>
    <h1 class="text-3xl font-bold underline text-blue-700">
        {{ __('user') }}
    </h1>
    <PageNav />
</template>

<script setup>
import PageNav from '../Shared/PageNav.vue';
import { useManageLocale } from '../functions/useManageLocale.js';

const props = defineProps({
    trans: { type: Object, default: null }
});

useManageLocale(props.trans);
</script>