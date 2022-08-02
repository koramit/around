<template>
    <SlotReservationForm
        class="border-b-2 border-dashed pb-2 mb-2 md:pb-4 md:mb-4"
        :configs="configs"
        v-if="configs.can.create_order"
    />

    <div class="md:flex items-end md:space-x-4">
        <FormDatetime
            label="reference date"
            name="ref_date"
            v-model="queryParams.ref_date"
        />
        <a
            class="mt-4 md:mt-0 w-full btn btn-complement h-10 text-center"
            :href="configs.routes.orders_export"
        >
            Export excel
        </a>
    </div>

    <FormCheckbox
        class="mt-6 md:mt-12 xl:mt-24"
        :toggler="true"
        v-model="queryParams.full_week"
        label="Full week"
    />
    <h2 class="mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement">
        Acute dialysis unit
    </h2>

    <div
        class="grid grid-flow-col grid-rows-1 gap-4 xl:gap-8 overflow-x-scroll pb-2 md:pb-4 border-b-2 border-dashed border-complement"
    >
        <div
            v-for="slot in slots"
            class="w-[21rem] sm:w-[22-rem] md:w-[24rem]"
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
            class="w-[21rem] sm:w-[22-rem] md:w-[24rem]"
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
            class="w-[21rem] sm:w-[22-rem] md:w-[24rem]"
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
import {reactive, watch} from 'vue';
import { Inertia } from '@inertiajs/inertia';
import SlotReservationForm from '../../../Partials/Procedures/AcuteHemodialysis/SlotReservationForm.vue';
import CovidSlot from '../../../Partials/Procedures/AcuteHemodialysis/CovidSlot.vue';
import FormCheckbox from '../../../Components/Controls/FormCheckbox.vue';
import pickBy from 'lodash/pickBy.js';
const props = defineProps({
    query: { type: Object, required: true },
    slots: { type: Array, required: true },
    configs: { type: Object, required: true },
});

const queryParams = reactive({
    ref_date: props.query.ref_date ?? null,
    full_week: (props.query.full_week ?? false) === 'on'
});

watch(
    () => queryParams,
    (val) => {
        let query = pickBy(val);
        query = Object.keys(query).length ? query : { remember: 'forget' };
        if (query.full_week !== undefined) {
            query.full_week = 'on';
        }
        Inertia.get(location.pathname, query, { preserveState: true, preserveScroll: true });
    },
    {deep: true}
);
</script>
