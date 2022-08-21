<template>
    <!-- backdrop -->
    <div
        v-show="show"
        class="inset-0 z-30 fixed flex items-center justify-center backdrop"
        :class="{ 'open': animate }"
    >
        <!-- modal -->
        <div
            v-if="show"
            class="bg-primary rounded shadow p-4 md:p-8 xl:p-10 modal-appear-from-top"
            :class="{
                'open': animate,
                'w-11/12 md:10/12': widthMode == 'document',
                'w-11/12 sm:10/12 md:w-3/5 xl:w-2/5': widthMode == 'form-cols-1',
            }"
        >
            <!-- header -->
            <div class="flex justify-between items-center">
                <div><slot name="header" /></div>
                <button
                    @click="close()"
                    class="block p-2 rounded-full hover:bg-white bg-primary transition-colors ease-in-out duration-200"
                >
                    <IconTimes class="w-4 h-4" />
                </button>
            </div>
            <!-- body -->
            <div><slot name="body" /></div>
            <!-- footer -->
            <div><slot name="footer" /></div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import IconTimes from './Icons/IconTimes.vue';

const emit = defineEmits(['opened', 'closed']);

defineProps({
    widthMode: { type: String, default: 'document' }
});

const show = ref(false);
const animate = ref(false);

const doubleRequestAnimationFrame =  (callback) => {
    requestAnimationFrame(() => {
        requestAnimationFrame(callback);
    });
};
const openTransitionEnd = (event) => {
    if (event.target.tagName == 'DIV' && event.propertyName == 'transform') {
        emit('opened');
        document.removeEventListener('transitionend', openTransitionEnd);
    }
};
const closeTransitionEnd = (event) => {
    if (event.target.tagName === 'DIV' && event.propertyName === 'transform') {
        emit('closed');
        show.value = false;
        document.removeEventListener('transitionend', closeTransitionEnd);
    }
};

const open = () => {
    document.addEventListener('transitionend', openTransitionEnd);
    show.value = true;

    // wait for dom ready
    doubleRequestAnimationFrame(() => {
        animate.value = true;
    });
};
const close =  () => {
    document.addEventListener('transitionend', closeTransitionEnd);
    animate.value = false;
};

defineExpose({open, close});
</script>

<style scoped>
    .modal-appear-from-top {
        opacity: 0;
        transform: translateY(-100%);
        transition: 0.2s all ease;
    }
    .modal-appear-from-top.open {
        opacity: 1;
        transform: translateY(0);
    }
    .modal-appear {
        opacity: 0;
        transform: scale(0);
        transition: 0.2s all ease;
    }
    .modal-appear.open {
        opacity: 1;
        transform: scale(1);
    }
    .backdrop {
        background-color: rgba(0, 0, 0, 0.0);
        transition: 0.2s background-color ease;
    }
    .backdrop.open {
        background-color: rgba(0, 0, 0, 0.25);
    }
</style>
