<template>
    <div
        v-if="$page.props.flash.actionMenu.length"
        class="mb-4"
    >
        <template
            v-for="(action, key) in $page.props.flash.actionMenu.filter(a => a !== null && a.can)"
            :key="key"
        >
            <button
                v-if="action.action !== undefined"
                class="flex items-center group py-2 text-primary"
                @click="$emit(action.type ?? 'action-clicked', action.action)"
            >
                <IconVector
                    :name="action.icon"
                    class="w-4 h-4 group-hover:text-accent transition-colors duration-200 ease-in-out"
                />
                <span
                    class="ml-2 group-hover:text-accent transition-colors duration-200 ease-in-out truncate"
                    v-if="!zenMode"
                >
                    {{ action.label }}
                </span>
            </button>
            <InertiaLink
                class="flex items-center group py-2 outline-none truncate text-primary"
                :href="action.route"
                v-else-if="action.route !== undefined"
                @click="$emit('link-clicked')"
            >
                <IconVector
                    :name="action.icon"
                    class="w-4 h-4 group-hover:text-accent transition-colors duration-200 ease-in-out"
                />
                <div
                    class="ml-2 group-hover:text-accent transition-colors duration-200 ease-in-out"
                    v-if="!zenMode"
                >
                    {{ action.label }}
                </div>
            </InertiaLink>
        </template>
    </div>
</template>
<script setup>
import { InertiaLink } from '@inertiajs/inertia-vue3';
import IconVector from './IconVector.vue';
defineEmits(['action-clicked', 'link-clicked', 'subscribe-clicked', 'set-home-page-clicked', 'bookmark-clicked']);
defineProps({
    zenMode: { type: Boolean }
});
</script>
