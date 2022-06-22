<template>
    <template v-if="form.hf !== undefined">
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
                    name="with_hf"
                    v-model="form.hf.perform_at"
                    :options="['Pre HD', 'Post HD']"
                />
            </div>
            <div>
                <label
                    for=""
                    class="form-label"
                >uf (ml.) :</label>
                <div class="grid gap-2 md:grid-cols-2">
                    <FormInput
                        name="ultrafiltration_min"
                        v-model="form.hf.ultrafiltration_min"
                        pattern="\d*"
                        type="number"
                        @autosave="validate('ultrafiltration_min')"
                        :error="errors.ultrafiltration_min"
                        placeholder="min [0, 5500]"
                    />
                    <FormInput
                        name="ultrafiltration_max"
                        v-model="form.hf.ultrafiltration_max"
                        pattern="\d*"
                        type="number"
                        @autosave="validate('ultrafiltration_max')"
                        :error="errors.ultrafiltration_max"
                        placeholder="max [0, 5500]"
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
            name="access_type"
            :options="configs.access_types"
        />
        <FormSelect
            ref="access_site_coagulant_input"
            label="access site coagulant"
            v-model="form.access_site_coagulant"
            name="access_site_coagulant"
            :options="(form.access_type && form.access_type.startsWith('AV')) ? configs.av_access_sites : configs.non_av_access_sites"
            :disabled="!form.access_type || form.access_type === 'pending'"
        />
        <FormSelect
            v-model="form.dialyzer"
            name="dialyzer"
            label="dialyzer"
            :options="configs.dialyzers"
        />
        <FormSelect
            v-model="form.dialysate"
            name="dialysate"
            label="dialysate"
            :options="configs.dialysates"
        />
        <FormSelect
            v-model="form.blood_flow_rate"
            name="blood_flow_rate"
            :options="configs.blood_flow_rates"
            label="blood flow rate (ml/min)"
        />
        <FormSelect
            v-model:model-value="form.dialysate_flow_rate"
            v-model:model-checkbox="form.reverse_dialysate_flow"
            :options="configs.dialysate_flow_rates"
            name="dialysate_flow_rate"
            label="dialysate flow (ml/min)"
            switch-label="Reverse flow"
        />
        <FormSelect
            v-model="form.dialysate_temperature"
            name="dialysate_temperature"
            :options="configs.dialysate_temperatures"
            label="dialysate temperature (℃)"
        />
    </div>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <div class="grid md:grid-cols-2 gap-2 xl:gap-8 my-2 md:my-4 xl:mt-8">
        <div>
            <FormInput
                label="sodium"
                name="sodium"
                v-model="form.sodium"
                type="number"
                pattern="\d*"
                @autosave="validate('sodium')"
                :error="errors.sodium"
                placeholder="[128, 145]"
            />
            <FormCheckbox
                class="mt-2 md:mt-4 xl:mt-8"
                label="Sodium profile"
                name="sodium_profile"
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
                        name="sodium_profile_start"
                        v-model="form.sodium_profile_start"
                        type="number"
                        pattern="\d*"
                        :error="errors.sodium_profile_start"
                    />
                    <FormInput
                        label="end"
                        name="sodium_profile_end"
                        v-model="form.sodium_profile_end"
                        type="number"
                        pattern="\d*"
                        :error="errors.sodium_profile_end"
                    />
                </div>
            </transition>
        </div>
        <FormSelect
            v-model="form.bicarbonate"
            name="bicarbonate"
            label="bicarbonate"
            :options="configs.bicarbonates"
        />
    </div>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4 my-2 md:my-4 xl:my-8">
        <FormSelect
            v-model="form.anticoagulant"
            name="anticoagulant"
            label="anticoagulant"
            :options="configs.anticoagulants"
            ref="anticoagulantInput"
        />
    </div>
    <transition name="slide-fade">
        <div
            class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4 my-2 md:my-4 xl:my-8"
            v-if="form.anticoagulant == 'None'"
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
        <div v-else-if="form.anticoagulant == 'Heparin'">
            <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4 my-2 md:my-4 xl:my-8">
                <FormInput
                    label="loading dose (iu)"
                    name="heparin_loading_dose"
                    v-model="form.heparin_loading_dose"
                    type="number"
                    pattern="\d*"
                    @autosave="validate('heparin_loading_dose')"
                    :error="errors.heparin_loading_dose"
                    placeholder="[250, 2000] IU"
                />
                <FormInput
                    label="maintenance dose (iu/hour)"
                    name="heparin_maintenance_dose"
                    v-model="form.heparin_maintenance_dose"
                    type="number"
                    pattern="\d*"
                    @autosave="validate('heparin_maintenance_dose')"
                    :error="errors.heparin_maintenance_dose"
                    placeholder="[0, 1500] IU/Hour"
                />
            </div>
            <AlertMessage
                title="Duration of maintenance (hours)"
                message="DLC/PC uses duration of dialysis. AVF/AVG uses duration of dialysis - 1."
            />
        </div>
        <div
            class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4 my-2 md:my-4 xl:my-8"
            v-else-if="form.anticoagulant == 'Enoxaparin'"
        >
            <FormInput
                label="dose (ml)"
                name="enoxaparin_dose"
                v-model="form.enoxaparin_dose"
                type="number"
                @autosave="validate('enoxaparin_dose')"
                :error="errors.enoxaparin_dose"
                placeholder="[0.3, 0.8] ml"
            />
        </div>
        <div
            class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4 my-2 md:my-4 xl:my-8"
            v-else-if="form.anticoagulant == 'Fondaparinux'"
        >
            <FormSelect
                label="bolus dose (iu)"
                name="fondaparinux_bolus_dose"
                v-model="form.fondaparinux_bolus_dose"
                :options="['500', '750']"
            />
        </div>
        <div
            class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4 my-2 md:my-4 xl:my-8"
            v-else-if="form.anticoagulant == 'Tinzaparin'"
        >
            <FormInput
                label="dose (iu)"
                name="tinzaparin_dose"
                v-model="form.tinzaparin_dose"
                type="number"
                pattern="\d*"
                @autosave="validate('tinzaparin_dose')"
                :error="errors.tinzaparin_dose"
                placeholder="[1500, 3500] IU"
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
                    name="ultrafiltration_min"
                    v-model="form.ultrafiltration_min"
                    pattern="\d*"
                    type="number"
                    @autosave="validate('ultrafiltration_min')"
                    :error="errors.ultrafiltration_min"
                    placeholder="min [0, 5500]"
                />
                <FormInput
                    name="ultrafiltration_max"
                    v-model="form.ultrafiltration_max"
                    pattern="\d*"
                    type="number"
                    @autosave="validate('ultrafiltration_max')"
                    :error="errors.ultrafiltration_max"
                    placeholder="max [0, 5500]"
                />
            </div>
        </div>
        <FormInput
            label="dry weight (kg.)"
            v-model="form.dry_weight"
            name="dry_weight"
            type="number"
        />
        <div>
            <label class="form-label">50% Glucose IV volume (ml)</label>
            <FormRadio
                class="grid grid-cols-2 gap-x-2"
                :class="{'grid-cols-3': form.glucose_50_percent_iv_volume}"
                name="glucose_50_percent_iv_volume"
                v-model="form.glucose_50_percent_iv_volume"
                :options="['50', '100']"
                :allow-reset="true"
            />
        </div>
        <FormSelect
            v-model="form.glucose_50_percent_iv_at"
            name="glucose_50_percent_iv_at"
            label="50% glucose iv (at hour)"
            :options="[1,2,3,4]"
        />
        <div>
            <label class="form-label">20% albumin prime (ml)</label>
            <FormRadio
                class="grid grid-cols-2 gap-x-2"
                :class="{'grid-cols-3': form.albumin_20_percent_prime}"
                name="albumin_20_percent_prime"
                v-model="form.albumin_20_percent_prime"
                :options="['50', '100']"
                :allow-reset="true"
            />
        </div>
        <FormInput
            label="nutrition iv type"
            v-model="form.nutrition_iv_type"
            name="nutrition_iv_type"
        />
        <FormInput
            label="nutrition iv volume (ml)"
            v-model="form.nutrition_iv_volume"
            name="nutrition_iv_volume"
            type="number"
            pattern="\d*"
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
            name="prc_volume"
            v-model="form.prc_volume"
            type="number"
        />
        <FormInput
            label="ffp volume (ml)"
            name="ffp_volume"
            v-model="form.ffp_volume"
            type="number"
            pattern="\d*"
        />
        <FormInput
            label="platelet volume (unit)"
            name="platelet_volume"
            v-model="form.platelet_volume"
            type="number"
        />
        <FormInput
            label="other"
            name="transfusion_other"
            v-model="form.transfusion_other"
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
import { useSelectOther } from '@/functions/useSelectOther.js';

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

const access_site_coagulant_input = ref(null);
watch(
    () => form.access_type,
    (val, old) => {
        if (
            (val === 'pending')
            || (['DLC', 'Perm cath'].includes(val) && !['DLC', 'Perm cath'].includes(old))
            || (['AVF', 'AVG'].includes(val) && !['AVF', 'AVG'].includes(old))
        ) {
            access_site_coagulant_input.value.setOther('');
        }
    }
);
const configs = reactive({...props.formConfigs});
if (form.anticoagulant && !Object.keys(configs.anticoagulants).includes(form.anticoagulant)) {
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
    let validator = configs.validators.filter((rule) => rule.name === fieldname)[0];
    const value = validator.type == 'integer' ? parseInt(form[fieldname]) :  parseFloat(form[fieldname]);
    if (value < validator.min || value > validator.max) {
        errors[fieldname] = `${form[fieldname]} could not be saved. Accept range [${validator.min}, ${validator.max}].`;
        setTimeout(() => form[fieldname] = null, 1500);
    } else {
        errors[fieldname] = '';
    }
};
</script>