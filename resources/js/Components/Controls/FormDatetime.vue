<template>
    <div class="w-full">
        <label
            class="form-label"
            :for="name"
        >{{ label }} :</label>
        <input
            :id="name"
            :name="name"
            ref="input"
            type="date"
            :placeholder="placeholder"
            :disabled="disabled"
            readonly
            :value="modelValue"
            class="form-input"
        >
        <div
            v-if="error"
            class="text-red-700 mt-2 text-sm"
        >
            {{ error }}
        </div>
    </div>
</template>

<script setup>
import 'flatpickr/dist/themes/light.css';
import flatpickr from 'flatpickr';
import { onMounted, ref, watch } from 'vue';

const emits = defineEmits(['autosave', 'update:modelValue']);

const props = defineProps({
    modelValue: { type: String, default: '' },
    name: { type: String, required: true },
    label: { type: String, default: '' },
    mode: { type: String, default: 'date' },
    placeholder: { type: String, default: '' },
    format: { type: String, default: 'F j, Y' }, // format for display date
    options: { type: Object, default: () => {} },
    error: { type: String, default: '' },
    disabled: { type: Boolean },
});

const onChange = (selectedDates, dateStr) => {
    // update model
    emits('update:modelValue', dateStr);
    // Emit autosave if field name available
    emits('autosave');
};

const flatpickrOptions = {
    date: { // init flatpickr instance
        altInput: true,
        altFormat: props.format,
        onChange: onChange,
        defaultDate: props.modelValue ?? '',
    },
    time: {
        enableTime: true,
        noCalendar: true,
        dateFormat: 'H:i',
        minTime: '07:00',
        maxTime: '21:00',
        time_24hr: true,
        minuteIncrement: 30,
        onChange: onChange,
    }
};

if (props.options !== undefined) {
    flatpickrOptions[props.mode] = {... flatpickrOptions[props.mode], ...props.options};
}

const input = ref(null);
let fp;
onMounted(() => {
    fp = flatpickr(input.value, flatpickrOptions[props.mode]);
});

const disabled = ref(false);
const error = ref(null);
watch(
    () => disabled.value,
    (val) => {
        fp._input.disabled = val;
    }
);
watch(
    () => error.value,
    (val) => {
        if (val) {
            fp._input.classList.add('border-red-400', 'text-red-400');
        } else {
            fp._input.classList.remove('border-red-400', 'text-red-400');
        }
    }
);
const setDate = (date) => {
    fp.setDate(date);
    // update model
    emits('update:modelValue', input.value.value);
    // Emit autosave if field name available
    emits('autosave');
};
const clear = () => {
    fp.clear();
    // update model
    emits('update:modelValue', input.value.value);
    // Emit autosave if field name available
    emits('autosave');
};

defineExpose({ setDate, clear });
</script>

<style>
    .calendar-event {
        position: absolute;
        width: 3px;
        height: 3px;
        border-radius: 150px;
        bottom: 3px;
        left: calc(50% - 1.5px);
        content: "•";
        display: block;
        background: #3d8eb9;
    }

    .calendar-event.busy {
        background: #f64747;
    }
</style>