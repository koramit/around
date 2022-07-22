<template>
    <!--        <button class="mt-4 md:mt-0 w-full btn btn-complement h-10">
            Export excel
        </button>-->
    <SlotReservationForm
        :configs="configs"
    />

    <FormDatetime
        label="select date"
        name="date_note"
        v-model="date_note"
        :options="{inline: true}"
        ref="dateNoteInput"
    />
    <div class="md:grid grid-cols-2 gap-4">
        <Transition
            name="slide-fade"
            v-if="slot.hd_unit.length"
        >
            <DialysisSlot
                :slots="slot.hd_unit"
            />
        </Transition>
        <Transition
            name="slide-fade"
            v-if="slot.ward.length"
        >
            <WardSlot
                :slots="slot.ward"
            />
        </Transition>
    </div>
</template>

<script setup>
import FormDatetime from '../../../Components/Controls/FormDatetime.vue';
import DialysisSlot from '../../../Partials/Procedures/AcuteHemodialysis/DialysisSlot.vue';
import WardSlot from '../../../Partials/Procedures/AcuteHemodialysis/WardSlot.vue';
import { reactive, ref, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import SlotReservationForm from '../../../Partials/Procedures/AcuteHemodialysis/SlotReservationForm.vue';
import CovidInfo from '../../../Components/Helpers/CovidInfo.vue';
import FallbackSpinner from '../../../Components/Helpers/FallbackSpinner.vue';
const props = defineProps({
    slot: { type: Object, required: true },
    configs: { type: Object, required: true },
});

const date_note = ref(props.slot.date_note);
watch(
    () => date_note.value,
    (val) => {
        /** @type {object} data */
        let data = {date_note: val};
        Inertia.get(location.pathname, data, { preserveState: true });
    }
);
</script>
