<script setup>

import FormInput from '../../../Components/Controls/FormInput.vue';
import FormRadio from '../../../Components/Controls/FormRadio.vue';
import {useForm} from '@inertiajs/vue3';
import {reactive, watch} from 'vue';
import FormDatetime from '../../../Components/Controls/FormDatetime.vue';
import GraftLossReport from '../../../Partials/Clinics/PostKT/GraftLossReport.vue';
import DeadReport from '../../../Partials/Clinics/PostKT/DeadReport.vue';
import FormTextarea from '../../../Components/Controls/FormTextarea.vue';

const props = defineProps({
    formData: {type: Object, required: true},
    formConfigs: {type: Object, required: true},
});

const form = useForm({...props.formData});

const configs = reactive({...props.formConfigs});

watch(
    () => form.graft_status,
    (val) => {
        if (val === 'loss follow up') {
            form.patient_status = 'loss follow up';
            form.status = 'loss follow up';
        } else {
            form.status = [form.graft_status, form.patient_status].join(' / ');
        }
    },
);
watch(
    () => form.patient_status,
    (val) => {
        if (val === 'loss follow up') {
            form.graft_status = 'loss follow up';
            form.status = 'loss follow up';
        } else {
            form.status = [form.graft_status, form.patient_status].join(' / ');
        }
    },
);
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
            <FormDatetime
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
                label="latest cr (mg/dL)"
                name="latest_cr"
                v-model="form.latest_cr"
                readonly
            />
            <FormDatetime
                label="date latest cr"
                name="date_latest_cr"
                v-model="form.date_latest_cr"
                readonly
            />
            <FormInput
                :label="`annual cr (mg/dL) (year ${form.annual_year})`"
                name="annual_cr"
                v-model="form.annual_cr"
                readonly
            />
            <FormDatetime
                label="date annual cr"
                name="date_annual_cr"
                v-model="form.date_annual_cr"
                readonly
            />
        </div>
        <h3 class="form-label mt-4 md:mt-8 xl:mt-16">
            graft status update :
        </h3>
        <hr class="border border-dashed my-2 md:my-4 xl:my-8">
        <div class="grid grid-cols-1 md:grid-cols-2 md:gap-4 xl:gap-8">
            <div class="space-y-2 md:space-y-4">
                <FormRadio
                    class="grid grid-cols-1 gap-2 2xl:grid-cols-3"
                    name="graft_status"
                    :options="configs.graft_status_options"
                    v-model="form.graft_status"
                />
                <FormDatetime
                    label="date update graft status"
                    name="date_update_graft_status"
                    v-model="form.date_update_graft_status"
                />
            </div>
        </div>
        <Transition name="slide-fade">
            <section
                v-if="form.graft_status === 'graft loss'"
                class="space-y-2 md:space-y-4 xl:space-y-8"
            >
                <h3 class="form-label mt-4 md:mt-8 xl:mt-16">
                    Graft Loss report :
                </h3>
                <hr class="border border-dashed my-2 md:my-4 xl:my-8">
                <div class="grid grid-cols-1 md:grid-cols-2 md:gap-4 xl:gap-8">
                    <FormDatetime
                        label="date graft loss"
                        name="date_graft_loss"
                        v-model="form.date_graft_loss"
                    />
                </div>
                <GraftLossReport v-model="form.graft_loss_codes" />
                <FormTextarea
                    label="graft loss note"
                    name="graft_loss_note"
                    v-model="form.graft_loss_note"
                />
            </section>
        </Transition>
        <h2
            class="form-label text-lg italic text-complement mt-4 md:mt-8 xl:mt-16 form-scroll-mt"
            id="patient-status"
        >
            patient status :
        </h2>
        <hr class="my-4 border-b border-accent">
        <div class="grid grid-cols-1 md:grid-cols-2 md:gap-4 xl:gap-8">
            <div class="space-y-2 md:space-y-4">
                <FormRadio
                    class="grid grid-cols-1 gap-2 2xl:grid-cols-3"
                    name="patient_status"
                    :options="configs.patient_status_options"
                    v-model="form.patient_status"
                />
                <FormDatetime
                    label="date update patient status"
                    name="date_update_patient_status"
                    v-model="form.date_update_patient_status"
                />
            </div>
        </div>
        <Transition name="slide-fade">
            <section
                v-if="form.patient_status === 'dead'"
                class="space-y-2 md:space-y-4 xl:space-y-8"
            >
                <h3 class="form-label mt-4 md:mt-8 xl:mt-16">
                    Dead report :
                </h3>
                <hr class="border border-dashed my-2 md:my-4 xl:my-8">
                <div class="grid grid-cols-1 md:grid-cols-2 md:gap-4 xl:gap-8">
                    <FormDatetime
                        label="date dead"
                        name="date_dead"
                        v-model="form.date_dead"
                    />
                </div>
                <DeadReport v-model="form.dead_report_codes" />
                <FormTextarea
                    label="dead note"
                    name="dead_note"
                    v-model="form.dead_note"
                />
            </section>
        </Transition>
        <h2
            class="form-label text-lg italic text-complement mt-4 md:mt-8 xl:mt-16 form-scroll-mt"
            id="creatinine-chart"
        >
            creatinine chart :
        </h2>
        <hr class="my-4 border-b border-accent">
        <h3 class="form-label">
            first year creatinine :
        </h3>
        <hr class="border border-dashed my-2 md:my-4 xl:my-8">
        <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8">
            <FormInput
                label="discharge cr (mg/dL)"
                name="discharge_cr"
                v-model="form.discharge_cr"
                type="number"
            />
            <FormDatetime
                label="date discharge cr"
                name="date_discharge_cr"
                v-model="form.date_discharge_cr"
            />
            <FormInput
                label="one week cr (mg/dL)"
                name="one_week_cr"
                v-model="form.one_week_cr"
                type="number"
            />
            <FormDatetime
                label="date one week cr"
                name="date_one_week_cr"
                v-model="form.date_one_week_cr"
            />
            <FormInput
                label="one month cr (mg/dL)"
                name="one_month_cr"
                v-model="form.one_month_cr"
                type="number"
            />
            <FormDatetime
                label="date one month cr"
                name="date_one_month_cr"
                v-model="form.date_one_month_cr"
            />
            <FormInput
                label="three month cr (mg/dL)"
                name="three_month_cr"
                v-model="form.three_month_cr"
                type="number"
            />
            <FormDatetime
                label="date three month cr"
                name="date_three_month_cr"
                v-model="form.date_three_month_cr"
            />
            <FormInput
                label="six month cr (mg/dL)"
                name="six_month_cr"
                v-model="form.six_month_cr"
                type="number"
            />
            <FormDatetime
                label="date six month cr"
                name="date_six_month_cr"
                v-model="form.date_six_month_cr"
            />
        </div>
        <h3 class="form-label mt-4 md:mt-8 xl:mt-16">
            annual creatinine :
        </h3>
        <hr class="border border-dashed my-2 md:my-4 xl:my-8">
        <div class="space-y-4 xl:space-y-8">
            <div
                v-for="key in 30"
                :key
            >
                <div
                    class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8"
                    v-if="form[`year_${key}_cr`] !== undefined"
                >
                    <FormInput
                        :label="`year ${key} cr (mg/dL)`"
                        :name="`year_${key}_cr`"
                        v-model="form[`year_${key}_cr`]"
                        type="number"
                    />
                    <FormDatetime
                        :label="`date year ${key} cr`"
                        :name="`date_year_${key}_cr`"
                        v-model="form[`date_year_${key}_cr`]"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
