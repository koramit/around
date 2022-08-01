<template>
    <div>
        <div class="flex items-center">
            <p>
                <span class="form-label !mb-0">{{ label }}</span>
                <IconCircleNotch
                    class="ml-1 w-4 h-4 inline-block opacity-25 animate-spin"
                    v-if="busy"
                />
            </p>
            <button
                v-if="!readonly"
                class="ml-4"
                @click="useCamera.click()"
            >
                <IconCamera class="w-4 h-4 text-thick-theme-light" />
            </button>
            <button
                v-if="!readonly"
                class="ml-4"
                @click="useGallery.click()"
            >
                <IconImage class="w-4 h-4 text-thick-theme-light" />
            </button>
            <button
                class="ml-4"
                v-if="modelValue"
                @click="show = !show"
            >
                <IconEyesSlash
                    v-if="show"
                    class="w-4 h-4 text-dark-theme-light"
                />
                <IconEyes
                    v-else
                    class="w-4 h-4 text-dark-theme-light"
                />
            </button>
        </div>
        <div
            v-if="error"
            class="text-red-700 text-sm"
        >
            {{ error }}
        </div>
        <!-- route('uploads.show', {path: name, filename: filename }) -->
        <img
            v-if="modelValue !== undefined && show"
            :src="`${serviceEndpoints.show}?path=${pathname}/${filename}`"
            @loadstart="busy = true"
            @load="$nextTick(() => busy = false)"
            alt=""
        >
        <input
            class="hidden"
            type="file"
            ref="useCamera"
            @input="fileInput"
            capture="environment"
            accept="image/*"
        >
        <input
            class="hidden"
            type="file"
            ref="useGallery"
            @input="fileInput"
            accept="image/*"
        >
    </div>
</template>

<script setup>
import IconCircleNotch from '../Helpers/Icons/IconCircleNotch.vue';
import IconCamera from '../Helpers/Icons/IconCamera.vue';
import IconImage from '../Helpers/Icons/IconImage.vue';
import IconEyesSlash from '../Helpers/Icons/IconEyesSlash.vue';
import IconEyes from '../Helpers/Icons/IconEyes.vue';
import {ref} from 'vue';
const emits = defineEmits(['update:modelValue', 'autosave']);
const props = defineProps({
    modelValue: { type: String, default: '' },
    label: { type: String, required: true },
    pathname: { type: String, required: true },
    error: { type: String, default: '' },
    readonly: { type: Boolean, },
    serviceEndpoints: { type: Object, required: true }
});

const useCamera = ref(null);
const useGallery = ref(null);
const show = ref(false);
const busy = ref(false);
const filename = ref(props.modelValue);
const fileInput = (event) => {
    busy.value = true;
    let form = new FormData();
    form.append('file', event.target.files[0]);
    form.append('pathname', props.pathname);
    form.append('old', props.modelValue);
    window.axios
        .post(props.serviceEndpoints.store, form)
        .then(response => {
            filename.value = response.data.filename;
            emits('update:modelValue', response.data.filename);
            emits('autosave');
            if (!show.value) {
                show.value = true;
            }
        }).catch(error => {
            console.log(error);
        }).finally(() => {
            busy.value = false;
        });
    console.log(event.target.files[0]);
};
</script>
