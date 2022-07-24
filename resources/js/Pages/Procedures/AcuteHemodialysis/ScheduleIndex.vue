<template>
    <SlotReservationForm
        class="border-b-2 border-dashed pb-2 mb-2 md:pb-4 md:mb-4"
        :configs="configs"
    />

    <div class="md:flex items-end md:space-x-4">
        <FormDatetime
            label="reference date"
            name="ref_date"
            v-model="ref_date"
        />
        <button class="mt-4 md:mt-0 w-full btn btn-complement h-10">
            Export excel
        </button>
    </div>

    <h2 class="mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement">
        Acute dialysis unit
    </h2>
    <div class="grid grid-flow-col grid-rows-1 gap-4 xl:gap-8 overflow-x-scroll pb-2 md:pb-4 border-b-2 border-dashed border-complement">
        <div
            v-for="slot in slots"
            class="w-72 md:w-[22rem]"
            :key="slot.date_note"
        >
            <label class="font-medium text-complement italic">{{ slot.date_label }}</label>
            <DialysisSlot
                :slots="slot.hd_unit"
            />
        </div>
    </div>

    <h2 class="mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement">
        Ward
    </h2>
    <div class="grid grid-flow-col grid-rows-1 gap-4 xl:gap-8 overflow-x-scroll pb-2 md:pb-4 border-b-2 border-dashed border-complement">
        <div
            v-for="slot in slots"
            class="w-72 md:w-[22rem]"
            :key="slot.date_note"
        >
            <label class="font-medium text-complement italic">{{ slot.date_label }}</label>
            <WardSlot
                :slots="slot.ward"
            />
        </div>
    </div>

    <h2 class="mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement">
        COVID cases
    </h2>
    <div class="grid grid-flow-col grid-rows-1 gap-4 xl:gap-8 overflow-x-scroll">
        <div
            v-for="slot in slots"
            class="w-72 md:w-[22rem]"
            :key="slot.date_note"
        >
            <label class="font-medium text-complement italic">{{ slot.date_label }}</label>
            <CovidSlot
                :slots="slot.covid_cases"
            />
        </div>
    </div>
</template>

<script setup>
import FormDatetime from '../../../Components/Controls/FormDatetime.vue';
import DialysisSlot from '../../../Partials/Procedures/AcuteHemodialysis/DialysisSlot.vue';
import WardSlot from '../../../Partials/Procedures/AcuteHemodialysis/WardSlot.vue';
import { ref, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import SlotReservationForm from '../../../Partials/Procedures/AcuteHemodialysis/SlotReservationForm.vue';
import CovidSlot from '../../../Partials/Procedures/AcuteHemodialysis/CovidSlot.vue';
const props = defineProps({
    slots: { type: Array, required: true },
    configs: { type: Object, required: true },
});

const ref_date = ref(props.slots[3]?.date_note);
watch(
    () => ref_date.value,
    (val) => {
        /** @type {object} data */
        let data = {ref_date: val};
        Inertia.get(location.pathname, data, { preserveState: true, preserveScroll: true });
    }
);
</script>
