<template>
    <h2
        class="mt-6 md:mt-12 xl:mt-24 flex justify-between items-center"
        id="prescription"
    >
        <span class="form-label mb-0 text-lg italic text-complement">TPE Prescription</span>
        <button
            class="text-sm text-accent"
        >
            Copy last order
        </button>
    </h2>
    <hr class="my-4 border-b border-accent">
    <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4">
        <FormSelect
            label="access type"
            v-model="form.access_type"
            name="tpe.access_type"
            :error="$page.props.errors['tpe.access_type']"
            :options="configs.access_types"
        />
        <FormSelect
            label="access site coagulant"
            v-model="form.access_site_coagulant"
            name="tpe.access_site_coagulant"
            :error="$page.props.errors['tpe.access_site_coagulant']"
            :options="(form.access_type && form.access_type.startsWith('AV')) ? configs.av_access_sites : configs.non_av_access_sites"
            :disabled="!form.access_type || form.access_type === 'pending'"
        />
        <FormSelect
            v-model="form.dialyzer"
            name="tpe.dialyzer"
            :error="$page.props.errors['tpe.dialyzer']"
            label="dialyzer"
            :options="configs.tpe_dialyzers"
        />
    </div>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <label class="form-label">replacement</label>
    <FormCheckbox
        v-model="form.replacement_fluid_albumin"
        name="tpe.replacement_fluid_albumin"
        label="Albumin"
        class="mt-2 md:mt-4"
        :toggler="true"
    />
    <Transition name="slide-fade">
        <div
            v-if="form.replacement_fluid_albumin"
            class="grid gap-2 md:grid-cols-2 md:gap-4 xl:gap-8"
        >
            <div>
                <label class="form-label">concentrated (%)</label>
                <FormRadio
                    v-model="form.replacement_fluid_albumin_concentrated"
                    name="tpe.replacement_fluid_albumin_concentrated"
                    :error="$page.props.errors['tpe.replacement_fluid_albumin_concentrated']"
                    class="grid grid-cols-2 gap-2 md:gap-4"
                    :options="configs.replacement_fluid_albumin_concentrated"
                />
            </div>
            <FormInput
                v-model="form.replacement_fluid_albumin_volume"
                type="tel"
                name="tpe.replacement_fluid_albumin_volume"
                :error="$page.props.errors['tpe.replacement_fluid_albumin_volume']"
                label="volume (ml)"
            />
        </div>
    </Transition>
    <FormCheckbox
        v-model="form.replacement_fluid_ffp"
        name="tpe.replacement_fluid_ffp"
        label="FFP"
        class="mt-2 md:mt-4"
        :toggler="true"
    />
    <Transition name="slide-fade">
        <FormInput
            v-if="form.replacement_fluid_ffp"
            v-model="form.replacement_fluid_ffp_volume"
            type="tel"
            name="tpe.replacement_fluid_ffp_volume"
            :error="$page.props.errors['tpe.replacement_fluid_ffp_volume']"
            label="volume (ml)"
        />
    </Transition>

    <AlertMessage
        class="my-2 md:my-4 xl:my-8"
        title="Volume of exchange (l)"
        message="[1.5-2.0] EPV, EPV = 0.065 x weight (kg) x (1-hct)."
    />
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4">
        <FormSelect
            v-model="form.blood_pumb"
            name="tpe.blood_pumb"
            :error="$page.props.errors['tpe.blood_pumb']"
            label="blood pumb (ml/min)"
            :options="configs.blood_pumb"
        />
        <FormSelect
            v-model="form.filtration_pumb"
            name="tpe.filtration_pumb"
            :error="$page.props.errors['tpe.filtration_pumb']"
            label="filtration pumb (%)"
            :options="configs.tpe_filtration_pumb_options"
        />
        <FormSelect
            v-model="form.replacement_pumb"
            name="tpe.replacement_pumb"
            :error="$page.props.errors['tpe.replacement_pumb']"
            label="replacement pumb (%)"
            :options="configs.tpe_filtration_pumb_options"
        />
        <FormInput
            v-model="form.drain_pumb"
            type="tel"
            name="tpe.drain_pumb"
            :error="$page.props.errors['tpe.drain_pumb']"
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
                name="tpe.calcium_gluconate_10_percent_volume"
                :error="$page.props.errors['tpe.calcium_gluconate_10_percent_volume']"
                :options="configs.calcium_gluconate_10_percent_volumes"
                :allow-reset="true"
            />
        </div>
        <div>
            <label class="form-label">10% calcium gluconate timing (at hour)</label>
            <FormRadio
                v-model="form.calcium_gluconate_10_percent_timing"
                name="tpe.calcium_gluconate_10_percent_timing"
                :error="$page.props.errors['tpe.calcium_gluconate_10_percent_timing']"
                :options="configs.calcium_gluconate_10_percent_timings"
                :allow-reset="true"
                class="grid gap-2 md:gap-4 grid-cols-2"
                :class="{'grid-cols-3': form.calcium_gluconate_10_percent_timing}"
            />
        </div>
    </div>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4 my-2 md:my-4 xl:my-8">
        <FormSelect
            v-model="form.anticoagulant"
            name="tpe.anticoagulant"
            label="anticoagulant"
            :options="configs.anticoagulants"
            ref="anticoagulantInput"
            :error="$page.props.errors['tpe.anticoagulant']"
        />
    </div>
    <Transition name="slide-fade">
        <div
            class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4 my-2 md:my-4 xl:my-8"
            v-if="form.anticoagulant == 'none'"
        >
            <FormCheckbox
                label="anticoagulant drip via peripheral IV"
                name="anticoagulant_none_drip_via_peripheral_iv"
                v-model="form.anticoagulant_none_drip_via_peripheral_iv"
            />
            <FormCheckbox
                label="NSS 200 ml flush q 1 hour"
                name="anticoagulant_none_nss_200ml_flush_q_hour"
                v-model="form.anticoagulant_none_nss_200ml_flush_q_hour"
            />
        </div>
        <div v-else-if="form.anticoagulant == 'heparin'">
            <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4 my-2 md:my-4 xl:my-8">
                <FormInput
                    label="loading dose (iu)"
                    name="tpe.heparin_loading_dose"
                    v-model="form.heparin_loading_dose"
                    type="number"
                    pattern="\d*"
                    @autosave="validate('heparin_loading_dose')"
                    :error="errors.heparin_loading_dose ?? $page.props.errors['tpe.heparin_loading_dose']"
                    :placeholder="`[${configs.validators.heparin_loading_dose.min}, ${configs.validators.heparin_loading_dose.max}] IU`"
                />
                <FormInput
                    label="maintenance dose (iu/hour)"
                    name="tpe.heparin_maintenance_dose"
                    v-model="form.heparin_maintenance_dose"
                    type="number"
                    pattern="\d*"
                    @autosave="validate('heparin_maintenance_dose')"
                    :error="errors.heparin_maintenance_dose ?? $page.props.errors['tpe.heparin_maintenance_dose']"
                    :placeholder="`[${configs.validators.heparin_maintenance_dose.min}, ${configs.validators.heparin_maintenance_dose.max}] IU/Hour`"
                />
            </div>
            <AlertMessage
                title="Duration of maintenance (hours)"
                message="DLC/PC uses duration of dialysis. AVF/AVG uses duration of dialysis - 1."
            />
        </div>
        <div
            class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4 my-2 md:my-4 xl:my-8"
            v-else-if="form.anticoagulant == 'enoxaparin'"
        >
            <FormInput
                label="dose (ml)"
                name="tpe.enoxaparin_dose"
                v-model="form.enoxaparin_dose"
                type="number"
                @autosave="validate('enoxaparin_dose')"
                :error="errors.enoxaparin_dose ?? $page.props.errors['tpe.enoxaparin_dose']"
                :placeholder="`[${configs.validators.enoxaparin_dose.min}, ${configs.validators.enoxaparin_dose.max}] ml`"
            />
        </div>
        <div
            class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4 my-2 md:my-4 xl:my-8"
            v-else-if="form.anticoagulant == 'fondaparinux'"
        >
            <FormSelect
                label="bolus dose (iu)"
                name="tpe.fondaparinux_bolus_dose"
                v-model="form.fondaparinux_bolus_dose"
                :options="configs.fondaparinux_bolus_doses"
                :error="$page.props.errors['tpe.fondaparinux_bolus_dose']"
            />
        </div>
        <div
            class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4 my-2 md:my-4 xl:my-8"
            v-else-if="form.anticoagulant == 'tinzaparin'"
        >
            <FormInput
                label="dose (iu)"
                name="tpe.tinzaparin_dose"
                v-model="form.tinzaparin_dose"
                type="number"
                pattern="\d*"
                @autosave="validate('tinzaparin_dose')"
                :error="errors.tinzaparin_dose ?? $page.props.errors['tpe.tinzaparin_dose']"
                :placeholder="`[${configs.validators.tinzaparin_dose.min}, ${configs.validators.tinzaparin_dose.max}] IU`"
            />
        </div>
    </Transition>

    <FormSelectOther
        :placeholder="selectOther.placeholder"
        ref="selectOtherInput"
        @closed="(val) => selectOtherClosed(val, true)"
    />
