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
import FormAutocomplete from '../../../Components/Controls/FormAutocomplete.vue';
import FormCheckbox from '../../../Components/Controls/FormCheckbox.vue';
import FormSelect from '../../../Components/Controls/FormSelect.vue';

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
const donorCOD = ref(null);
if (form.donor_cause_of_death && !configs.donor_cause_of_death_options.includes(form.donor_cause_of_death)) {
    configs.donor_cause_of_death_options.push(form.donor_cause_of_death);
}
watch (
    () => form.donor_cause_of_death,
    (val) => {
        if (val !== 'other') {
            return;
        }

        selectOther.placeholder = 'Other donor cause of death';
        selectOther.configs = configs.donor_cause_of_death_options;
        selectOther.input = donorCOD.value;
        selectOtherInput.value.open();
    }
);
const causeOfEsrdInput = ref(null);
if (form.cause_of_esrd && !configs.cause_of_esrd_options.includes(form.cause_of_esrd)) {
    configs.cause_of_esrd_options.push(form.cause_of_esrd);
}
watch (
    () => form.cause_of_esrd,
    (val) => {
        if (val !== 'other') {
            return;
        }

        selectOther.placeholder = 'Other cause of ESRD';
        selectOther.configs = configs.cause_of_esrd_options;
        selectOther.input = causeOfEsrdInput.value;
        selectOtherInput.value.open();
    }
);
const medicalSchemeInput = ref(null);
if (form.medical_scheme && !configs.medical_scheme_options.includes(form.medical_scheme)) {
    configs.medical_scheme_options.push(form.medical_scheme);
}
watch (
    () => form.medical_scheme,
    (val) => {
        if (val !== 'other') {
            return;
        }

        selectOther.placeholder = 'Other scheme';
        selectOther.configs = configs.medical_scheme_options;
        selectOther.input = medicalSchemeInput.value;
        selectOtherInput.value.open();
    }
);
const { selectOtherInput, selectOther, selectOtherClosed } = useSelectOther();

['donor_hn', 'combined_with_liver', 'combined_with_heart', 'combined_with_pancreas'].forEach(field => {
    watch(
        () => form[field],
        () => {
            setTimeout(() => {
                window.axios
                    .get(configs.routes.show)
                    .then(res => {
                        form.case_no = res.data.case_no;
                        form.donor_name = res.data.donor_name;
                    });
            }, 3500);
        }
    );
});
</script>

