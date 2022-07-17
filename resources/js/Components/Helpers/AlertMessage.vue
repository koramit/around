<template>
    <div
        role="alert"
        class="border-t-4 rounded-b px-4 py-3 shadow-sm bg-white"
        :class="{
            'border-red-400': type === 'danger', 'text-red-400': type === 'danger',
            'border-yellow-400': type === 'warning', 'text-yellow-400': type === 'warning',
            'border-green-400': type === 'success', 'text-green-400': type === 'success',
            'border-blue-400': type === 'info', 'text-blue-400': type === 'info',
        }"
    >
        <div class="flex items-center">
            <div class="w-1/6 flex justify-center">
                <IconExclamationCircle
                    v-if="type === 'danger'"
                    class="w-6 h-6 md:w-10 md:h-10 lg:w-16 lg:h-16"
                />
                <IconInfoCircle
                    v-else
                    class="w-6 h-6 md:w-10 md:h-10 lg:w-16 lg:h-16"
                />
            </div>
            <div class="w-5/6 lg:text-lg">
                <p class="font-semibold">
                    {{ title }}
                </p>
                <p class="text-gray-700 text-sm font-medium lg:text-lg mt-2">
                    {{ message }}
                </p>
            </div>
        </div>
        <div
            class="flex justify-end"
            v-if="$page.props.flash.message?.detail ?? null"
        >
            <div class="w-5/6 h-48 bg-primary">
                extra
            </div>
        </div>
    </div>
</template>

<script setup>
import IconInfoCircle from './Icons/IconInfoCircle.vue';
import IconExclamationCircle from './Icons/IconExclamationCircle.vue';
defineProps({
    type: { type: String, default: 'info' },
    title: { type: String, default: 'What\'s up!' },
    message: { type: String, default: 'Hello world' },
});
</script>
