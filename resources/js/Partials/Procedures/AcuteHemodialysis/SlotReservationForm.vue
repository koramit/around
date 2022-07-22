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
            <FormAutocomplete
                label="attending"
                name="attending_staff"
                v-model="form.attending_staff"
                :endpoint="configs.routes.resources_api_staffs"
                :params="configs.routes.staffs_scope_params"
                :error="form.errors.attending_staff"
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
            <SpinnerButton
                class="btn btn-complement w-full"
                :spin="checking"
                :disabled="checkingIncomplete"
                @click="checkAvailableDates"
            >
                Check available dates
            </SpinnerButton>
            <FormSelect
                label="required date"
                name="date_note"
                v-model="form.date_note"
                :options="availableDates"
                :disabled="availableDates.length === 0"
            />
        </div>
        <div class="md:w-1/2">
            <Suspense
                v-if="covid.hn"
            >
                <CovidInfo
                    :configs="covid"
                />
                <template #fallback>
                    <FallbackSpinner />
                </template>
            </Suspense>
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
import {computed, reactive, ref, watch} from 'vue';
import FormCheckbox from '../../../Components/Controls/FormCheckbox.vue';
import CovidInfo from '../../../Components/Helpers/CovidInfo.vue';
import FallbackSpinner from '../../../Components/Helpers/FallbackSpinner.vue';

const props = defineProps({
    configs: {type: Object, required: true},
});

const form = useForm({
    case_key: null,
    case_label: null,
    attending_staff: null,
    dialysis_type: null,
    dialysis_at: null,
    covid_case: false,
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
    }
);
watch(
    () => form,
    () => {
        if (availableDates.value.length) {
            availableDates.value = [];
        }
    },
    {deep: true}
);

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