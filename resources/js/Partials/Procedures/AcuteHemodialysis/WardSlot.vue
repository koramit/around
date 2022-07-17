<template>
    <div>
        <p class="mt-1 italic text-xs text-accent">
            ๏ Not in any particular order
        </p>
        <TransitionGroup
            name="flip-list"
            class="mt-2 lg:mt-0 grid grid-cols-1 gap-2"
            tag="div"
        >
            <div
                class="w-full p-2 md:p-4 rounded shadow"
                :class="{
                    'bg-green-400 p-4 h-8': !slot.type,
                    'flex justify-between items-center': slot.type,
                    'text-complement-darker bg-amber-400': slot.status !== undefined && slot.status === 'scheduling',
                    'text-primary bg-complement': slot.status !== undefined && slot.status === 'performed',
                    'text-primary bg-red-400': slot.status !== undefined && slot.status !== 'scheduling' && slot.status !== 'performed',
                }"
                v-for="(slot, key) in slots"
                :key="key"
            >
                <template v-if="slot.type">
                    <div class="w=1/4">
                        <span class="p-1 md:p-2 bg-primary rounded-full text-xs text-accent font-semibold">{{ slot.type }}</span>
                    </div>
                    <div class="w-3/4 mt-1 mt-0 space-x-2 flex items-center">
                        <Link
                            class="font-semibold text-xs flex items-center"
                            :href="slot.case_record_route"
                        >
                            <IconPatient
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
