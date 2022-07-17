<template>
    <div class="mt-2 py-0 overflow-hidden shadow-xl bg-complement text-white cursor-pointer rounded text-sm whitespace-nowrap">
        <template
            v-for="(action, key) in actions"
            :key="key"
        >
            <InertiaLink
                v-if="action.type === 'link'"
                :href="action.href"
                class="block w-full text-left px-4 py-2"
            >
                <span class="flex items-center space-x-2">
                    <IconVector
                        :name="action.icon"
                        class="w-4 h-4"
                    />
                    <span>{{ action.label }}</span>
                </span>
            </InertiaLink>
            <button
                v-else
                :key="key"
                class="block w-full text-left px-4 py-2"
                @click="$emit('action-clicked', action)"
            >
                <span class="flex items-center space-x-2">
                    <IconVector
                        :name="action.icon"
                        class="w-4 h-4"
                    />
                    <span>{{ action.label }}</span>
                </span>
            </button>
        </template>
    </div>
</template>

<script setup>
import {InertiaLink} from '@inertiajs/inertia-vue3';
import IconVector from '../Helpers/IconVector.vue';
defineEmits(['action-clicked']);
defineProps({
    actions: { type: Array, required: true }
});
</script>
