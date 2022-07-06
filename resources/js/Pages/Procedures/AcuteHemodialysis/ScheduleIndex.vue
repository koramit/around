<template>
    <div class="md:grid grid-cols-2 gap-4">
        <FormDatetime
            name="date_note"
            v-model="date_note"
            :options="{inline: true}"
            ref="dateNoteInput"
        />
        <button class="mt-4 md:mt-0 w-full btn btn-complement h-10">
            Export excel
        </button>
    </div>
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
import { ref, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
const props = defineProps({
    slot: { type: Object, required: true },
});
const date_note = ref(props.slot.date_note);
watch(
    () => date_note.value,
    (val) => Inertia.get(location.pathname, {date_note: val}, { preserveState: true }),
);
</script>