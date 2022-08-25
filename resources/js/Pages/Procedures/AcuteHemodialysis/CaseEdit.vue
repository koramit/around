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
    <!-- summary  -->
    <h2
        class="form-label text-lg italic text-complement scroll-mt-16 md:scroll-mt-8"
        id="case-record"
    >
        Case record
    </h2>
    <hr class="my-4 border-b border-accent">
    <div class="grid gap-2 md:grid md:gap-4 md:grid-cols-2 xl:gap-8">
        <FormInput
            label="hn"
            :readonly="true"
            name="hn"
            v-model="form.record.hn"
        />
        <FormInput
            label="an"
            :readonly="true"
            name="an"
            v-model="form.admission.an"
            placeholder="No active admission"
        />
        <FormInput
            label="first md"
            :readonly="true"
            name="first_md"
            v-model="form.computed.first_md"
        />
        <FormInput
            label="first dialysis on"
            :readonly="true"
            name="first_dialysis_at"
            v-model="form.computed.first_dialysis_at"
        />
        <FormInput
            label="latest md"
            :readonly="true"
            name="latest_md"
            v-model="form.computed.latest_md"
        />
        <FormInput
            label="latest dialysis on"
            :readonly="true"
            name="latest_dialysis_at"
            v-model="form.computed.latest_dialysis_at"
        />
        <template v-if="form.admission.an">
            <FormInput
                label="admitted on"
                :readonly="true"
                name="admitted_at"
                v-model="form.admission.admitted_at"
            />
            <FormInput
                label="discharged on"
                :readonly="true"
                name="discharged_at"
                v-model="form.admission.discharged_at"
            />
            <FormInput
                label="ward admit"
                :readonly="true"
                name="ward_admit"
                v-model="form.admission.ward_admit"
            />
            <FormInput
                label="ward discharge"
                :readonly="true"
                name="ward_discharge"
                v-model="form.admission.ward_discharge"
            />
        </template>
    </div>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">

    <!-- outcome -->
    <h3 class="form-label mt-4 md:mt-8 xl:mt-16">
        Outcome :
    </h3>
    <div class="grid gap-2 md:grid-cols-2 md:gap-4 xl:gap-6">
        <div>
            <FormRadio
                label="renal outcome"
                name="renal_outcome"
                v-model="form.renal_outcome"
                :allow-reset="true"
                :options="['Recovery', 'ESRD', 'KT']"
            />
            <Transition name="slide-fade">
                <FormInput
                    v-if="form.renal_outcome === 'Recovery'"
                    label="last creatinine before discharge"
                    class="mt-2 md:mt-t xl:mt-6"
                    name="cr_before_discharge"
                    v-model="form.cr_before_discharge"
                />
            </Transition>
        </div>
        <div>
            <FormRadio
                label="patient outcome"
                name="patient_outcome"
                v-model="form.patient_outcome"
                :allow-reset="true"
                :options="['Alive', 'Dead']"
            />
            <Transition name="slide-fade">
                <FormInput
                    v-if="form.patient_outcome === 'Dead'"
                    label="cause of dead"
                    class="mt-2 md:mt-t xl:mt-6"
                    name="cause_of_dead"
                    v-model="form.cause_of_dead"
                />
            </Transition>
        </div>
    </div>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">

    <!-- previous crrt -->
    <FormCheckbox
        class="mt-4 md:mb-4 md:mt-8 xl:mt-16"
        label="Previous crrt"
        v-model="form.previous_crrt"
        :toggler="true"
    />
    <Transition name="slide-fade">
        <div
            class="grid gap-2 md:grid md:gap-4 md:grid-cols-2 xl:gap-8"
            v-if="form.previous_crrt"
        >
            <FormDatetime
                label="date start crrt"
                name="date_start_crrt"
                v-model="form.date_start_crrt"
            />
            <FormDatetime
                label="date end crrt"
                name="date_end_crrt"
                v-model="form.date_end_crrt"
            />
        </div>
    </Transition>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">

    <!-- renal diagnosis -->
    <h3 class="form-label mt-4 md:mt-8 xl:mt-16">
        Renal diagnosis :
    </h3>
    <FormRadio
        class="sm:grid grid-cols-2 gap-x-2 lg:grid-cols-4"
        name="renal_diagnosis"
        v-model="form.renal_diagnosis"
        :options="[...configs.renal_diagnosis]"
    />
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">

    <!-- admission diagnosis -->
    <template v-if="form.admission.an">
        <h3 class="form-label mt-4 md:mt-8 xl:mt-16">
            admission diagnosis :
        </h3>
        <FormInput
            name="admission_diagnosis"
            v-model="form.admission_diagnosis"
        />
        <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    </template>

    <!-- comorbid and indication -->
    <div class="sm:hidden">
        <h3 class="form-label mt-4 md:mt-8 xl:mt-16">
            Comorbidities :
        </h3>
        <div class="mt-2 md:mt-4 xl:mt-6 grid gap-2 md:gap-4 xl:gap-6 2xl:grid-cols-2">
            <FormCheckbox
                v-for="(disease, key) in configs.comorbidities"
                :key="key"
                :name="disease.name"
                :label="disease.label"
                v-model="form.comorbidities[disease.name]"
            />
        </div>
        <FormInput
            class="mt-2 md:mt-4 xl:mt-6 h-auto"
            name="comorbidities_other"
            label="Other comorbidities"
            v-model="form.comorbidities.other"
        />
        <h3 class="form-label mt-4 md:mt-8 xl:mt-16">
            Indication for dialysis :
        </h3>
        <div class="mt-2 md:mt-4 xl:mt-6 grid gap-2 md:gap-4 xl:gap-6 2xl:grid-cols-2">
            <FormCheckbox
                v-for="(disease, key) in configs.indications"
                :key="key"
                :name="disease.name"
                :label="disease.label"
                v-model="form.indications[disease.name]"
            />
        </div>
        <FormInput
            class="mt-2 md:mt-4 xl:mt-6 h-auto"
            name="indications_other"
            label="Other indications"
            v-model="form.indications.other"
        />
    </div>
    <div class="hidden sm:grid grid-cols-2 gap-2">
        <div>
            <h3 class="form-label mt-4 md:mt-8 xl:mt-16">
                Comorbidities :
            </h3>
            <div class="mt-2 md:mt-4 xl:mt-6 grid gap-2 md:gap-4 xl:gap-6 2xl:grid-cols-2">
                <FormCheckbox
                    v-for="(disease, key) in configs.comorbidities"
                    :key="key"
                    :name="disease.name"
                    :label="disease.label"
                    v-model="form.comorbidities[disease.name]"
                />
            </div>
        </div>
        <div>
            <h3 class="form-label mt-4 md:mt-8 xl:mt-16">
                Indication for dialysis :
            </h3>
            <div class="mt-2 md:mt-4 xl:mt-6 grid gap-2 md:gap-4 xl:gap-6 2xl:grid-cols-2">
                <FormCheckbox
                    v-for="(disease, key) in configs.indications"
                    :key="key"
                    :name="disease.name"
                    :label="disease.label"
                    v-model="form.indications[disease.name]"
                />
            </div>
        </div>
        <FormInput
            class="mt-2 md:mt-4 xl:mt-6 h-auto"
            name="comorbidities_other"
            label="Other comorbidities"
            v-model="form.comorbidities.other"
        />
        <FormInput
            class="mt-2 md:mt-4 xl:mt-6 h-auto"
            name="indications_other"
            label="Other indications"
            v-model="form.indications.other"
        />
    </div>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">

    <!-- serology -->
    <h3 class="form-label mt-4 md:mt-8 xl:mt-16">
        Serology :
    </h3>
    <div class="grid gap-2 md:grid-cols-2 md:gap-4 xl:gap-6">
        <div>
            <label class="form-label">HBs Ag</label>
            <FormRadio
                class="grid lg:grid-cols-2 gap-x-2"
                name="hbs_ag"
                v-model="form.hbs_ag"
                :options="configs.serology_results"
            />
        </div>
        <FormDatetime
            label="date HBs Ag"
            name="date_hbs_ag"
            v-model="form.date_hbs_ag"
        />
        <div>
            <label class="form-label">anti hcv</label>
            <FormRadio
                class="grid lg:grid-cols-2 gap-x-2"
                name="anti_hcv"
                v-model="form.anti_hcv"
                :options="configs.serology_results"
            />
        </div>
        <FormDatetime
            label="date anti hcv"
            name="date_anti_hcv"
            v-model="form.date_anti_hcv"
        />
        <div>
            <label class="form-label">anti HIV</label>
            <FormRadio
                class="grid lg:grid-cols-2 gap-x-2"
                name="anti_hiv"
                v-model="form.anti_hiv"
                :options="configs.serology_results"
            />
        </div>
        <FormDatetime
            label="date anti HIV"
            name="date_anti_hiv"
            v-model="form.date_anti_hiv"
        />
    </div>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">

    <!--@TODO may not always show OPD consent-->
    <!-- consent signed -->
    <h3 class="form-label mt-4 md:mt-8 xl:mt-16">
        Consent :
    </h3>
    <ImageUploader
        label="๏ opd consent form"
        :pathname="configs.opd_consent_form_pathname"
        v-model="form.opd_consent_form"
        :service-endpoints="configs.image_upload_endpoints"
    />
    <template v-if="form.admission.an">
        <ImageUploader
            class="mt-4 md:mt-6"
            label="๏ ipd consent form"
            :pathname="configs.ipd_consent_form_pathname"
            :service-endpoints="configs.image_upload_endpoints"
            v-model="form.ipd_consent_form"
        />
        <FormCheckbox
            class="mt-4 md:mt-6"
            label="Use same form"
            name="same_consent_form"
            v-model="form.same_consent_form"
            :toggler="true"
        />
    </template>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">

    <!-- insurance  -->
    <h3 class="form-label mt-4 md:mt-8 xl:mt-16">
        Medical scheme :
    </h3>
    <FormRadio
        name="insurance"
        class="md:grid grid-cols-2 gap-2"
        v-model="form.insurance"
        :options="configs.insurances"
        :allow-other="true"
        ref="insurance"
    />

    <!-- HD orders -->
    <h2
        class="mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement"
        id="orders"
    >
        Orders
    </h2>
    <hr class="my-4 border-b border-accent">
    <OrderIndex :orders="orders" />

    <!--discussion-->
    <h2
        class="mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement scroll-mt-16 md:scroll-mt-8"
        id="discussion"
    >
        discussion
    </h2>
    <hr class="my-4 border-b border-accent">
    <CommentSection :configs="configs.comment" />

    <FormSelectOther
        :placeholder="selectOther.placeholder"
        ref="selectOtherInput"
        @closed="selectOtherClosed"
    />
