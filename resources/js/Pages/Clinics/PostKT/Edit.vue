<script setup>

import FormInput from '../../../Components/Controls/FormInput.vue';
import FormRadio from '../../../Components/Controls/FormRadio.vue';
import {Link, useForm} from '@inertiajs/vue3';
import {computed, reactive, ref, watch} from 'vue';
import FormDatetime from '../../../Components/Controls/FormDatetime.vue';
import GraftLossReport from '../../../Partials/Clinics/PostKT/GraftLossReport.vue';
import DeadReport from '../../../Partials/Clinics/PostKT/DeadReport.vue';
import FormTextarea from '../../../Components/Controls/FormTextarea.vue';
import SpinnerButton from '../../../Components/Controls/SpinnerButton.vue';
import {useActionStore} from '../../../functions/useActionStore.js';
import {useSelectOther} from '../../../functions/useSelectOther.js';
import FormSelectOther from '../../../Components/Controls/FormSelectOther.vue';
import {useFormAutosave} from '../../../functions/useFormAutosave.js';
import {useConfirmForm} from '../../../functions/useConfirmForm.js';
import ConfirmFormComposable from '../../../Components/Forms/ConfirmFormComposable.vue';
import AlertMessage from '../../../Components/Helpers/AlertMessage.vue';

const props = defineProps({
    formData: {type: Object, required: true},
    formConfigs: {type: Object, required: true},
});

const form = useForm({...props.formData});

const configs = reactive({...props.formConfigs});

const {autosave} = useFormAutosave();
if (configs.can.update) {
    watch(
        () => form.data(),
        (value) => {
            autosave(value, configs.routes.update);
        },
        {deep: true},
    );
}

const caseStatus = computed(() => {
    if (form.patient_status === 'loss follow up') {
        return 'loss f/u';
    } else if (form.patient_status === 'dead') {
        return 'dead';
    } else if (form.graft_status === 'graft loss') {
        return 'graft Loss';
    } else {
        return 'active';
    }
});

watch(
    () => form.graft_status,
    (val) => {
        if (val === 'loss follow up') {
            form.patient_status = 'loss follow up';
            form.status = 'loss follow up';
        }
    },
);
watch(
    () => form.patient_status,
    (val) => {
        if (val === 'loss follow up') {
            form.graft_status = 'loss follow up';
            form.status = 'loss follow up';
        } else if (val === 'dead') {
            form.graft_status = 'graft loss';
        }
    },
);

function timestampUpdate() {
    form.put(configs.routes.timestamp_update);
}

const {confirmForm, openConfirmForm, confirmed} = useConfirmForm();
const {actionStore} = useActionStore();
let actionStoreName = null;
watch (
    () => actionStore.value,
    (value) => {
        switch (value.name) {
        case 'timestamp-update':
            timestampUpdate();
            break;
        case 'destroy-case':
            actionStoreName = value.name;
            openConfirmForm(value.config);
            break;
        default:
            return;
        }
    },
    {deep: true}
);
const handleConfirmedAction = (reason) => {
    switch (actionStoreName) {
    case 'destroy-case':
        useForm({reason: reason}).delete(configs.routes.destroy);
        break;
    default :
        return;
    }
    actionStoreName = null;
};

