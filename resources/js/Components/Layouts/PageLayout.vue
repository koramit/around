<template>
    <Head>
        <title>{{ $page.props.flash.title }}</title>
    </Head>
    <div>
        <slot />
    </div>
</template>

<script setup>
import { Head } from '@inertiajs/inertia-vue3';
import { pageRoutines } from '@/functions/pageRoutines.js';
pageRoutines();
</script>