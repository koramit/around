<template>
    <div
        v-if="$page.props.flash.actionMenu.length"
        class="mb-4"
    >
        <button
            class="flex items-center group py-2 text-soft-theme-light"
            v-for="(action, key) in $page.props.flash.actionMenu"
            :key="key"
            @click="$emit('action-clicked', action.action)"
        >
            <IconVector
                :name="action.icon"
                class="w-4 h-4 mr-2 group-hover:text-primary-darker transition-colors duration-200 ease-in-out"
            />
            <div
                class="group-hover:text-primary-darker transition-colors duration-200 ease-in-out"
                v-if="!zenMode"
            >
                {{ action.label }}
            </div>
        </button>
    </div>
</template>
<script setup>
import IconVector from './IconVector.vue';
defineEmits(['action-clicked']);
defineProps({
    zenMode: { type: Boolean }
});
</script>