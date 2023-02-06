<template>
    <div class="flex items-center justify-center">
        <template
            v-for="(action, key) in actions"
            :key="key"
        >
            <Link
                v-if="action.as === 'link'"
                :href="action.route"
                class="md:m-1 flex items-center group"
                :class="{'m-2': actions.length > 1}"
            >
                <span
                    class="bg-primary-darker md:bg-white p-2 md:group-hover:bg-primary-darker rounded-full transition-colors duration-200 ease-in"
                >
                    <IconVector
                        :name="action.icon"
                        :theme="action.theme"
                        class="w-4 h-4"
                    />
                </span>
            </Link>
            <button
                v-else-if="action.as === 'button'"
                :key="key"
                class="md:m-2 flex items-center group"
                :class="{'m-2': actions.length > 1}"
                @click="$emit('action-clicked', action)"
            >
                <span
                    class="bg-primary-darker md:bg-white p-2 md:group-hover:bg-primary-darker rounded-full transition-colors duration-200 ease-in"
                >
                    <IconVector
                        :name="action.icon"
                        :theme="action.theme"
                        class="w-4 h-4"
                    />
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
