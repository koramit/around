<template>
    <div>
        <div class="space-y-2 md:space-y-4">
            <ImageUploader
                v-for="(upload, key) in uploads"
                :key="key"
                :name="`${name}_${key}`"
                v-model="uploads[key]"
                :service-endpoints="serviceEndpoints"
                :pathname="pathname"
                :label="`# ${key+1}`"
                @removed="remove(key)"
                @autosave="emits('update:modelValue', [...uploads])"
            />
        </div>
        <button
            class="mt-2 md:mt-4"
            @click="uploads.push(null)"
        >
            <IconPaperclip
                class="w-4 h-4 text-accent"
            />
        </button>
    </div>
</template>

<script setup>
import ImageUploader from './ImageUploader.vue';
import IconPaperclip from '../Helpers/Icons/IconPaperclip.vue';
import {nextTick, ref} from 'vue';
import {useInPageLinkHelpers} from '../../functions/useInPageLinkHelpers.js';

const props = defineProps({
    name: { type: String, required: true },
    pathname: { type: String, required: true },
    serviceEndpoints: { type: Object, required: true },
    modelValue: { type: Array, required: true },
});

const emits = defineEmits(['update:modelValue']);

const uploads = ref([...props.modelValue]);

const {smoothScroll} = useInPageLinkHelpers();
function remove(key) {
    let temp = [...uploads.value];
    temp.splice(key, 1);
    uploads.value = [];
    nextTick(() => {
        uploads.value = [...temp];
        emits('update:modelValue', [...uploads.value]);
        nextTick(() => smoothScroll('#'+props.name));
    });
}
</script>
