<script setup>

import FormInput from '../../../Components/Controls/FormInput.vue';
import FormRadio from '../../../Components/Controls/FormRadio.vue';
import {reactive} from 'vue';

const form = reactive({
    kt_no: '67-01',
    kt_id: 12024001,
    date_transplant: '16 Jan 2024',
    status: 'graft function / alive',
    graft_status: null,
    date_latest_cr: null,
    latest_cr: null,
    patient_status: null,
});

const configs = {
    graft_status_options: ['graft function', 'graft loss', 'loss follow up'],
    patient_status_options: ['alive', 'dead', 'loss follow up']
};
</script>

<template>
    <div>
        <h2
            class="form-label text-lg italic text-complement form-scroll-mt"
            id="case-data"
        >
            CASE DATA
        </h2>
        <hr class="my-4 border-b border-accent">
        <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8">
            <FormInput
                label="KT NO"
                :readonly="true"
                name="kt_no"
                v-model="form.kt_no"
            />
            <FormInput
                label="KT ID"
                :readonly="true"
                name="kt_id"
                v-model="form.kt_id"
            />
            <FormInput
                label="date transplant"
                :readonly="true"
                name="date_transplant"
                v-model="form.date_transplant"
            />
            <FormInput
                label="status"
                :readonly="true"
                name="status"
                v-model="form.status"
            />
        </div>
        <h2
            class="form-label text-lg italic text-complement mt-4 md:mt-8 xl:mt-16 form-scroll-mt"
            id="graft-status"
        >
            graft status :
        </h2>
        <hr class="my-4 border-b border-accent">
        <h3 class="form-label">
            Creatinine update :
        </h3>
        <hr class="border border-dashed my-2 md:my-4 xl:my-8">
        <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8">
            <FormInput
                label="date latest cr"
                name="date_latest_cr"
                v-model="form.date_latest_cr"
                readonly
            />
            <FormInput
                label="latest cr (mg/dL)"
                name="latest_cr"
                v-model="form.latest_cr"
                readonly
            />
            <FormInput
                label="date annual cr"
                name="date_annual_cr"
                v-model="form.date_annual_cr"
                readonly
            />
            <FormInput
                label="annual cr (mg/dL)"
                name="annual_cr"
                v-model="form.latest_cr"
                readonly
            />
        </div>
        <h3 class="form-label mt-4 md:mt-8 xl:mt-16">
            graft status update :
        </h3>
        <hr class="border border-dashed my-2 md:my-4 xl:my-8">
        <div>
            <FormRadio
                class="grid grid-cols-2 gap-4"
                name="graft_status"
                :options="configs.graft_status_options"
                v-model="form.graft_status"
            />
        </div>
        <h2
            class="form-label text-lg italic text-complement mt-4 md:mt-8 xl:mt-16 form-scroll-mt"
            id="graft-status"
        >
            patient status :
        </h2>
        <hr class="my-4 border-b border-accent">
        <div>
            <FormRadio
                class="grid grid-cols-2 gap-4"
                name="patient_status"
                :options="configs.patient_status_options"
                v-model="form.patient_status"
            />
        </div>
    </div>
</template>

<style scoped>

</style>