</template>

<script setup>
import {useForm} from '@inertiajs/inertia-vue3';
import {nextTick, onMounted, reactive, ref, watch} from 'vue';
import {useSelectOther} from '../../../functions/useSelectOther.js';
import debounce from 'lodash/debounce';
import FormInput from '../../../Components/Controls/FormInput.vue';
import FormRadio from '../../../Components/Controls/FormRadio.vue';
import FormCheckbox from '../../../Components/Controls/FormCheckbox.vue';
import FormDatetime from '../../../Components/Controls/FormDatetime.vue';
import ImageUploader from '../../../Components/Controls/ImageUploader.vue';
import FormSelectOther from '../../../Components/Controls/FormSelectOther.vue';
import OrderIndex from '../../../Partials/Procedures/AcuteHemodialysis/OrderIndex.vue';
import {useInPageLinkHelpers} from '../../../functions/useInPageLinkHelpers';
import CovidInfo from '../../../Components/Helpers/CovidInfo.vue';
import FallbackSpinner from '../../../Components/Helpers/FallbackSpinner.vue';
import CommentSection from '../../../Components/Forms/CommentSection.vue';

const props = defineProps({
    caseRecordForm: { type: Object, required: true },
    orders: { type: Array, required: true },
    formConfigs: { type: Object, required: true },
    gotoSection: { type: [String, null], default: null },
});

