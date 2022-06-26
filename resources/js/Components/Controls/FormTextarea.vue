<template>
    <div class="w-full">
        <label
            v-if="label"
            class="form-label"
            :for="name"
        >{{ label }} :</label>
        <textarea
            :id="name"
            :name="name"
            ref="textarea"
            @input="oninput"
            @change="$emit('autosave')"
            :type="type"
            :placeholder="placeholder"
            :pattern="pattern"
            :readonly="readonly"
            :value="modelValue"
            class="form-input scroll-mt-12 md:scroll-mt-0"
            :class="{ 'border-red-400 text-red-400': error }"
        />
        <div
            v-if="error"
            class="text-red-700 mt-2 text-sm"
        >
            {{ error }}
        </div>
    </div>
</template>

<script setup>
import debounce from 'lodash/debounce';
import autosize from 'autosize';
import { onMounted, ref } from 'vue';

const emits = defineEmits(['autosave', 'update:modelValue']);
defineProps({
    modelValue: { type: String, default: '' },
    name: { type: String, required: true },
    label: { type: String, default: '' },
    type: { type: String, default: 'text' },
    placeholder: { type: String, default: '' },
    pattern: { type: String, default: '' },
    readonly: { type: Boolean },
    error: { type: String, default: '' },
});

const autosave = debounce(() => emits('autosave'), 2500);
const textarea = ref(null);
onMounted(() => {
    autosize(textarea.value);
});

const oninput = () => {
    emits('update:modelValue', textarea.value.value);
    autosave();
};
const focus = () => textarea.value.focus();

defineExpose({focus});
</script>