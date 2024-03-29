<template>
    <div>
        <p class="mt-1 italic text-xs text-accent">
            ๏ Not in any particular order
        </p>
        <TransitionGroup
            name="flip-list"
            class="mt-2 lg:mt-0 grid grid-cols-4 gap-2"
            tag="div"
        >
            <div
                class="w-full rounded shadow"
                :class="{
                    'col-span-3 p-2 md:px-4': slot.slot_count === 3,
                    'col-span-2 p-2 md:px-4': slot.slot_count === 2,
                    'col-span-1 p-2 md:px-4': slot.slot_count === 1,
                    'flex items-center': !slot.available,
                    'text-primary bg-green-400 p-8': slot.available,
                    'text-complement-darker bg-amber-400': slot.status !== undefined && slot.status === 'scheduling',
                    'text-primary bg-rose-900 animate-pulse': slot.status !== undefined && slot.status === 'started',
                    'text-primary bg-complement': slot.status !== undefined && slot.status === 'finished',
                    'text-primary bg-red-400': slot.status !== undefined && slot.status !== 'scheduling' && slot.status !== 'started' && slot.status !== 'finished',
                    'border-4 border-white border-dashed': slot.extra_slot
                }"
                v-for="(slot, key) in slots.acute"
                :key="key"
            >
                <template v-if="!slot.available">
                    <div class="w-1/3">
                        <Link :href="slot.order_route">
                            <span
                                class="p-1 md:p-2 rounded-full text-xs font-semibold underline"
                                :class="{
                                    'bg-primary text-accent': slot.status !== 'submitted',
                                    'bg-indigo-400 text-white': slot.status === 'submitted'
                                }"
                            >{{ slot.type }}</span>
                        </Link>
                    </div>
                    <div class="w-2/3 mt-1 md:mt-0">
                        <Link
                            class="font-semibold text-xs flex items-center"
                            :href="slot.case_record_route"
                        >
                            <IconVentilator
                                v-if="slot.on_ventilator"
                                class="h-3 w-3 mr-1 text-green-400"
                            />
                            <IconPatient
                                v-else
                                class="h-3 w-3 mr-1 text-white"
                            />
                            <span class="block py-1 italic truncate underline">{{ slot.patient_name }}</span>
                        </Link>
                        <p class="font-semibold text-xs flex items-center">
                            <IconUserMd
                                class="h-3 w-3 mr-1 text-white"
                            />
                            <span class="block py-1 italic truncate">{{ slot.author }}</span>
                        </p>
                        <p class="font-semibold text-xs flex items-center">
                            <IconUserMd
                                class="h-3 w-3 mr-1 text-white"
                            />
                            <span class="block py-1 italic truncate">{{ slot.attending }}</span>
                        </p>
                    </div>
                </template>
            </div>
        </TransitionGroup>
        <div v-if="slots.covid?.length">
            <label class="form-label my-2 md:my-4">COVID-19 Cases at Acute unit</label>
            <WardSlot
                :slots="slots.covid.map(s => ({...s}))"
            />
        </div>
        <div v-if="slots.chronic?.length">
            <label class="form-label my-2 md:my-4">Dialysis at Chronic unit</label>
            <WardSlot
                :slots="slots.chronic.map(s => ({...s}))"
            />
        </div>
    </div>
</template>

<script setup>
import IconPatient from '../../../Components/Helpers/Icons/IconPatient.vue';
import IconUserMd from '../../../Components/Helpers/Icons/IconUserMd.vue';
import { Link } from '@inertiajs/vue3';
import WardSlot from './WardSlot.vue';
import IconVentilator from '../../../Components/Helpers/Icons/IconVentilator.vue';

defineProps({
    slots: { type: Object, required: true }
});
</script>
