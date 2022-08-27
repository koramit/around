<template>
    <div
        role="alert"
        class="border-t-4 rounded-b p-4 shadow-sm bg-white border-yellow-400"
    >
        <div class="flex items-center text-yellow-400">
            <div class="w-1/6 flex justify-center">
                <IconInfoCircle class="w-6 h-6 md:w-10 md:h-10 lg:w-16 lg:h-16" />
            </div>
            <div class="w-5/6 lg:text-lg">
                <p class="font-semibold">
                    Serology
                </p>
            </div>
        </div>
        <div
            class="flex md:grid grid-cols-3 gap-x-2 justify-between mt-4 md:mt-5 bg-gray-100 rounded shadow p-2 md:p-4"
            v-for="lab in serology"
            :key="lab.label"
        >
            <p class="font-medium">
                {{ lab.label }}
            </p>
            <p
                class="italic"
                :class="{
                    'text-green-400': lab.result === 'negative',
                    'text-red-400': lab.result === 'positive',
                    'text-amber-400': lab.result === 'intermediate',
                }"
            >
                {{ lab.result ?? '?' }}
            </p>
            <p class="text-complement">
                on: {{ lab.date_lab ?? '?' }}
            </p>
        </div>
    </div>
</template>

<script setup>
import IconInfoCircle from './Icons/IconInfoCircle.vue';

defineProps({
    serology: {type: Array, required:true}
});
</script>