const deadPlace = ref(null);
if (form.dead_place && !configs.dead_place_options.includes(form.dead_place)) {
    configs.dead_place_options.push(form.dead_place);
}
watch (
    () => form.dead_place,
    (val) => {
        if (val !== 'other') {
            return;
        }

        selectOther.placeholder = 'Other dead place (in case hospital, please specific hospital name)';
        selectOther.configs = configs.dead_place_options;
        selectOther.input = deadPlace.value;
        selectOtherInput.value.open();
    }
);
const { selectOtherInput, selectOther, selectOtherClosed } = useSelectOther();
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
        <AlertMessage
            class="my-4"
            v-if="form.no_patient_record"
            type="warning"
            title="Attention"
            :message="form.no_patient_record_message"
        />
        <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8">
            <FormInput
                label="KT NO"
                readonly
                name="kt_no"
                v-model="form.kt_no"
            />
            <FormInput
                label="KT ID"
                readonly
                name="kt_id"
                v-model="form.kt_id"
            />
            <FormInput
                label="date transplant"
                readonly
                name="date_transplant_formatted"
                v-model="form.date_transplant_formatted"
            />
            <FormInput
                label="status"
                readonly
                name="status"
                v-model="caseStatus"
            />
            <FormInput
                label="date last update"
                readonly
                name="date_last_update_formatted"
                v-model="form.date_last_update_formatted"
            />
        </div>
        <h2
            class="form-label text-lg italic text-complement mt-4 md:mt-8 xl:mt-16 form-scroll-mt"
            id="creatinine-update"
        >
            <span class="flex justify-between items-baseline">
                <span>Creatinine update :</span>
                <Link
                    :href="configs.routes.annual_update"
                    class="text-xs underline font-semibold not-italic text-accent"
                    v-if="configs.can.annual_update"
                >
                    annual update
                </Link>
            </span>
        </h2>
        <hr class="my-4 border-b border-accent">
        <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8">
            <div>
                <FormInput
                    label="latest cr (mg/dL)"
                    name="latest_cr"
                    v-model="form.latest_cr"
                    readonly
                />
                <Link
                    v-if="configs.can.use_latest_cr_to_update_timestamps"
                    :href="configs.routes.timestamp_update_by_latest_cr"
                    class="text-xs uppercase underline font-semibold not-italic text-accent"
                >
                    use latest cr to update timestamps
                </Link>
            </div>
            <FormInput
                label="date latest cr"
                name="date_latest_cr_formatted"
                v-model="form.date_latest_cr_formatted"
                readonly
            />
            <div>
                <FormInput
                    :label="`annual cr (mg/dL) (year ${form.annual_year})`"
                    name="annual_cr"
                    v-model="form.annual_cr"
                    readonly
                />
                <Link
                    v-if="configs.can.use_latest_cr_as_annual_cr"
                    :href="configs.routes.annual_update_by_latest_cr"
                    class="text-xs uppercase underline font-semibold not-italic text-accent"
                >
                    use latest cr as annual cr
                </Link>
            </div>
            <FormInput
                label="date annual cr"
                name="date_annual_cr"
                v-model="form.date_annual_cr"
                readonly
            />
        </div>
        <h2
            class="form-label text-lg italic text-complement mt-4 md:mt-8 xl:mt-16 form-scroll-mt"
            id="graft-status"
        >
            graft status :
        </h2>
        <hr class="my-4 border-b border-accent">
        <div class="grid grid-cols-1 md:grid-cols-2 md:gap-4 xl:gap-8">
            <div class="space-y-2 md:space-y-4">
                <FormRadio
                    class="grid grid-cols-1 gap-2 2xl:grid-cols-3"
                    name="graft_status"
                    :options="configs.graft_status_options"
                    v-model="form.graft_status"
                    :disabled="form.patient_status === 'dead' && form.graft_status === 'graft loss'"
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
                <Transition name="slide-fade">
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 md:gap-4 xl:gap-8"
                        v-if="form.patient_status === 'alive'"
                    >
                        <div>
                            <label class="form-label">dialysis status (in case patient is alive) :</label>
                            <FormRadio
                                class="grid grid-cols-1 gap-2 2xl:grid-cols-3"
                                name="dialysis_status"
                                :options="configs.dialysis_status_options"
                                v-model="form.dialysis_status"
                                allow-reset
                            />
                        </div>
                    </div>
                </Transition>
            </section>
        </Transition>
        <FormTextarea
            class="mt-2 md:mt-4 xl:mt-8"
            label="graft status note"
            name="graft_loss_note"
            v-model="form.graft_loss_note"
        />
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
                <div class="grid grid-cols-1 md:grid-cols-2 md:gap-4 xl:gap-8">
                    <div>
                        <label class="form-label">autopsy perform :</label>
                        <FormRadio
                            class="grid grid-cols-1 gap-2 2xl:grid-cols-3"
                            name="autopsy_perform"
                            :options="configs.autopsy_perform_options"
                            v-model="form.autopsy_perform"
                            allow-reset
                        />
                    </div>
                    <div>
                        <label class="form-label">dead place :</label>
                        <FormRadio
                            class="grid grid-cols-1 gap-2 2xl:grid-cols-3"
                            name="dead_place"
                            :options="configs.dead_place_options"
                            v-model="form.dead_place"
                            allow-reset
                            allow-other
                            ref="deadPlace"
                        />
                    </div>
                </div>
            </section>
        </Transition>
        <FormTextarea
            class="mt-2 md:mt-4 xl:mt-8"
            label="patient status note"
            name="dead_note"
            v-model="form.dead_note"
        />
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
                v-for="key in parseInt(form.annual_year)"
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
        <hr class="border border-dashed my-2 md:my-4 xl:my-8">
        <FormTextarea
            label="remark"
            name="remark"
            v-model="form.remark"
        />
        <SpinnerButton
            v-if="configs.can.update"
            :spin="form.processing"
            @click="timestampUpdate"
            class="mt-4 md:mt-8 w-full btn-complement"
        >
            TIMESTAMP UPDATE
        </SpinnerButton>

        <FormSelectOther
            :placeholder="selectOther.placeholder"
            ref="selectOtherInput"
            @closed="selectOtherClosed"
        />

        <ConfirmFormComposable
            ref="confirmForm"
            @confirmed="(reason) => confirmed(reason, handleConfirmedAction)"
        />
    </div>
</template>

<style scoped>

</style>
