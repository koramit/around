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
    <!-- reservation -->
    <h2 class="mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement">
        Reservation data
    </h2>
    <hr class="my-4 border-b border-accent">
    <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4">
        <DisplayData
            v-for="(field, key) in Object.keys(content.reservation)"
            :key="key"
            :label="field"
            :data="content.reservation[field]"
        />
    </div>
    <!--special requests-->
    <h2 class="mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement">
        Special Requests
    </h2>
    <hr class="my-4 border-b border-accent">
    <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4">
        <DisplayData
            v-for="(field, key) in content.special_requests.filter(r => r.data)"
            :key="key"
            :label="field.label"
            :data="field.data"
        />
    </div>
    <!--Predialysis Evaluation-->
    <h2 class="mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement">
        Predialysis Evaluation
    </h2>
    <hr class="my-4 border-b border-accent">
    <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4">
        <DisplayData
            v-for="(field, key) in content.predialysis_evaluation.filter(e => e.data)"
            :key="key"
            :label="field.label"
            :data="field.data"
        />
    </div>
    <!--HD prescription-->
    <template v-if="content.hd !== undefined">
        <h2 class="mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement">
            HD Prescription
        </h2>
        <hr class="my-4 border-b border-accent">
        <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4">
            <DisplayData
                v-for="(field, key) in content.hd"
                :key="key"
                :label="field.label"
                :data="field.data"
            />
        </div>
    </template>
    <!--HF prescription-->
    <template v-if="content.hf !== undefined">
        <h2 class="mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement">
            HF Prescription
        </h2>
        <hr class="my-4 border-b border-accent">
        <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4">
            <DisplayData
                v-for="(field, key) in content.hf"
                :key="key"
                :label="field.label"
                :data="field.data"
            />
        </div>
    </template>
    <!--TPE prescription-->
    <template v-if="content.tpe !== undefined">
        <h2 class="mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement">
            TPE Prescription
        </h2>
        <hr class="my-4 border-b border-accent">
        <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4">
            <DisplayData
                v-for="(field, key) in content.tpe"
                :key="key"
                :label="field.label"
                :data="field.data"
            />
        </div>
    </template>
</template>

<script setup>
import CovidInfo from '../../../Components/Helpers/CovidInfo.vue';
import FallbackSpinner from '../../../Components/Helpers/FallbackSpinner.vue';
import DisplayData from '../../../Components/Controls/DisplayData.vue';

defineProps({
    configs: {type: Object, required: true},
    content: {type: Object, required: true}
});
</script>

