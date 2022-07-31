<template>
    <div v-if="links.length > 3">
        <div class="flex flex-wrap -mb-1 mt-4">
            <template v-for="(link, key) in links">
                <div
                    v-if="link.url === null"
                    :key="key"
                    class="mr-1 mb-1 px-4 py-3 text-sm leading-4 bg-gray-200 text-gray-400 border rounded cursor-not-allowed"
                    v-html="link.label"
                />
                <InertiaLink
                    v-else
                    :key="key+'theLink'"
                    class="mr-1 mb-1 px-4 py-3 text-sm text-complement-darker leading-4 border border-primary-darker rounded hover:bg-white focus:border-complement-darker focus:text-complement-darker transition-colors"
                    :class="{ 'bg-primary-darker cursor-not-allowed hover:bg-primary-darker': link.active }"
                    :href="link.url"
                    :as="'button'"
                    :disabled="link.active"
                >
                    <span v-html="link.label" />
                </InertiaLink>
            </template>
        </div>
    </div>
</template>

<script setup>
import {InertiaLink} from '@inertiajs/inertia-vue3';
defineProps({
    links: {type: Array, required: true}
});
</script>
