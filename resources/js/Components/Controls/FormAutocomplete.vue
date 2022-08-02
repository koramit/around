<template>
    <div class="relative">
        <div
            class="fixed inset-0 z-10"
            @click="open = false"
            v-if="open"
        />
        <div class="w-full">
            <label
                v-if="label"
                class="form-label"
                :for="name"
            >{{ label }} : </label>
            <div class="relative">
                <input
                    type="text"
                    class="form-input"
                    @input="search"
                    :id="name"
                    :name="name"
                    ref="input"
                    :value="modelValue"
                    :class="{ 'border-red-400 text-red-400': error }"
                    :disabled="disabled"
                >
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                        />
                    </svg>
                </div>
            </div>
            <div
                v-if="error"
                class="text-red-700 mt-2 text-sm"
            >
                {{ error }}
            </div>
        </div>
        <Transition name="fade-appear">
            <div
                class="absolute mt-1 bg-white rounded border-2 border-yellow-200 shadow-xl w-full max-h-44 py-2 overflow-y-scroll z-20 origin-top"
                :class="{ 'scale-100 opacity-100': open }"
                v-if="open"
            >
                <button
                    class="block w-full py-1 px-2 lg:px-3 hover:bg-primary hover:text-accent text-left"
                    v-for="(item, key) in items"
                    :key="key"
                    @click="selectItem(item)"
                >
                    {{ item }}
                </button>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import throttle from 'lodash/throttle';
const emits = defineEmits(['update:modelValue', 'autosave']);
const props = defineProps({
    modelValue: { type: String, default: '' },
    modelKey: { type: String, default: '' },
    label: { type: String, default: '' },
    endpoint: { type: String, default: '' },
    params: { type: String, default: '' },
    name: { type: String, required: true },
    error: { type: String, default: '' },
    lengthToStart: { type: Number, default: 3},
    disabled: {type: Boolean}
});

const items = ref([]);
const input = ref(null);
const open = ref(false);
const search = throttle(function () {
    emits('update:modelValue', input.value.value);

    if (input.value.value.length < props.lengthToStart) {
        if (open.value) {
            open.value = false;
        }

        if (! input.value.value) {
            emits('autosave');
        }

        return;
    }
    window.axios
        .get(props.endpoint + '?search=' + input.value.value + props.params)
        .then(response => {
            items.value = response.data.length ? response.data : ['No match found'];
            open.value = true;
        }).catch(error => {
            console.log(error);
        });
}, 300);

const selectItem = (item) => {
    input.value.value = item;
    open.value = false;
    emits('update:modelValue', item);
    emits('autosave');
};
</script>
