<template>
    <div class="flex items-center justify-center">
        <template
            v-for="(action, key) in actions"
            :key="key"
        >
            <InertiaLink
                v-if="action.type === 'link'"
                :href="action.href"
                class="flex items-center m-2 group"
            >
                <span class="p-2 group-hover:bg-primary-darker rounded-full transition-colors duration-200 ease-in">
                    <IconVector
                        :name="action.icon"
                        :theme="action.theme"
                        class="w-4 h-4"
                    />
                </span>
                <!--{{ action.label }}-->
            </InertiaLink>
            <button
                v-else
                :key="key"
                class="flex items-center m-2 group"
                @click="$emit('action-clicked', action)"
            >
                <span class="p-2 group-hover:bg-primary-darker rounded-full transition-colors duration-200 ease-in">
                    <IconVector
                        :name="action.icon"
                        :theme="action.theme"
                        class="w-4 h-4"
                    />
                </span>
                <!--{{ action.label }}-->
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
