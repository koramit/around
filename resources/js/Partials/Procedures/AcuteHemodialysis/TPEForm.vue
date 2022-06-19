<template>
    <h2
        class="mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement"
        id="prescription"
    >
        TPE Prescription
    </h2>
    <hr class="my-4 border-b border-accent">
    <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4">
        <FormSelect
            label="access type"
            v-model="form.access_type"
            name="access_type"
            :options="configs.access_types"
        />
        <FormSelect
            label="access site coagulant"
            v-model="form.access_site_coagulant"
            name="access_site_coagulant"
            :options="(form.access_type && form.access_type.startsWith('AV')) ? configs.av_access_sites : configs.non_av_access_sites"
            :disabled="!form.access_type || form.access_type === 'รอใส่สาย'"
        />
        <FormSelect
            v-model="form.dialyzer"
            name="dialyzer"
            label="dialyzer"
            :options="configs.tpe_dialyzers"
        />
    </div>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <label class="form-label">replacement</label>
    <FormCheckbox
        v-model="form.replacement_fluid_albumin"
        name="replacement_fluid_albumin"
        label="Albumin"
        class="mt-2 md:mt-4"
        :toggler="true"
    />
    <transition name="slide-fade">
        <div
            v-if="form.replacement_fluid_albumin"
            class="grid gap-2 md:grid-cols-2 md:gap-4 xl:gap-8"
        >
            <div>
                <label class="form-label">concentrated (%)</label>
                <FormRadio
                    v-model="form.replacement_fluid_albumin"
                    name="replacement_fluid_albumin_concentrated"
                    class="grid grid-cols-2 gap-2 md:gap-4"
                    :options="['3', '4']"
                />
            </div>
            <FormInput
                v-model="form.replacement_fluid_albumin_volume"
                type="tel"
                name="replacement_fluid_albumin_volume"
                label="volume (ml)"
            />
        </div>
    </transition>
    <FormCheckbox
        v-model="form.replacement_fluid_ffp"
        name="replacement_fluid_ffp"
        label="FFP"
        class="mt-2 md:mt-4"
        :toggler="true"
    />
    <transition name="slide-fade">
        <FormInput
            v-if="form.replacement_fluid_ffp"
            v-model="form.replacement_fluid_albumin_volume"
            type="tel"
            name="replacement_fluid_albumin_volume"
            label="volume (ml)"
        />
    </transition>

    <AlertMessage
        class="my-2 md:my-4 xl:my-8"
        title="Volume of exchange (l)"
        message="[1.5-2.0] EPV, EPV = 0.065 x weight (kg) x (1-hct)."
    />
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4">
        <FormSelect
            v-model="form.blood_pumb"
            name="blood_pumb"
            label="blood pumb (ml/min)"
            :options="['150', '200']"
        />
        <FormSelect
            v-model="form.filtration_pumb"
            name="filtration_pumb"
            label="filtration pumb (%)"
            :options="configs.tpe_filtration_pumb_options"
        />
        <FormSelect
            v-model="form.replacement_pumb"
            name="replacement_pumb"
            label="replacement pumb (%)"
            :options="configs.tpe_filtration_pumb_options"
        />
        <FormInput
            v-model="form.drain_pumb"
            type="tel"
            name="drain_pumb"
            label="drain pumb (%)"
        />
    </div>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4">
        <div>
            <label class="form-label">10% calcium gluconate volume (ml)</label>
            <FormRadio
                v-model="form.calcium_gluconate_10_percent_volume"
                class="grid gap-2 md:gap-4"
                :class="{
                    'grid-cols-3': !form.calcium_gluconate_10_percent_volume,
                    'grid-cols-2 sm:grid-cols-4': form.calcium_gluconate_10_percent_volume
                }"
                name="calcium_gluconate_10_percent_volume"
                :options="['10','20','30']"
                :allow-reset="true"
            />
        </div>
        <div>
            <label class="form-label">10% calcium gluconate timing (at hour)</label>
            <FormRadio
                v-model="form.calcium_gluconate_10_percent_timing"
                name="calcium_gluconate_10_percent_timing"
                :options="['1' , '2']"
                :allow-reset="true"
                class="grid gap-2 md:gap-4 grid-cols-2"
                :class="{'grid-cols-3': form.calcium_gluconate_10_percent_timing}"
            />
        </div>
    </div>
</template>

<script setup>
import FormCheckbox from '@/Components/Controls/FormCheckbox';
import FormInput from '@/Components/Controls/FormInput';
import FormSelect from '@/Components/Controls/FormSelect';
import FormRadio from '@/Components/Controls/FormRadio';
import AlertMessage from '@/Components/Helpers/AlertMessage';
import { reactive } from 'vue';
import { watch } from 'vue';

const props = defineProps({
    modelValue: { type: Object, required: true },
    formConfigs: { type: Object, required: true },
});
const emit = defineEmits(['update:modelValue', 'autosave']);

const form = reactive({...props.modelValue});
const reset = {
    anticoagulant: null
};

watch (
    () => form,
    (val) => {
        if (val.anticoagulant !== reset.anticoagulant) {
            val.anticoagulant_none_drip_via_peripheral_iv = false;
            val.anticoagulant_none_nss_200ml_flush_q_hour = false;
            val.heparin_loading_dose = null;
            val.heparin_maintenance_dose = null;
            val.enoxaparin_dose = null;
            val.fondaparinux_bolus_dose = null;
            val.tinzaparin_dose = null;
            val.anticoagulant_other = null;
            reset.anticoagulant = val.anticoagulant;
        }

        emit('update:modelValue', val);
        emit('autosave');
    },
    { deep: true }
);

// const errors = reactive({
//     heparin_loading_dose: null,
//     heparin_maintenance_dose: null,
//     enoxaparin_dose: null,
//     tinzaparin_dose: null,
//     ultrafiltration: null,
//     glucose_50_percent_iv_volume: null,
// });
// const validate = (fieldname) => {
//     let validator = configs.validators.filter((rule) => rule.name === fieldname)[0];
//     const value = validator.type == 'integer' ? parseInt(form[fieldname]) :  parseFloat(form[fieldname]);
//     if (value < validator.min || value > validator.max) {
//         errors[fieldname] = `${form[fieldname]} could not be saved. Accept range [${validator.min}, ${validator.max}].`;
//         setTimeout(() => form[fieldname] = null, 1500);
//     } else {
//         errors[fieldname] = '';
//     }
// };

const configs = reactive({...props.formConfigs});
</script>