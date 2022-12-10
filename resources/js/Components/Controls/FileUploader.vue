<template>
    <div :id="name">
        <input
            type="file"
            class="hidden"
            @change="fileChanged"
            ref="fileInput"
            accept="application/pdf, image/jpeg, image/png"
            :name="name"
        >
        <div class="flex items-center">
            <span class="form-label !mb-0">{{ label }}</span>
            <IconCircleNotch
                class="ml-2 w-4 h-4 inline-block opacity-25 animate-spin"
                v-if="busy"
            />
            <button
                v-else
                @click="fileInput.click()"
            >
                <IconPaperclip class="ml-2 w-4 h-4" />
            </button>
            <a
                class="ml-2"
                v-if="modelValue"
                target="_blank"
                :href="`${serviceEndpoints.show}?path=${pathname}/${filename}`"
            >
                <IconEyes class="w-4 h-4 text-dark-theme-light" />
            </a>
        </div>
        <div
            v-if="error"
            class="text-red-700 text-sm"
        >
            {{ error }}
        </div>
    </div>
</template>

<script setup>
import {ref} from 'vue';
import IconCircleNotch from '../Helpers/Icons/IconCircleNotch.vue';
import IconPaperclip from '../Helpers/Icons/IconPaperclip.vue';
import IconEyes from '../Helpers/Icons/IconEyes.vue';

const emits = defineEmits(['update:modelValue', 'autosave']);
const props = defineProps({
    modelValue: { type: String, default: '' },
    label: { type: String, required: true },
    pathname: { type: String, required: true },
    error: { type: String, default: '' },
    readonly: { type: Boolean, },
    serviceEndpoints: { type: Object, required: true },
    name: { type: String, required: true },
});

const fileInput = ref(null);
const busy = ref(false);
const filename = ref(props.modelValue);

const fileChanged = (event) => {
    const file = event.target.files[0];
    if (!file) {
        return;
    }
    const formData = new FormData();
    formData.append('file', file);
    formData.append('pathname', props.pathname);
    formData.append('old', props.modelValue);
    busy.value = true;
    window.axios
        .post(props.serviceEndpoints.store, formData)
        .then(response => {
            filename.value = response.data.filename;
            emits('update:modelValue', response.data.filename);
            emits('autosave');
        }).catch(error => {
            console.log(error);
        }).finally(() => {
            busy.value = false;
        });
};

</script>
