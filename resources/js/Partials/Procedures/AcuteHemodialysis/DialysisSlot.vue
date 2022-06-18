<template>
    <div>
        <p class="mt-1 italic text-xs text-bitter-theme-light">
            ๏ Not in any particular order
        </p>
        <transition-group
            name="flip-list"
            class="mt-2 lg:mt-0 grid grid-cols-4 gap-2"
            tag="div"
        >
            <div
                class="w-full p-2 md:p-4 rounded shadow"
                :class="{
                    'col-span-4': slot.slotColSpan === 4,
                    'col-span-3': slot.slotColSpan === 3,
                    'col-span-2': slot.slotColSpan === 2,
                    'col-span-1': slot.slotColSpan === 1,
                    'bg-red-300 md:flex justify-between items-center': !slot.available,
                    'bg-green-300 p-4': slot.available,
                }"
                v-for="(slot, key) in slots"
                :key="key"
            >
                <template v-if="!slot.available">
                    <span class="p-1 md:p-2 bg-soft-theme-light rounded-full text-xs text-bitter-theme-light font-semibold">{{ slot.type }}</span>
                    <div class="mt-1 md:mt-0 space-y-1 md:space-y-2">
                        <Link
                            class="font-semibold text-soft-theme-light text-xs flex items-center"
                            :href="route('procedures.acute-hemodialysis.edit', slot.case_record_slug)"
                        >
                            <IconPatient
                                class="h-3 w-3 mr-1 text-white"
                            />
                            <span class="block italic truncate underline">{{ slot.patient_name }}</span>
                        </Link>
                        <p class="font-semibold text-soft-theme-light text-xs flex items-center">
                            <IconUserMd
                                class="h-3 w-3 mr-1 text-white"
                            />
                            <span class="block italic truncate">{{ slot.author }}</span>
                        </p>
                    </div>
                </template>
            </div>
        </transition-group>
    </div>
</template>

<script setup>
import IconPatient from '@/Components/Helpers/Icons/IconPatient';
import IconUserMd from '@/Components/Helpers/Icons/IconUserMd';
import { computed } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';

const props = defineProps({
    reservedSlots: { type: Array, required: true }
});

const slots = computed(() => {
    let slotGroup = [[], [], [], [], []];
    let unavailableSlotsCount = 0;

    props.reservedSlots.forEach(s => {
        s.slotColSpan = s.slot_count;
        s.type = s.type.split(' ')[0];
        s.available = false;
        slotGroup[s.slotColSpan].push(s);
        unavailableSlotsCount += s.slotColSpan;
    });

    let availableSlotsCount = 32 - unavailableSlotsCount;
    for(let i = 1;  i <= availableSlotsCount; i++) {
        slotGroup[1].push({ slotColSpan: 1, available: true });
    }

    let rearrangeSlots = [];
    for(let i = slotGroup.length - 1 ; i > 0; i--) {
        slotGroup[i].forEach((slot) => {
            rearrangeSlots.push(slot);
            if (slot.slotColSpan === 4) {
                return;
            } else if (slot.slotColSpan === 3) {
                if (slotGroup[1].length) {
                    rearrangeSlots.push(slotGroup[1].shift());
                }
            } else if (slot.slotColSpan === 2) {
                if (slotGroup[2].length > 2) {
                    let tmpSlot = slotGroup[2].pop();
                    rearrangeSlots.push(tmpSlot);
                } else if (slotGroup[1].length) {
                    rearrangeSlots.push(slotGroup[1].shift());
                    rearrangeSlots.push(slotGroup[1].shift());
                }
            }
        });
    }

    return rearrangeSlots.reverse();
});
</script>