<template>
    <div>
        <template v-if="configs.can.view_case_data">
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
                    label="gender"
                    readonly
                    name="gender"
                    v-model="form.gender"
                />
                <FormInput
                    label="nationality"
                    readonly
                    name="nationality"
                    v-model="form.nationality"
                />
                <FormInput
                    label="dob"
                    readonly
                    name="dob"
                    v-model="form.dob"
                />
                <FormInput
                    label="age at transplant"
                    readonly
                    name="age_at_tx"
                    v-model="form.age_at_tx"
                />
                <FormInput
                    label="KT NO"
                    readonly
                    name="kt_no"
                    v-model="form.kt_no"
                />
                <FormInput
                    label="recipient ID"
                    readonly
                    name="recipient_id"
                    v-model="form.recipient_id"
                />
                <FormInput
                    label="case NO"
                    readonly
                    name="case_no"
                    v-model="form.case_no"
                />
                <FormInput
                    label="kidney donor type"
                    readonly
                    name="donor_type"
                    v-model="form.donor_type"
                />
                <FormInput
                    label="donor ID"
                    readonly
                    name="donor_id"
                    v-model="form.donor_id"
                />
                <div>
                    <label class="form-label">combined with :</label>
                    <FormCheckbox
                        label="liver"
                        name="combined_with_liver"
                        v-model="form.combined_with_liver"
                    />
                    <FormCheckbox
                        label="heart"
                        name="combined_with_heart"
                        v-model="form.combined_with_heart"
                    />
                    <FormCheckbox
                        label="pancreas"
                        name="combined_with_pancreas"
                        v-model="form.combined_with_pancreas"
                    />
                </div>
                <template v-if="form.donor_type?.startsWith('LD')">
                    <FormInput
                        label="donor hn"
                        name="donor_hn"
                        v-model="form.donor_hn"
                    />
                    <FormInput
                        label="donor name"
                        name="donor_name"
                        v-model="form.donor_name"
                        readonly
                    />
                    <FormSelect
                        label="donor is"
                        name="donor_is"
                        :options="configs[`${form.donor_gender}_donor_is_options`] ?? configs.donor_is_options"
                        v-model="form.donor_is"
                        :error="$page.props.errors.donor_is"
                    />
                    <div class="grid grid-cols-2 gap-4">
                        <FormCheckbox
                            label="ABO incompatible"
                            name="abo_incompatible"
                            v-model="form.abo_incompatible"
                        />
                        <FormCheckbox
                            label="Preemptive"
                            name="preemptive"
                            v-model="form.preemptive"
                        />
                    </div>
                </template>
                <template v-else-if="form.donor_type?.startsWith('CD')">
                    <FormInput
                        label="donor redcross id"
                        name="donor_redcross_id"
                        v-model="form.donor_redcross_id"
                    />
                    <FormAutocomplete
                        label="donor hospital"
                        name="donor_hospital"
                        v-model="form.donor_hospital"
                        :endpoint="configs.routes.hospitals"
                    />
                    <FormAutocomplete
                        :disabled="form.donor_type === 'CD dual kidneys'"
                        label="co-recipient hospital"
                        name="co_recipient_hospital"
                        v-model="form.co_recipient_hospital"
                        :endpoint="configs.routes.hospitals"
                    />
                    <FormSelect
                        label="donor cause of death"
                        name="donor_cause_of_death"
                        v-model="form.donor_cause_of_death"
                        :options="configs.donor_cause_of_death_options"
                        ref="donorCOD"
                        allow-other
                    />
                    <div>
                        <label class="form-label">donor gender :</label>
                        <FormRadio
                            class="grid grid-cols-2 gap-4"
                            name="donor_gender"
                            v-model="form.donor_gender"
                            :options="['female', 'male']"
                        />
                    </div>
                    <FormInput
                        label="donor age"
                        name="donor_age"
                        v-model="form.donor_age"
                        type="tel"
                    />
                    <FormCheckbox
                        label="Trauma donor"
                        name="donor_trauma"
                        v-model="form.donor_trauma"
                    />
                    <div />
                </template>
                <FormAutocomplete
                    label="nephrologist"
                    name="nephrologist"
                    v-model="form.nephrologist"
                    :endpoint="configs.routes.people"
                    :params="configs.routes.nephrologists_scope"
                    :error="$page.props.errors.nephrologist"
                    :length-to-start="3"
                />
                <FormAutocomplete
                    label="surgeon"
                    name="surgeon"
                    v-model="form.surgeon"
                    :endpoint="configs.routes.people"
                    :params="configs.routes.surgeons_scope"
                    :error="$page.props.errors.surgeon"
                    :length-to-start="3"
                />
                <FormSelect
                    label="medical scheme"
                    name="medical_scheme"
                    v-model="form.medical_scheme"
                    :options="configs.medical_scheme_options"
                    :allow-other="true"
                    ref="medicalSchemeInput"
                    :error="$page.props.errors.medical_scheme"
                />
                <FormInput
                    label="kt times"
                    name="kt_times"
                    v-model="form.kt_times"
                    type="tel"
                />
            </div>
            <h2
                class="form-label text-lg italic text-complement mt-4 md:mt-8 xl:mt-16 form-scroll-mt"
                id="operation-data"
            >
                operation data
            </h2>
            <hr class="my-4 border-b border-accent">
            <div class="grid gap-2 md:grid-cols-2 xl:gap-8">
                <FormInput
                    label="pre kt prc (unit)"
                    name="pre_kt_prc_unit"
                    v-model="form.pre_kt_prc_unit"
                    type="tel"
                />
                <div>
                    <label class="form-label">
                        cold ischemia time (hr/min) :
                    </label>
                    <div class="flex space-x-2 md:space-x-4">
                        <FormInput
                            name="cold_ischemic_time_hours"
                            v-model="form.cold_ischemic_time_hours"
                            :error="$page.props.errors.cold_ischemic_time_hours"
                            type="tel"
                            placeholder="hours"
                        />
                        <FormInput
                            name="cold_ischemic_time_minutes"
                            v-model="form.cold_ischemic_time_minutes"
                            :error="$page.props.errors.cold_ischemic_time_minutes"
                            type="tel"
                            placeholder="minutes"
                        />
                    </div>
                </div>
                <FormDatetime
                    v-if="form.donor_type?.startsWith('CD')"
                    label="time clamp at donor"
                    name="time_clamp_at_donor"
                    v-model="form.time_clamp_at_donor"
                    mode="datetime"
                />
                <template v-if="form.donor_type?.startsWith('LD')">
                    <FormInput
                        label="warm ischemia time (min)"
                        name="warm_ischemic_time_minutes"
                        v-model="form.warm_ischemic_time_minutes"
                        :error="$page.props.errors.warm_ischemic_time_minutes"
                        type="tel"
                    />
                    <FormInput
                        label="anastomosis time (min)"
                        name="anastomosis_time_minutes"
                        v-model="form.anastomosis_time_minutes"
                        :error="$page.props.errors.anastomosis_time_minutes"
                        type="tel"
                    />
                </template>
            </div>
        </template>
        <template v-if="configs.can.view_clinical_data">
            <h2
                class="form-label text-lg italic text-complement mt-4 md:mt-8 xl:mt-16 form-scroll-mt"
                id="clinical-data"
            >
                clinical data
            </h2>
            <hr class="my-4 border-b border-accent">
            <div class="grid gap-2 md:grid-cols-2 xl:gap-8">
                <template v-if="configs.recipient_gender === 'female'">
                    <div>
                        <label class="form-label">
                            gestation (g/p/a) :
                        </label>
                        <div class="flex space-x-2 md:space-x-4">
                            <FormInput
                                name="gestation_g"
                                v-model="form.gestation_g"
                                :error="$page.props.errors.gestation_g"
                                type="tel"
                                placeholder="G"
                            />
                            <FormInput
                                name="gestation_p"
                                v-model="form.gestation_p"
                                :error="$page.props.errors.gestation_p"
                                type="tel"
                                placeholder="P"
                            />
                            <FormInput
                                name="gestation_a"
                                v-model="form.gestation_a"
                                :error="$page.props.errors.gestation_a"
                                type="tel"
                                placeholder="A"
                            />
                        </div>
                    </div>
                    <div />
                </template>
                <FormSelect
                    label="cause of esrd"
                    name="cause_of_esrd"
                    v-model="form.cause_of_esrd"
                    :options="configs.cause_of_esrd_options"
                    :allow-other="true"
                    :error="$page.props.errors.cause_of_esrd"
                    ref="causeOfEsrdInput"
                />
                <div>
                    <label
                        class="form-label"
                        for="native_biopsy_report"
                    >esrd diagnosis with native bx report :</label>
                    <FormRadio
                        class="grid grid-cols-1 sm:grid-cols-3 gap-2 md:gap-4"
                        name="native_biopsy_report"
                        v-model="form.native_biopsy_report"
                        :options="['Yes', 'No']"
                        allow-reset
                    />
                </div>
                <FormDatetime
                    label="date first rrt"
                    name="date_first_rrt"
                    v-model="form.date_first_rrt"
                />
                <div>
                    <label
                        class="form-label"
                        for="rrt_mode"
                    >rrt mode :</label>
                    <FormRadio
                        class="grid grid-cols-1 sm:grid-cols-3 gap-2 md:gap-4"
                        name="rrt_mode"
                        v-model="form.rrt_mode"
                        :options="['PD', 'HD']"
                        allow-reset
                    />
                </div>
                <FormInput
                    label="baseline cr (mg/dL)"
                    name="baseline_cr"
                    v-model="form.baseline_cr"
                />
                <FormInput
                    label="pre kt cr (mg/dL)"
                    name="pre_kt_cr"
                    v-model="form.pre_kt_cr"
                />
                <div>
                    <label class="form-label">recipient cmv igg :</label>
                    <FormRadio
                        class="grid grid-cols-2 gap-4"
                        name="recipient_cmv_igg"
                        v-model="form.recipient_cmv_igg"
                        :options="configs.crossmatch_options"
                    />
                </div>
                <div>
                    <label class="form-label">donor cmv igg :</label>
                    <FormRadio
                        class="grid grid-cols-2 gap-4"
                        name="donor_cmv_igg"
                        v-model="form.donor_cmv_igg"
                        :options="configs.crossmatch_options"
                    />
                </div>
            </div>
            <h3 class="form-label mt-4 md:mt-8 xl:mt-16">
                hla mismatch :
            </h3>
            <hr class="border border-dashed my-2 md:my-4 xl:my-8">
            <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8">
                <div
                    v-for="antigen in configs.hla_mismatch_antigens"
                    :key="antigen"
                >
                    <label class="form-label">{{ antigen }} :</label>
                    <FormRadio
                        class="grid grid-cols-2 gap-2 lg:grid-cols-4 lg:gap-4"
                        :name="`mismatch_${antigen.toLowerCase()}`"
                        v-model="form[`mismatch_${antigen.toLowerCase()}`]"
                        :options="configs.hla_mismatch_options"
                        :error="$page.props.errors['mismatch_'+antigen.toLowerCase()]"
                        :allow-reset="true"
                    />
                </div>
            </div>
            <h3 class="form-label mt-4 md:mt-8 xl:mt-16">
                pra :
            </h3>
            <hr class="border border-dashed my-2 md:my-4 xl:my-8">
            <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8">
                <FormInput
                    label="last class i (%)"
                    name="last_pra_class_i_percent"
                    v-model="form.last_pra_class_i_percent"
                    type="number"
                    :error="$page.props.errors.last_pra_class_i_percent"
                />
                <FormDatetime
                    label="date last class i"
                    name="date_last_pra_class_i"
                    v-model="form.date_last_pra_class_i"
                />
                <FormInput
                    label="last class ii (%)"
                    name="last_pra_class_ii_percent"
                    v-model="form.last_pra_class_ii_percent"
                    type="number"
                    :error="$page.props.errors.last_pra_class_ii_percent"
                />
                <FormDatetime
                    label="date last class ii"
                    name="date_last_pra_class_ii"
                    v-model="form.date_last_pra_class_ii"
                />
                <FormInput
                    label="peak class i (%)"
                    name="peak_pra_class_i_percent"
                    v-model="form.peak_pra_class_i_percent"
                    type="number"
                    :error="$page.props.errors.peak_pra_class_i_percent"
                />
                <FormDatetime
                    label="date peak class i"
                    name="date_peak_pra_class_i"
                    v-model="form.date_peak_pra_class_i"
                />
                <FormInput
                    label="peak class ii (%)"
                    name="peak_pra_class_ii_percent"
                    v-model="form.peak_pra_class_ii_percent"
                    type="number"
                    :error="$page.props.errors.peak_pra_class_ii_percent"
                />
                <FormDatetime
                    label="date peak class ii"
                    name="date_peak_pra_class_ii"
                    v-model="form.date_peak_pra_class_ii"
                />
            </div>
            <h3 class="form-label mt-4 md:mt-8 xl:mt-16">
                crossmatch :
            </h3>
            <hr class="border border-dashed my-2 md:my-4 xl:my-8">
            <div
                class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8"
                v-for="(crossmatch, key) in configs.crossmatches"
                :key="key"
            >
                <FormRadio
                    :label="crossmatch.label"
                    :name="crossmatch.name"
                    v-model="form[crossmatch.name]"
                    :options="configs.crossmatch_options"
                    :error="$page.props.errors[crossmatch.name]"
                    :allow-reset="true"
                />
                <FormInput
                    :label="`specify ${crossmatch.label} positive`"
                    :name="`${crossmatch.name}_positive_specification`"
                    v-model="form[`${crossmatch.name}_positive_specification`]"
                    :error="$page.props.errors[crossmatch.name+'_positive_specification']"
                    :disabled="form[crossmatch.name] !== 'positive'"
                />
            </div>
            <h3 class="form-label mt-4 md:mt-8 xl:mt-16">
                graft function :
            </h3>
            <hr class="border border-dashed my-2 md:my-4 xl:my-8">
            <FormSelect
                name="graft_function"
                v-model="form.graft_function"
                :options="configs.graft_function_options"
                :error="$page.props.errors.graft_function"
            />
        </template>

        <template v-if="configs.can.view_follow_up_data">
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
                <FormInput
                    label="date transplant"
                    readonly
                    name="date_transplant_formatted"
                    v-model="form.date_transplant_formatted"
                />
                <FormInput
                    label="date last update"
                    readonly
                    name="date_last_update_formatted"
                    v-model="form.date_last_update_formatted"
                />
                <FormInput
                    label="status"
                    readonly
                    name="status"
                    v-model="caseStatus"
                />
                <FormInput
                    label="refer"
                    name="refer"
                    v-model="form.refer"
                />
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
                name="graft_loss_status_note"
                v-model="form.graft_loss_status_note"
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
                name="patient_status_note"
                v-model="form.patient_status_note"
            />
        </template>

        <template v-if="configs.can.view_clinical_data">
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
        </template>
        <SpinnerButton
            v-if="configs.can.update && configs.can.view_follow_up_data"
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
