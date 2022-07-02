<template>
    <div
        class="relative inline-block text-left"
        ref="dropdown"
    >
        <div
            class="fixed inset-0 z-10"
            @click="show = false"
            v-if="show"
        />
        <button
            @click="toggle"
            type="button"
        >
            <slot />
        </button>
        <Transition :name="dropup ? 'fade-appear-above':'fade-appear'">
            <div
                class="origin-top-right absolute right-0 w-auto rounded-md shadow-lg z-20"
                :class="{' -translate-y-full': dropup}"
                v-if="show"
            >
                <div @click.stop="show = autoClose ? false : true">
                    <slot name="dropdown" />
                </div>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { onMounted, onUnmounted, ref } from 'vue';

defineProps({
    autoClose: { type: Boolean, default: true }
});

const show = ref(false);
// const animate = ref(false);
const dropup = ref(false);
const dropupThreshold = ref(0.8);
const dropdown = ref(null);


const escapePressed = (e) => {
    if (e.keyCode === 27) {
        show.value = false;
    }
};

onMounted(() => document.addEventListener('keydown', escapePressed));
onUnmounted(() => document.removeEventListener('keydown', escapePressed));

const toggle = () => {
    if (!show.value) {
        dropup.value = (dropdown.value.offsetTop / (window.innerHeight + window.scrollY)) > dropupThreshold.value;
    }
    show.value = !show.value;
};
</script>

<style scoped>
    .fade-appear-enter-active {
        animation: fade-appear .2s;
    }
    .fade-appear-leave-active {
        animation: fade-appear .2s reverse;
    }
    @keyframes fade-appear {
        0% {
            transform: scale(0.9);
            opacity: 0;
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    .fade-appear-above-enter-active {
        animation: fade-appear-above .2s;
    }
    .fade-appear-above-leave-active {
        animation: fade-appear-above .2s reverse;
    }
    @keyframes fade-appear-above {
        0% {
            transform: scale(0.9);
            transform: translateY(0%);
            opacity: 0;
        }
        100% {
            transform: scale(1);
            transform: translateY(-120%);
            opacity: 1;
        }
    }
</style>