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
</script>