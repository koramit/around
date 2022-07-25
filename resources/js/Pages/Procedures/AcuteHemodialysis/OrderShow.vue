<template>
    <Transition mode="out-in">
        <Suspense v-if="configs.covid.hn">
            <CovidInfo
                class="mb-4"
                :configs="configs.covid"
            />
            <template #fallback>
                <FallbackSpinner />
            </template>
        </Suspense>
    </Transition>
    <h1>Hello order</h1>
    <div
        v-for="field in Object.keys(content)"
        :key="field"
    >
        <label class="form-label">{{ field }}</label>
        <p>{{ content[field] }}</p>
    </div>
</template>

<script setup>
import CovidInfo from '../../../Components/Helpers/CovidInfo.vue';
import FallbackSpinner from '../../../Components/Helpers/FallbackSpinner.vue';

defineProps({
    configs: {type: Object, required: true},
    content: {type: Object, required: true}
});
</script>