</template>

<script setup>
import FormCheckbox from '@/Components/Controls/FormCheckbox.vue';
import FormInput from '@/Components/Controls/FormInput.vue';
import FormSelect from '@/Components/Controls/FormSelect.vue';
import FormRadio from '@/Components/Controls/FormRadio.vue';
import AlertMessage from '@/Components/Helpers/AlertMessage.vue';
import FormSelectOther from '@/Components/Controls/FormSelectOther.vue';
import { watch, reactive, ref } from 'vue';
import { useSelectOther } from '@/functions/useSelectOther.js';

const props = defineProps({
    modelValue: { type: Object, required: true },
    formConfigs: { type: Object, required: true },
});
const emit = defineEmits(['update:modelValue', 'autosave']);

const form = reactive({...props.modelValue});
const reset = {
    anticoagulant: form.anticoagulant ?? null,
    access_type: form.access_type ?? null,
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
            reset.anticoagulant = val.anticoagulant;
        }

        // reset access_site_coagulant
        if (
            (val.access_type === 'pending')
            || (['DLC', 'Perm cath'].includes(val.access_type) && !['DLC', 'Perm cath'].includes(reset.access_type))
            || (['AVF', 'AVG'].includes(val.access_type) && !['AVF', 'AVG'].includes(reset.access_type))
        ) {
            val.access_site_coagulant = '';
            reset.access_type = val.access_type;
        }

        // reset replacement_fluid_albumin
        if (!val.replacement_fluid_albumin) {
            val.replacement_fluid_albumin_concentrated = null;
            val.replacement_fluid_albumin_volume = null;
        }

        // reset replacement_fluid_ffp
        if (!val.replacement_fluid_ffp) {
            val.replacement_fluid_ffp_volume = null;
        }

        emit('update:modelValue', val);
        emit('autosave');
    },
    { deep: true }
);
const configs = reactive({...props.formConfigs});
if (form.anticoagulant && configs.anticoagulants.findIndex(item => item.value == form.anticoagulant) === -1) {
    configs.anticoagulants.push({ value: form.anticoagulant, label: form.anticoagulant });
}
const anticoagulantInput = ref(null);
watch (
    () => form.anticoagulant,
    (val) => {
        if (val.toLowerCase() !== 'other') {
            return;
        }

        selectOther.placeholder = 'Other anticoagulant';
        selectOther.configs = configs.anticoagulants;
        selectOther.input = anticoagulantInput.value;
        selectOtherInput.value.open();
    }
);
const { selectOtherInput, selectOther, selectOtherClosed } = useSelectOther();

const errors = reactive({
    heparin_loading_dose: null,
    heparin_maintenance_dose: null,
    enoxaparin_dose: null,
    tinzaparin_dose: null,
});
const validate = (fieldname) => {
    let validator = configs.validators[fieldname];
    const value = validator.type == 'integer' ? parseInt(form[fieldname]) :  parseFloat(form[fieldname]);
    if (value < validator.min || value > validator.max) {
        errors[fieldname] = `${form[fieldname]} could not be saved. Accept range [${validator.min}, ${validator.max}].`;
        form[fieldname] = null;
    } else {
        errors[fieldname] = '';
    }
};
</script>