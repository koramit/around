<template>
    <h2
        class="mt-6 md:mt-12 xl:mt-24 flex justify-between items-center"
        id="prescription"
    >
        <span class="form-label mb-0 text-lg italic text-complement">HF Prescription</span>
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
            name="hf.access_type"
            :options="configs.access_types"
            :error="$page.props.errors['hf.access_type']"
        />
        <FormSelect
            label="access site coagulant"
            v-model="form.access_site_coagulant"
            name="hf.access_site_coagulant"
            :options="(form.access_type && form.access_type.startsWith('AV')) ? configs.av_access_sites : configs.non_av_access_sites"
            :disabled="!form.access_type || form.access_type === 'pending'"
            :error="$page.props.errors['hf.access_site_coagulant']"
        />
        <FormSelect
            v-model="form.dialyzer"
            name="hf.dialyzer"
            label="dialyzer"
            :options="configs.hf_dialyzers"
            :error="$page.props.errors['hf.dialyzer']"
        />
        <FormSelect
            v-model="form.blood_flow_rate"
            name="hf.blood_flow_rate"
            :options="configs.blood_flow_rates"
            label="blood flow rate (ml/min)"
            :error="$page.props.errors['hf.blood_flow_rate']"
        />
    </div>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4 my-2 md:my-4 xl:my-8">
        <FormSelect
            v-model="form.anticoagulant"
            name="hf.anticoagulant"
            label="anticoagulant"
            :options="configs.anticoagulants"
            ref="anticoagulantInput"
            :error="$page.props.errors['hf.anticoagulant']"
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
                    name="hf.heparin_loading_dose"
                    v-model="form.heparin_loading_dose"
                    type="number"
                    pattern="\d*"
                    @autosave="validate('heparin_loading_dose')"
                    :error="errors.heparin_loading_dose ?? $page.props.errors['hf.heparin_loading_dose']"
                    :placeholder="`[${configs.validators.heparin_loading_dose.min}, ${configs.validators.heparin_loading_dose.max}] IU`"
                />
                <FormInput
                    label="maintenance dose (iu/hour)"
                    name="hf.heparin_maintenance_dose"
                    v-model="form.heparin_maintenance_dose"
                    type="number"
                    pattern="\d*"
                    @autosave="validate('heparin_maintenance_dose')"
                    :error="errors.heparin_maintenance_dose ?? $page.props.errors['hf.heparin_maintenance_dose']"
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
                name="hf.enoxaparin_dose"
                v-model="form.enoxaparin_dose"
                type="number"
                @autosave="validate('enoxaparin_dose')"
                :error="errors.enoxaparin_dose ?? $page.props.errors['hf.enoxaparin_dose']"
                :placeholder="`[${configs.validators.enoxaparin_dose.min}, ${configs.validators.enoxaparin_dose.max}] ml`"
            />
        </div>
        <div
            class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4 my-2 md:my-4 xl:my-8"
            v-else-if="form.anticoagulant == 'fondaparinux'"
        >
            <FormSelect
                label="bolus dose (iu)"
                name="hf.fondaparinux_bolus_dose"
                v-model="form.fondaparinux_bolus_dose"
                :options="configs.fondaparinux_bolus_doses"
                :error="$page.props.errors['hf.fondaparinux_bolus_dose']"
            />
        </div>
        <div
            class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4 my-2 md:my-4 xl:my-8"
            v-else-if="form.anticoagulant == 'tinzaparin'"
        >
            <FormInput
                label="dose (iu)"
                name="hf.tinzaparin_dose"
                v-model="form.tinzaparin_dose"
                type="number"
                pattern="\d*"
                @autosave="validate('tinzaparin_dose')"
                :error="errors.tinzaparin_dose ?? $page.props.errors['hf.tinzaparin_dose']"
                :placeholder="`[${configs.validators.tinzaparin_dose.min}, ${configs.validators.tinzaparin_dose.max}] IU`"
            />
        </div>
    </Transition>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4">
        <div>
            <label
                for=""
                class="form-label"
            >uf (ml.)</label>
            <div class="grid gap-2 md:grid-cols-2">
                <FormInput
                    name="hf.ultrafiltration_min"
                    v-model="form.ultrafiltration_min"
                    pattern="\d*"
                    type="number"
                    @autosave="validate('ultrafiltration_min')"
                    :error="errors.ultrafiltration_min ?? $page.props.errors['hf.ultrafiltration_min']"
                    :placeholder="`min [${configs.validators.ultrafiltration_min.min}, ${configs.validators.ultrafiltration_min.max}]`"
                />
                <FormInput
                    name="hf.ultrafiltration_max"
                    v-model="form.ultrafiltration_max"
                    pattern="\d*"
                    type="number"
                    @autosave="validate('ultrafiltration_max')"
                    :error="errors.ultrafiltration_max ?? $page.props.errors['hf.ultrafiltration_max']"
                    :placeholder="`max [${configs.validators.ultrafiltration_max.min}, ${configs.validators.ultrafiltration_max.max}]`"
                />
            </div>
        </div>
        <FormInput
            label="dry weight (kg.)"
            v-model="form.dry_weight"
            name="hf.dry_weight"
            type="number"
            :error="$page.props.errors['hf.dry_weight']"
        />
        <div>
            <label class="form-label">50% Glucose IV volume (ml)</label>
            <FormRadio
                class="grid grid-cols-2 gap-x-2"
                :class="{'grid-cols-3': form.glucose_50_percent_iv_volume}"
                name="glucose_50_percent_iv_volume"
                v-model="form.glucose_50_percent_iv_volume"
                :options="configs.glucose_50_percent_iv_volumes"
                :allow-reset="true"
            />
        </div>
        <div>
            <label class="form-label">20% albumin prime (ml)</label>
            <FormRadio
                class="grid grid-cols-2 gap-x-2"
                :class="{'grid-cols-3': form.albumin_20_percent_prime}"
                name="albumin_20_percent_prime"
                v-model="form.albumin_20_percent_prime"
                :options="configs.albumin_20_percent_primes"
                :allow-reset="true"
            />
        </div>
        <FormInput
            label="nutrition iv type"
            v-model="form.nutrition_iv_type"
            name="hf.nutrition_iv_type"
            :error="$page.props.errors['hf.nutrition_iv_type']"
        />
        <FormInput
            label="nutrition iv volume (ml)"
            v-model="form.nutrition_iv_volume"
            name="hf.nutrition_iv_volume"
            type="number"
            pattern="\d*"
            :error="$page.props.errors['hf.nutrition_iv_volume']"
        />
    </div>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <label class="form-label">transfustion :</label>
    <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4">
        <FormInput
            label="prc volume (unit)"
            name="hf.prc_volume"
            v-model="form.prc_volume"
            type="number"
            :error="$page.props.errors['hf.prc_volume']"
        />
        <FormInput
            label="ffp volume (ml)"
            name="hf.ffp_volume"
            v-model="form.ffp_volume"
            type="number"
            pattern="\d*"
            :error="$page.props.errors['hf.ffp_volume']"
        />
        <FormInput
            label="platelet volume (unit)"
            name="hf.platelet_volume"
            v-model="form.platelet_volume"
            type="number"
            :error="$page.props.errors['hf.platelet_volume']"
        />
        <FormInput
            label="other"
            name="hf.transfusion_other"
            v-model="form.transfusion_other"
            :error="$page.props.errors['hf.transfusion_other']"
        />
    </div>

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
import FormSelectOther from '@/Components/Controls/FormSelectOther.vue';
import AlertMessage from '@/Components/Helpers/AlertMessage.vue';
import { reactive, ref } from 'vue';
import { watch } from 'vue';
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
            val.anticoagulant_other = null;
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
    ultrafiltration: null,
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