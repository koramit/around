<template>
    <div>
        <p class="mt-1 italic text-xs text-accent">
            ๏ Not in any particular order
        </p>
        <transition-group
            name="flip-list"
            class="mt-2 lg:mt-0 grid grid-cols-4 gap-2"
            tag="div"
        >
            <div
                class="w-full rounded shadow"
                :class="{
                    'col-span-3 py-4 px-4': slot.slot_count === 3,
                    'col-span-2 py-4 px-4': slot.slot_count === 2,
                    'col-span-1 py-4 px-2': slot.slot_count === 1,
                    'bg-red-300 md:flex justify-between items-center': !slot.available,
                    'bg-green-300 p-8': slot.available,
                }"
                v-for="(slot, key) in slots"
                :key="key"
            >
                <template v-if="!slot.available">
                    <span class="p-1 md:p-2 bg-primary rounded-full text-xs text-accent font-semibold">{{ slot.type }}</span>
                    <div class="mt-1 md:mt-0">
                        <Link
                            class="font-semibold text-primary text-xs flex items-center"
                            :href="slot.case_record_route"
                        >
                            <IconPatient
                                class="h-3 w-3 mr-1 text-white"
                            />
                            <span class="block py-1 italic truncate underline">{{ slot.patient_name }}</span>
                        </Link>
                        <p class="font-semibold text-primary text-xs flex items-center">
                            <IconUserMd
                                class="h-3 w-3 mr-1 text-white"
                            />
                            <span class="block py-1 italic truncate">{{ slot.author }}</span>
                        </p>
                        <p class="font-semibold text-primary text-xs flex items-center">
                            <IconUserMd
                                class="h-3 w-3 mr-1 text-white"
                            />
                            <span class="block py-1 italic truncate">{{ slot.attending }}</span>
                        </p>
                    </div>
                </template>
            </div>
        </transition-group>
    </div>
</template>

<script setup>
import IconPatient from '@/Components/Helpers/Icons/IconPatient.vue';
import IconUserMd from '@/Components/Helpers/Icons/IconUserMd.vue';
import { Link } from '@inertiajs/inertia-vue3';
defineProps({
    slots: { type: Array, required: true }
});
</script>