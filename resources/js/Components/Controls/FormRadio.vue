<template>
    <div>
        <label
            v-if="label"
            class="form-label"
        >{{ label }} :</label>
        <div
            v-for="(item, key) in computeItems"
            :key="key"
            class="mb-2 flex text-gray-600 appearance-none w-full py-1 px-2 lg:py-2 lg:px-3 bg-white shadow-sm rounded border-2 transition-colors duration-200 ease-in-out cursor-pointer"
            :class="{
                'border-red-400': error,
                'border-gray-200': !selected && !error,
                'opacity-50': selected && selected !== item.value,
                'border-accent font-medium': selected === item.value && !error,
            }"
        >
            <div class="text-accent flex items-center">
                <input
                    :id="item.value+'-'+name"
                    type="radio"
                    class="shadow-sm h-5 w-5 transition-all duration-200 ease-in-out appearance-none inline-block align-middle rounded-full border border-complement-darker select-none shrink-0 cursor-pointer focus:outline-none"
                    :value="item.value"
                    :name="name"
                    v-model="selected"
                    :checked="item.value === selected"
                >
            </div>
            <label
                :for="item.value+'-'+name"
                v-text="item.label"
                class="ml-4 w-full block cursor-pointer"
            />
        </div>
        <p
            v-if="error"
            class="form-error-block mt-0"
        >
            {{ error }}
        </p>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { computed, watch } from 'vue';

const emits = defineEmits(['update:modelValue', 'autosave']);
const props = defineProps({
    modelValue: { type: [String, Number], default: '' },
    options: { type: Array, required: true },
    name: { type: String, required: true },
    label: { type: String, default: '' },
    disabled: { type: Boolean },
    error: { type: String, default: '' },
    allowReset: { type:Boolean },
    allowOther: { type:Boolean },
});

const selected = ref(props.modelValue);
watch (
    () => selected.value,
    (val) => {
        emits('update:modelValue', val);
        emits('autosave');
    },
);

const computeItems = computed(() => {
    let options = ['string', 'number'].includes(typeof props.options[0])
        ?   props.options.map( function (option) {
            return { value: option, label: option };
        })
        :   [...props.options];

    if (props.allowOther) {
        options.push({ label: 'Other', value: 'other' });
    }

    if (!props.allowReset || selected.value === null) {
        return options;
    } else {
        options.push({ label: 'Remove', value: null });
        return options;
    }
});

const setOther = (val) => {
    selected.value = val;
};

defineExpose({ setOther });
</script>