<template>
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
            name="encountered_at_text"
            v-model="form.record.hn"
        />
        <FormInput
            label="an"
            :readonly="true"
            name="encountered_at_text"
            v-model="form.admission.an"
            placeholder="No active admission"
        />
        <FormInput
            label="first dialysis on"
            :readonly="true"
            name="first_dialysis_at"
            v-model="dateFirstDialysis"
        />
        <FormInput
            label="last dialysis on"
            :readonly="true"
            name="last_dialysis_at"
            v-model="form.last_dialysis_at"
        />
        <template v-if="form.admission.an">
            <FormInput
                label="admitted on"
                :readonly="true"
                name="encountered_at_text"
                v-model="form.admission.encountered_at_text"
            />
            <FormInput
                label="discharged on"
                :readonly="true"
                name="dismissed_at_text"
                v-model="form.admission.dismissed_at_text"
            />
            <FormInput
                label="ward admit"
                :readonly="true"
                name="ward_admit"
                v-model="form.admission.place_name"
            />
            <FormAutocomplete
                label="ward discharge"
                :endpoint="configs.endpoints.resources_api_wards"
                v-model="form.ward_discharge"
                name="ward_discharge"
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
        :options="configs.renal_diagnosis"
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
                :options="['Positive', 'Intermediate', 'Negative']"
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
                :options="['Positive', 'Intermediate', 'Negative']"
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
                :options="['Positive', 'Intermediate', 'Negative']"
            />
        </div>
        <FormDatetime
            label="date anti HIV"
            name="date_anti_hiv"
            v-model="form.date_anti_hiv"
        />
    </div>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">

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

    <!-- reservation -->
    <h2
        class="mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement"
        id="reservation"
    >
        Reservation
    </h2>
    <hr class="my-4 border-b border-accent">
    <template v-if="configs.dialysis_reservable">
        <div class="grid md:grid-cols-2 gap-2 lg:gap-6">
            <FormAutocomplete
                label="dialysis at"
                name="dialysis_at"
                v-model="order.dialysis_at"
                :endpoint="configs.endpoints.resources_api_wards"
                :error="order.errors.dialysis_at"
                :length-to-start="1"
            />
            <FormAutocomplete
                label="attending"
                name="attending_staff"
                v-model="order.attending_staff"
                :endpoint="configs.endpoints.resources_api_staffs"
                :params="configs.staffs_scope_params"
                :error="order.errors.attending_staff"
                :length-to-start="1"
            />
            <FormSelect
                label="dialysis type"
                name="order_dialysis_type"
                v-model="order.dialysis_type"
                :options="order.dialysis_at && order.dialysis_at.startsWith('ไตเทียม') ? configs.in_unit_dialysis_types : configs.out_unit_dialysis_types"
                :disabled="!order.dialysis_at"
            />
            <div>
                <label class="form-label">patient type</label>
                <FormRadio
                    class="grid grid-cols-2 gap-x-2"
                    name="patient_type"
                    v-model="order.patient_type"
                    :options="configs.patient_types"
                    ref="patientTypeInput"
                />
            </div>
        </div>
        <Transition name="slide-fade">
            <div v-if="order.dialysis_at && order.dialysis_type">
                <div class="grid xl:grid-cols-2 gap-2 md:gap-4 lg:gap-6">
                    <FormDatetime
                        label="required date"
                        name="date_note"
                        v-model="order.date_note"
                        :options="{ enable: configs.reserve_available_dates, onDayCreate: onDayCreate, inline: true }"
                        ref="dateNoteInput"
                    />
                    <div>
                        <Transition
                            name="slide-fade"
                            v-if="order.date_note && order.dialysis_at"
                        >
                            <DialysisSlot
                                :slots="reservedSlots.slots"
                                v-if="order.dialysis_at.indexOf('Hemo') !== -1"
                            />
                            <WardSlot
                                v-else
                                :slots="reservedSlots.slots"
                            />
                        </Transition>
                        <AlertMessage
                            v-if="reservedSlots.reply && !reservedSlots.available"
                            class="mt-4"
                            type="warning"
                            title="Cannot make a reservation"
                            :message="reservedSlots.reply"
                        />
                    </div>
                </div>
                <div class="mt-2 lg:mt-0 md:pt-4">
                    <SpinnerButton
                        class="block w-full text-center btn btn-accent"
                        @click="reserve"
                        :spin="order.processing"
                        :disabled="reserveButtonDisable"
                    >
                        RESERVE
                    </SpinnerButton>
                </div>
            </div>
        </Transition>
    </template>
    <AlertMessage
        v-else
        title="Cannot make a reservation"
        type="warning"
        message="One active order at a time"
    />

    <FormSelectOther
        :placeholder="selectOther.placeholder"
        ref="selectOtherInput"
        @closed="selectOtherClosed"
    />
</template>

<script setup>
import { useForm } from '@inertiajs/inertia-vue3';
import { computed, defineAsyncComponent, reactive, ref, watch } from 'vue';
import { useSelectOther } from '@/functions/useSelectOther.js';
import debounce from 'lodash/debounce';
import FormInput from '@/Components/Controls/FormInput.vue';
import FormAutocomplete from '@/Components/Controls/FormAutocomplete.vue';
import FormRadio from '@/Components/Controls/FormRadio.vue';
import FormCheckbox from '@/Components/Controls/FormCheckbox.vue';
import FormDatetime from '@/Components/Controls/FormDatetime.vue';
import ImageUploader from '@/Components/Controls/ImageUploader.vue';
import FormSelectOther from '@/Components/Controls/FormSelectOther.vue';
import FormSelect from '@/Components/Controls/FormSelect.vue';
import SpinnerButton from '@/Components/Controls/SpinnerButton.vue';
import OrderIndex from '@/Partials/Procedures/AcuteHemodialysis/OrderIndex.vue';
const DialysisSlot = defineAsyncComponent(() => import('@/Partials/Procedures/AcuteHemodialysis/DialysisSlot.vue'));
const WardSlot = defineAsyncComponent(() => import('@/Partials/Procedures/AcuteHemodialysis/WardSlot.vue'));
const AlertMessage = defineAsyncComponent(() => import('@/Components/Helpers/AlertMessage.vue'));

const props = defineProps({
    caseRecordForm: { type: Object, required: true },
    orders: { type: Array, required: true },
    formConfigs: { type: Object, required: true }
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
const dateFirstDialysis = ref(props.orders.length ? props.orders[0].date_note : null);
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
const onDayCreate = (dObj, dStr, fp, dayElem) => {
    if (!configs.reserve_disable_dates.length) return;
    for (let i = 0; i < configs.reserve_disable_dates.length; i++) {
        if (dayElem.getAttribute('aria-label') == configs.reserve_disable_dates[i]) {
            dayElem.innerHTML += '<span class="calendar-event busy"></span>';
        }
    }
};
watch (
    () => order.date_note,
    (val) => {
        if (!val) {
            return;
        }
        window.axios
            .post(configs.endpoints.acutehemodialysis_slot_available, {
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
const reserveButtonDisable = computed(() => {
    return !order.dialysis_at || !order.date_note || !order.dialysis_type || !order.patient_type || !order.attending_staff || !reservedSlots.available;
});

const reserve = () => {
    order.post(configs.endpoints.orders_store, {
        onFinish: () => order.processing = false,
        onError: (error) => console.log(error),
    });
};
</script>
