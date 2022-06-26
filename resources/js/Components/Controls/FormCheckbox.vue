<template>
    <div v-if="toggler">
        <!-- Toggle Button -->
        <label
            class="inline-flex items-center cursor-pointer scroll-mt-12 md:scroll-mt-0"
            :id="name ?? null"
        >
            <!-- toggle -->
            <div class="relative">
                <!-- input -->
                <input
                    type="checkbox"
                    class="hidden"
                    @change="change"
                >
                <!-- line -->
                <div
                    class="w-8 h-5 rounded-full shadow-inner transition-all duration-200 ease-in-out"
                    :class="{
                        'bg-accent-darker' : modelValue,
                        'bg-gray-200' : !modelValue,
                    }"
                />
                <!-- dot -->
                <div
                    class="absolute w-5 h-5 bg-white rounded-full shadow inset-y-0 left-0 transition-all duration-200 ease-in-out transform"
                    :class="{ 'translate-x-3' : modelValue }"
                />
            </div>
            <!-- label -->
            <div class="ml-3 text-sm md:text-base xl:text-lg">
                {{ label }}
            </div>
        </label>
        <p
            v-if="error"
            class="form-error-block"
        >
            {{ error }}
        </p>
    </div>
    <div v-else>
        <label
            class="inline-flex items-center cursor-pointer scroll-mt-12 md:scroll-mt-0"
            :id="name ?? null"
        >
            <input
                type="checkbox"
                class="shadow-xs h-6 w-6 transition-all duration-200 ease-in-out appearance-none color inline-block align-middle border border-gray-400 select-none shrink-0 rounded cursor-pointer focus:outline-none"
                :checked="modelValue"
                @change="change"
            >
            <span class="ml-4 text-sm md:text-base xl:text-lg">{{ label }}</span>
        </label>
        <p
            v-if="error"
            class="form-error-block"
        >
            {{ error }}
        </p>
    </div>
</template>

<script setup>
const emits = defineEmits(['update:modelValue', 'autosave']);
const props = defineProps({
    modelValue: { type: Boolean },
    name: { type: String, default: '' },
    label: { type: String, default: '' },
    error: { type: String, default: '' },
    toggler: { type: Boolean }
});
const change = () => {
    emits('update:modelValue', !props.modelValue);
    emits('autosave');
};
const check = () => {
    emits('update:modelValue', !props.modelValue);
};
defineExpose({ check });
</script>