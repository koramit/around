<template>
    <template v-if="form.hf_perform_at !== undefined">
        <h2
            class="mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement"
            id="prescription"
        >
            HF Prescription
        </h2>
        <hr class="my-4 border-b border-accent">
        <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4">
            <div>
                <label class="form-label">perform at :</label>
                <FormRadio
                    class="grid grid-cols-2 gap-x-2"
                    name="hd.hf_perform_at"
                    v-model="form.hf_perform_at"
                    :options="configs.hf_perform_at"
                    :error="$page.props.errors['hd.hf_perform_at']"
                />
            </div>
            <div>
                <label
                    for=""
                    class="form-label"
                >uf (ml.) :</label>
                <div class="grid gap-2 md:grid-cols-2">
                    <FormInput
                        name="hd.hf_ultrafiltration_min"
                        v-model="form.hf_ultrafiltration_min"
                        pattern="\d*"
                        type="number"
                        @autosave="validate('hf_ultrafiltration_min')"
                        :error="errors.hf_ultrafiltration_min ?? $page.props.errors['hd.hf_ultrafiltration_min']"
                        :placeholder="`min [${configs.validators.hf_ultrafiltration_min.min}, ${configs.validators.hf_ultrafiltration_min.max}]`"
                    />
                    <FormInput
                        name="hd.hf_ultrafiltration_max"
                        v-model="form.hf_ultrafiltration_max"
                        pattern="\d*"
                        type="number"
                        @autosave="validate('hf_ultrafiltration_max')"
                        :error="errors.hf_ultrafiltration_max ?? $page.props.errors['hd.hf_ultrafiltration_max']"
                        :placeholder="`max [${configs.validators.hf_ultrafiltration_max.min}, ${configs.validators.hf_ultrafiltration_max.max}]`"
                    />
                </div>
            </div>
        </div>
    </template>
    <h2
        class="mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement"
        id="prescription"
    >
        HD Prescription
    </h2>
    <hr class="my-4 border-b border-accent">
    <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4">
        <FormSelect
            label="access type"
            v-model="form.access_type"
            name="hd.access_type"
            :options="configs.access_types"
            :error="$page.props.errors['hd.access_type']"
        />
        <FormSelect
            label="access site coagulant"
            v-model="form.access_site_coagulant"
            name="hd.access_site_coagulant"
            :options="(form.access_type && form.access_type.startsWith('AV')) ? configs.av_access_sites : configs.non_av_access_sites"
            :disabled="!form.access_type || form.access_type === 'pending'"
            :error="$page.props.errors['hd.access_site_coagulant']"
        />
        <FormSelect
            v-model="form.dialyzer"
            name="hd.dialyzer"
            label="dialyzer"
            :options="configs.dialyzers"
            :error="$page.props.errors['hd.dialyzer']"
        />
        <FormSelect
            v-model="form.dialysate"
            name="hd.dialysate"
            label="dialysate"
            :options="configs.dialysates"
            :error="$page.props.errors['hd.dialysate']"
        />
        <FormSelect
            v-model="form.blood_flow_rate"
            name="hd.blood_flow_rate"
            :options="configs.blood_flow_rates"
            label="blood flow rate (ml/min)"
            :error="$page.props.errors['hd.blood_flow_rate']"
        />
        <FormSelect
            v-model:model-value="form.dialysate_flow_rate"
            v-model:model-checkbox="form.reverse_dialysate_flow"
            :options="configs.dialysate_flow_rates"
            name="hd.dialysate_flow_rate"
            label="dialysate flow (ml/min)"
            switch-label="Reverse flow"
            :error="$page.props.errors['hd.dialysate_flow_rate']"
        />
        <FormSelect
            v-model="form.dialysate_temperature"
            name="hd.dialysate_temperature"
            :options="configs.dialysate_temperatures"
            label="dialysate temperature (℃)"
            :error="$page.props.errors['hd.dialysate_temperature']"
        />
    </div>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <div class="grid md:grid-cols-2 gap-2 xl:gap-8 my-2 md:my-4 xl:mt-8">
        <div>
            <FormInput
                label="sodium"
                name="hd.sodium"
                v-model="form.sodium"
                type="number"
                pattern="\d*"
                @autosave="validate('sodium')"
                :error="errors.sodium ?? $page.props.errors['hd.sodium']"
                :placeholder="`[${configs.validators.sodium.min}, ${configs.validators.sodium.max}]`"
            />
            <FormCheckbox
                class="mt-2 md:mt-4 xl:mt-8"
                label="Sodium profile"
                name="hd.sodium_profile"
                v-model="form.sodium_profile"
                :toggler="true"
            />
            <transition name="slide-fade">
                <div
                    v-if="form.sodium_profile"
                    class="grid gap-2 md:gap-4 xl:gap-8 mt-2 md:mt-4 xl:mt-8"
                >
                    <FormInput
                        label="start"
                        name="hd.sodium_profile_start"
                        v-model="form.sodium_profile_start"
                        type="number"
                        pattern="\d*"
                        :error="errors.sodium_profile_start ?? $page.props.errors['hd.sodium_profile_start']"
                    />
                    <FormInput
                        label="end"
                        name="hd.sodium_profile_end"
                        v-model="form.sodium_profile_end"
                        type="number"
                        pattern="\d*"
                        :error="errors.sodium_profile_end ?? $page.props.errors['hd.sodium_profile_end']"
                    />
                </div>
            </transition>
        </div>
        <FormSelect
            v-model="form.bicarbonate"
            name="hd.bicarbonate"
            label="bicarbonate"
            :options="configs.bicarbonates"
            :error="$page.props.errors['hd.bicarbonate']"
        />
    </div>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4 my-2 md:my-4 xl:my-8">
        <FormSelect
            v-model="form.anticoagulant"
            name="hd.anticoagulant"
            label="anticoagulant"
            :options="configs.anticoagulants"
            ref="anticoagulantInput"
            :error="$page.props.errors['hd.anticoagulant']"
        />
    </div>
    <transition name="slide-fade">
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
                    name="hd.heparin_loading_dose"
                    v-model="form.heparin_loading_dose"
                    type="number"
                    pattern="\d*"
                    @autosave="validate('heparin_loading_dose')"
                    :error="errors.heparin_loading_dose ?? $page.props.errors['hd.heparin_loading_dose']"
                    :placeholder="`[${configs.validators.heparin_loading_dose.min}, ${configs.validators.heparin_loading_dose.max}] IU`"
                />
                <FormInput
                    label="maintenance dose (iu/hour)"
                    name="hd.heparin_maintenance_dose"
                    v-model="form.heparin_maintenance_dose"
                    type="number"
                    pattern="\d*"
                    @autosave="validate('heparin_maintenance_dose')"
                    :error="errors.heparin_maintenance_dose ?? $page.props.errors['hd.heparin_maintenance_dose']"
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
                name="hd.enoxaparin_dose"
                v-model="form.enoxaparin_dose"
                type="number"
                @autosave="validate('enoxaparin_dose')"
                :error="errors.enoxaparin_dose ?? $page.props.errors['hd.enoxaparin_dose']"
                :placeholder="`[${configs.validators.enoxaparin_dose.min}, ${configs.validators.enoxaparin_dose.max}] ml`"
            />
        </div>
        <div
            class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4 my-2 md:my-4 xl:my-8"
            v-else-if="form.anticoagulant == 'fondaparinux'"
        >
            <FormSelect
                label="bolus dose (iu)"
                name="hd.fondaparinux_bolus_dose"
                v-model="form.fondaparinux_bolus_dose"
                :options="configs.fondaparinux_bolus_doses"
                :error="$page.props.errors['hd.fondaparinux_bolus_dose']"
            />
        </div>
        <div
            class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4 my-2 md:my-4 xl:my-8"
            v-else-if="form.anticoagulant == 'tinzaparin'"
        >
            <FormInput
                label="dose (iu)"
                name="hd.tinzaparin_dose"
                v-model="form.tinzaparin_dose"
                type="number"
                pattern="\d*"
                @autosave="validate('tinzaparin_dose')"
                :error="errors.tinzaparin_dose ?? $page.props.errors['hd.tinzaparin_dose']"
                :placeholder="`[${configs.validators.tinzaparin_dose.min}, ${configs.validators.tinzaparin_dose.max}] IU`"
            />
        </div>
    </transition>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4">
        <div>
            <label
                for=""
                class="form-label"
            >uf (ml.)</label>
            <div class="grid gap-2 md:grid-cols-2">
                <FormInput
                    name="hd.ultrafiltration_min"
                    v-model="form.ultrafiltration_min"
                    pattern="\d*"
                    type="number"
                    @autosave="validate('ultrafiltration_min')"
                    :error="errors.ultrafiltration_min ?? $page.props.errors['hd.ultrafiltration_min']"
                    :placeholder="`min [${configs.validators.ultrafiltration_min.min}, ${configs.validators.ultrafiltration_min.max}]`"
                />
                <FormInput
                    name="hd.ultrafiltration_max"
                    v-model="form.ultrafiltration_max"
                    pattern="\d*"
                    type="number"
                    @autosave="validate('ultrafiltration_max')"
                    :error="errors.ultrafiltration_max ?? $page.props.errors['hd.ultrafiltration_max']"
                    :placeholder="`max [${configs.validators.ultrafiltration_max.min}, ${configs.validators.ultrafiltration_max.max}]`"
                />
            </div>
        </div>
        <FormInput
            label="dry weight (kg.)"
            v-model="form.dry_weight"
            name="hd.dry_weight"
            type="number"
            :error="$page.props.errors['hd.dry_weight']"
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
        <FormSelect
            v-model="form.glucose_50_percent_iv_at"
            name="glucose_50_percent_iv_at"
            label="50% glucose iv (at hour)"
            :options="configs.glucose_50_percent_iv_at"
        />
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
            name="hd.nutrition_iv_type"
            :error="$page.props.errors['hd.nutrition_iv_type']"
        />
        <FormInput
            label="nutrition iv volume (ml)"
            v-model="form.nutrition_iv_volume"
            name="hd.nutrition_iv_volume"
            type="number"
            pattern="\d*"
            :error="$page.props.errors['hd.nutrition_iv_volume']"
        />
    </div>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <FormCheckbox
        label="Post Dialysis BW"
        name="post_dialysis_bw"
        v-model="form.post_dialysis_bw"
        :toggler="true"
    />

    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <label class="form-label">transfustion :</label>
    <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4">
        <FormInput
            label="prc volume (unit)"
            name="hd.prc_volume"
            v-model="form.prc_volume"
            type="number"
            :error="$page.props.errors['hd.prc_volume']"
        />
        <FormInput
            label="ffp volume (ml)"
            name="hd.ffp_volume"
            v-model="form.ffp_volume"
            type="number"
            pattern="\d*"
            :error="$page.props.errors['hd.ffp_volume']"
        />
        <FormInput
            label="platelet volume (unit)"
            name="hd.platelet_volume"
            v-model="form.platelet_volume"
            type="number"
            :error="$page.props.errors['hd.platelet_volume']"
        />
        <FormInput
            label="other"
            name="hd.transfusion_other"
            v-model="form.transfusion_other"
            :error="$page.props.errors['hd.transfusion_other']"
        />
    </div>

    <FormSelectOther
        :placeholder="selectOther.placeholder"
        ref="selectOtherInput"
        @closed="(val) => selectOtherClosed(val, true)"
    />
</template>
<script setup>
import FormCheckbox from '@/Components/Controls/FormCheckbox';
import FormInput from '@/Components/Controls/FormInput';
import FormSelect from '@/Components/Controls/FormSelect';
import FormSelectOther from '@/Components/Controls/FormSelectOther';
import FormRadio from '@/Components/Controls/FormRadio';
import AlertMessage from '@/Components/Helpers/AlertMessage';
import { reactive, ref } from 'vue';
import { watch } from 'vue';
import { useSelectOther } from '@/functions/useSelectOther';

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

        // reset sodium profile
        if (!val.sodium_profile) {
            val.sodium_profile_start = null;
            val.sodium_profile_end = null;
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
    glucose_50_percent_iv_volume: null,
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