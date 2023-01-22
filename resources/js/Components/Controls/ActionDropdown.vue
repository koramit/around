<template>
    <div class="mt-2 py-0 overflow-hidden shadow-xl bg-complement text-white cursor-pointer rounded text-sm whitespace-nowrap">
        <template
            v-for="(action, key) in actions"
            :key="key"
        >
            <Link
                v-if="action.as === 'link'"
                :href="action.route"
                class="block w-full text-left px-4 py-2"
            >
                <span class="flex items-center space-x-2">
                    <IconVector
                        :name="action.icon"
                        class="w-4 h-4"
                    />
                    <span>{{ action.label }}</span>
                </span>
            </Link>
            <button
                v-else-if="action.as === 'button'"
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
import {Link} from '@inertiajs/vue3';
import IconVector from '../Helpers/IconVector.vue';
defineEmits(['action-clicked']);
defineProps({
    actions: { type: Array, required: true }
});
</script>