const configs = reactive({...props.formConfigs});
const form = useForm({...props.caseRecordForm});
const reset = {
    previous_crrt: true,
};
watch (
    () => form,
    (val) => {
        if (!val.previous_crrt && !reset.previous_crrt) {
            val.date_start_crrt = null;
            val.date_end_crrt = null;
            reset.previous_crrt = true;
        } else if (val.previous_crrt) {
            reset.previous_crrt = false;
        }

        let data = val.data();
        delete data.admission;
        delete data.record;
        delete data.computed;
        autosave(configs.endpoints.update, data);
    },
    { deep: true }
);
watch(
    () => form.indications.initiate_chronic_hd,
    (val) => {
        if (val) {
            form.indications.maintain_chronic_hd = false;
            form.indications.change_from_pd = false;
        }
    }
);
watch(
    () => form.indications.maintain_chronic_hd,
    (val) => {
        if (val) {
            form.indications.initiate_chronic_hd = false;
            form.indications.change_from_pd = false;
        }
    }
);
watch(
    () => form.indications.change_from_pd,
    (val) => {
        if (val) {
            form.indications.initiate_chronic_hd = false;
            form.indications.maintain_chronic_hd = false;
        }
    }
);
const autosave = debounce(function (url, data) {
    window.axios
        .patch(url, data)
        .catch(error => {
            console.log(error);
        });
}, 2000);
const insurance = ref(null);
if (form.insurance && !configs.insurances.includes(form.insurance)) {
    configs.insurances.push(form.insurance);
}
watch (
    () => form.insurance,
    (val) => {
        if (val !== 'other') {
            return;
        }

        selectOther.placeholder = 'Other Insurance';
        selectOther.configs = configs.insurances;
        selectOther.input = insurance.value;
        selectOtherInput.value.open();
    }
);
const { selectOtherInput, selectOther, selectOtherClosed } = useSelectOther();

