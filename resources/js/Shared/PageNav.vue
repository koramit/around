<template>
    <Link href="/">
        {{ __('home') }}
    </Link>
    |
    <Link href="/user/en">
        {{ __('user/en') }}
    </Link>
    |
    <Link href="/user/th">
        {{ __('user/th') }}
    </Link>
</template>

<script setup>
import { Link } from '@inertiajs/inertia-vue3';
</script>