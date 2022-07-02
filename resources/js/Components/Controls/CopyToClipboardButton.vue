<template>
    <button
        class="inline-flex items-center text-accent"
        :data-clipboard-text="text"
        :id="id"
        @click="copyTextToClipboard"
    >
        <slot />
        <IconClipboardCopied
            v-if="notify"
            class="ml-1 w-5 h-5 text-complement"
        />
        <IconClipboard
            v-else
            class="ml-1 w-5 h-5"
        />
    </button>
</template>

<script setup>
import ClipboardJS from 'clipboard';
import {  ref } from 'vue';
import IconClipboard from '../Helpers/Icons/IconClipboard.vue';
import IconClipboardCopied from '../Helpers/Icons/IconClipboardCopied.vue';
defineProps({
    text: { type: [String, Number], default: 'around about arrange' },
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