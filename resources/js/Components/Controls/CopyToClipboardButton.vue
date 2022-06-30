<template>
    <button
        class="inline-flex items-center text-accent"
        :data-clipboard-text="text"
        :id="id"
        @click="copyTextToClipboard"
    >
        <slot />
        <IconVector
            name="copy"
            class="ml-1 w-4 h-4"
        />
        <transition name="slide-fade">
            <span
                class="ml-1 font-extralight italic text-accent-darker"
                v-if="notify"
            >{{ itemName }} Copied!</span>
        </transition>
    </button>
</template>

<script setup>
import ClipboardJS from 'clipboard';
import {  ref } from 'vue';
import IconVector from '../Helpers/IconVector.vue';
defineProps({
    text: { type: [String, Number], default: 'around about arrange' },
    itemName: { type: [String], default: '' },
});
const id = 'btn-' + (+ new Date()) + Math.floor(Math.random() * 10000);
const notify = ref(false);
const copyTextToClipboard = () => {
    let clipboard = new ClipboardJS(`#${id}`);
    clipboard.on('success', () => {
        notify.value = true;
        clipboard.destroy();
        setTimeout(() => notify.value = false, 3000);
    });
};
</script>