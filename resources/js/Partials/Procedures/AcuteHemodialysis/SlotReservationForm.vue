<template>
    <div class="flex flex-col-reverse md:flex-row md:space-x-4">
        <div class="space-y-2 md:w-1/2">
            <FormAutocompleteKeyValue
                name="case"
                label="case"
                v-model:key-model="form.case_key"
                v-model:value-model="form.case_label"
                :error="form.errors.case_key"
                :endpoint="configs.routes.idle_cases"
            />
            <FormCheckbox
                class="border border-dashed border-l-0 border-r-0 border-accent py-2"
                label="covid-19 infected"
                :toggler="true"
                name="covid_case"
                v-model="form.covid_case"
            />
            <FormAutocomplete
                label="dialysis at"
                name="dialysis_at"
                v-model="form.dialysis_at"
                :endpoint="configs.routes.resources_api_wards"
                :error="form.errors.dialysis_at"
                :length-to-start="1"
            />
            <FormSelect
                label="dialysis type"
                name="dialysis_type"
                v-model="form.dialysis_type"
                :options="form.dialysis_at && form.dialysis_at.search('Hemodialysis') !== -1 ? configs.in_unit_dialysis_types : configs.out_unit_dialysis_types"
                :disabled="!form.dialysis_at"
            />
            <div>
                <label class="form-label">patient type</label>
                <FormRadio
                    class="grid grid-cols-2 gap-x-2"
                    name="patient_type"
                    v-model="form.patient_type"
                    :options="configs.patient_types"
                    ref="patientTypeInput"
                />
            </div>
            <FormAutocomplete
                label="attending"
                name="attending_staff"
                v-model="form.attending_staff"
                :endpoint="configs.routes.resources_api_staffs"
                :params="configs.routes.staffs_scope_params"
                :error="form.errors.attending_staff"
                :length-to-start="1"
            />
            <SpinnerButton
                class="btn btn-complement w-full"
                :spin="checking"
                :disabled="checkingIncomplete"
                @click="checkAvailableDates"
            >
                Check available dates
            </SpinnerButton>
            <Transition name="slide-fade">
                <div
                    class="space-y-2"
                    v-if="availableDates.length"
                >
                    <FormSelect
                        label="required date"
                        name="date_note"
                        v-model="form.date_note"
                        :options="availableDates"
                        :error="form.errors.date_note"
                    />
                    <SpinnerButton
                        class="btn btn-accent w-full"
                        :spin="form.processing"
                        :disabled="Object.keys(form.data()).reduce((a,b) => a || (form[b] === null), false)"
                        @click="reserve"
                    >
                        {{ (form.date_note ?? '').includes('Approval') ? 'REQUEST ' : '' }} RESERVE
                    </SpinnerButton>
                </div>
            </Transition>
        </div>
        <div class="md:w-1/2">
            <Transition mode="out-in">
                <Suspense v-if="covid.hn">
                    <CovidInfo :configs="covid" />
                    <template #fallback>
                        <FallbackSpinner />
                    </template>
                </Suspense>
            </Transition>
        </div>
    </div>
</template>

<script setup>
import FormAutocompleteKeyValue from '../../../Components/Controls/FormAutocompleteKeyValue.vue';
import {useForm} from '@inertiajs/inertia-vue3';
import FormAutocomplete from '../../../Components/Controls/FormAutocomplete.vue';
import FormSelect from '../../../Components/Controls/FormSelect.vue';
import FormRadio from '../../../Components/Controls/FormRadio.vue';
import SpinnerButton from '../../../Components/Controls/SpinnerButton.vue';
import {computed, onMounted, reactive, ref, watch} from 'vue';
import FormCheckbox from '../../../Components/Controls/FormCheckbox.vue';
import CovidInfo from '../../../Components/Helpers/CovidInfo.vue';
import FallbackSpinner from '../../../Components/Helpers/FallbackSpinner.vue';

const props = defineProps({
    configs: {type: Object, required: true},
});

const form = useForm({
    case_key: null,
    attending_staff: null,
    dialysis_type: null,
    patient_type: null,
    dialysis_at: null,
    covid_case: false,
    case_label: null,
    date_note: null,
});
const covid = reactive({
    route_lab: props.configs.covid.route_lab,
    route_vaccine: props.configs.covid.route_vaccine,
    hn: null,
    cid: null,
});
watch(
    () => form.case_key,
    (val) => {
        if (!val) {
            covid.hn = null;
            return;
        }
        let data = val.split('|');
        covid.cid = data[2];
        covid.hn = data[1];
        console.log(covid);
    }
);
onMounted(() => {
    if (props.configs.case) {
        form.case_key = props.configs.case.value;
        form.case_label = props.configs.case.label;
    }
});
watch(
    [() => form.dialysis_type, () => form.dialysis_at, () => form.covid_case],
    () => {
        if (availableDates.value.length) {
            availableDates.value = [];
            form.date_note = null;
        }
    }
);
watch(
    () => form.date_note,
    (val) => {
        if (!val) {
            return;
        }

        let index = availableDates.value.findIndex((date) => date.value === val);

        if (index === -1) {
            return;
        }

        if (availableDates.value[index].error !== undefined) {
            form.errors.date_note = availableDates.value[index].error;
        } else {
            form.errors.date_note = null;
        }
    }
);
const reserve = () => form.transform((data) => ({...data, case_record_hashed_key: data.case_key.split('|')[0]}))
    .post(props.configs.routes.orders_store, {
        preserveState: true,
        onFinish: () => {
            form.processing = false;
        },
    });

const availableDates = ref([]);
const checking = ref(false);
const checkingIncomplete = computed(() => !form.dialysis_type || !form.dialysis_at);
const checkAvailableDates = () => {
    checking.value = true;
    window.axios
        .post(props.configs.routes.slot_available_dates, form.data())
        .then(res => availableDates.value = [...res.data])
        .catch(error => console.log(error))
        .finally(() => checking.value = false);
};
</script>