// HD orders
const order = useForm({
    dialysis_type: null,
    dialysis_at: null,
    attending_staff: null,
    date_note: null,
    patient_type: null,
    case_record_hashed_key: form.record.hashed_key,
});
const patientTypeInput = ref(null);
watch (
    () => order.date_note,
    (val) => {
        if (!val) {
            return;
        }
        window.axios
            .post(configs.endpoints.acute_hemodialysis_slot_available, {
                dialysis_type: order.dialysis_type,
                dialysis_at: order.dialysis_at,
                date_note: order.date_note,
            }).then(response => {
                reservedSlots.slots = response.data.slots;
                reservedSlots.available = response.data.available;
                reservedSlots.reply = response.data.reply;
            });
    }
);
watch (
    () => order.dialysis_type,
    () => {
        if (!order.patient_type && (form.indications.initiate_chronic_hd || form.indications.maintain_chronic_hd)) {
            patientTypeInput.value.setOther('Chronic');
        }
    }
);
const dateNoteInput = ref(null);
const resetSlots = () => {
    reservedSlots.slots = [];
    reservedSlots.available = false;
    reservedSlots.reply = '';
    if (dateNoteInput.value) {
        dateNoteInput.value.clear();
    }
};
watch (
    () => order.dialysis_at,
    resetSlots,
);
watch (
    () => order.dialysis_type,
    resetSlots,
);

const reservedSlots = reactive({
    slots: [],
    available: false,
    reply: '',
});

onMounted(() => {
    if (props.gotoSection === null) {
        return;
    }

    nextTick (() => {
        const {smoothScroll} = useInPageLinkHelpers();
        smoothScroll(props.gotoSection);
    });
});
</script>
