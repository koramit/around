<template>
    <div>
        <p class="mt-1 italic text-xs text-accent">
            ๏ Not in any particular order
        </p>
        <transition-group
            name="flip-list"
            class="mt-2 lg:mt-0 grid grid-cols-1 gap-2"
            tag="div"
        >
            <div
                class="w-full p-2 md:p-4 rounded shadow"
                :class="{
                    'bg-red-300 flex justify-between items-center': slot.type,
                    'bg-green-300 p-4 h-8': !slot.type,
                }"
                v-for="(slot, key) in slots"
                :key="key"
            >
                <template v-if="slot.type">
                    <span class="p-1 md:p-2 bg-primary rounded-full text-xs text-accent font-semibold">{{ slot.type }}</span>
                    <div class="mt-1 md:mt-0 md:space-x-2 md:flex md:items-center">
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