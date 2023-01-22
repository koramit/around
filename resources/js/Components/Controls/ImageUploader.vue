<template>
    <div>
        <div class="flex items-center">
            <p>
                <span class="form-label !mb-0 !inline">{{ label }}</span>
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
            <span v-if="modelValue">
                <button
                    class="ml-4"
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
                <button
                    class="ml-4"
                    @click="remove"
                    v-if="serviceEndpoints.destroy !== undefined"
                >
                    <IconTrashXMark
                        class="w-4 h-4 text-red-400"
                    />
                </button>
            </span>
        </div>
        <div
            v-if="error"
            class="text-red-700 text-sm"
        >
            {{ error }}
        </div>
        <transition name="slide-fade">
            <!--suppress JSUndeclaredVariable -->
            <img
                v-if="modelValue !== undefined && show"
                :src="`${serviceEndpoints.show}?path=${pathname}/${filename}`"
                @loadstart="busy = true"
                @load="$nextTick(() => busy = false)"
                alt=""
            >
        </transition>
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
import IconTrashXMark from '../Helpers/Icons/IconTrashXMark.vue';
const emits = defineEmits(['update:modelValue', 'autosave', 'removed']);
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
};

function remove() {
    busy.value = true;
    show.value = false;
    window.axios
        .delete(props.serviceEndpoints.destroy, {
            data: {path: props.pathname + '/' + props.modelValue}
        }).then(function () {
            filename.value = '';
            emits('update:modelValue', '');
            emits('autosave');
            emits('removed');
        }).catch(error => {
            console.log(error);
        }).finally(() => {
            busy.value = false;
        });
}
</script>
