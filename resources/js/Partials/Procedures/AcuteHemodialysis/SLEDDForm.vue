<template>
    <h2
        class="mt-6 md:mt-12 xl:mt-24 flex justify-between items-center"
        id="prescription"
    >
        <span class="form-label !mb-0 text-lg italic text-complement">SLEDD Prescription</span>
        <button
            class="flex items-center text-sm text-accent"
            @click="$emit('copyPreviousOrder')"
            v-if="formConfigs.can.copy"
        >
            <IconCopy class="w-3 h-3 mr-1" />
            Copy previous order
        </button>
    </h2>
    <hr class="my-4 border-b border-bitter-theme-light">
    <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4">
        <div>
            <label class="form-label">duration (hrs.)</label>
            <FormRadio
                class="grid grid-cols-2 gap-x-2"
                name="sledd.duration"
                :error="$page.props.errors['sledd.duration']"
                v-model="form.duration"
                :options="configs.sledd_durations"
            />
        </div>
    </div>
    <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4">
        <FormSelect
            label="access type"
            v-model="form.access_type"
            name="sledd.access_type"
            :options="configs.access_types"
            :error="$page.props.errors['sledd.access_type']"
        />
        <FormSelect
            label="access site"
            v-model="form.access_site_coagulant"
            name="sledd.access_site_coagulant"
            :options="(form.access_type && form.access_type.startsWith('AV')) ? configs.av_access_sites : configs.non_av_access_sites"
            :disabled="!form.access_type || form.access_type === 'pending'"
            :error="$page.props.errors['sledd.access_site_coagulant']"
        />
        <FormSelect
            v-model="form.dialyzer"
            name="sledd.dialyzer"
            label="dialyzer"
            :options="configs.sledd_dialyzers"
            :error="$page.props.errors['sledd.dialyzer']"
        />
        <FormSelect
            v-model="form.dialysate"
            name="sledd.dialysate"
            label="dialysate"
            :options="configs.dialysates"
            :error="$page.props.errors['sledd.dialysate']"
        />
        <FormSelect
            v-model="form.blood_flow_rate"
            name="sledd.blood_flow_rate"
            :options="configs.sledd_blood_flow_rates"
            label="blood flow rate (ml/min)"
            :error="$page.props.errors['sledd.blood_flow_rate']"
        />
        <FormSelect
            v-model:model-value="form.dialysate_flow_rate"
            v-model:model-checkbox="form.reverse_dialysate_flow"
            :options="configs.sledd_dialysate_flow_rates"
            name="sledd.dialysate_flow_rate"
            label="dialysate flow (ml/min)"
            switch-label="Reverse flow"
            :error="$page.props.errors['sledd.dialysate_flow_rate']"
        />
        <FormSelect
            v-model="form.dialysate_temperature"
            name="sledd.dialysate_temperature"
            :options="configs.dialysate_temperatures"
            label="dialysate temperature (℃)"
            :error="$page.props.errors['sledd.dialysate_temperature']"
        />
    </div>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <div class="grid md:grid-cols-2 gap-2 xl:gap-8 my-2 md:my-4 xl:mt-8">
        <div>
            <FormInput
                label="sodium"
                name="sledd.sodium"
                v-model="form.sodium"
                type="number"
                pattern="\d*"
                @autosave="validate('sodium')"
                :error="errors.sodium ?? $page.props.errors['sledd.sodium']"
                :placeholder="`[${configs.validators.sodium.min}, ${configs.validators.sodium.max}]`"
            />
            <FormCheckbox
                class="mt-2 md:mt-4 xl:mt-8"
                label="Sodium profile"
                name="sledd.sodium_profile"
                v-model="form.sodium_profile"
                :toggler="true"
            />
            <Transition name="slide-fade">
                <div
                    v-if="form.sodium_profile"
                    class="grid gap-2 md:gap-4 xl:gap-8 mt-2 md:mt-4 xl:mt-8"
                >
                    <FormInput
                        label="start"
                        name="sledd.sodium_profile_start"
                        v-model="form.sodium_profile_start"
                        type="number"
                        pattern="\d*"
                        :error="errors.sodium_profile_start ?? $page.props.errors['sledd.sodium_profile_start']"
                    />
                    <FormInput
                        label="end"
                        name="sledd.sodium_profile_end"
                        v-model="form.sodium_profile_end"
                        type="number"
                        pattern="\d*"
                        :error="errors.sodium_profile_end ?? $page.props.errors['sledd.sodium_profile_end']"
                    />
                </div>
            </Transition>
        </div>
        <FormSelect
            v-model="form.bicarbonate"
            name="sledd.bicarbonate"
            label="bicarbonate"
            :options="configs.bicarbonates"
            :error="$page.props.errors['sledd.bicarbonate']"
        />
    </div>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4 my-2 md:my-4 xl:my-8">
        <FormSelect
            v-model="form.anticoagulant"
            name="sledd.anticoagulant"
            label="anticoagulant"
            :options="configs.anticoagulants"
            ref="anticoagulantInput"
            :error="$page.props.errors['sledd.anticoagulant']"
        />
    </div>
    <Transition name="slide-fade">
        <div
            class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4 my-2 md:my-4 xl:my-8"
            v-if="form.anticoagulant === 'none'"
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
        <div v-else-if="form.anticoagulant === 'heparin'">
            <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4 my-2 md:my-4 xl:my-8">
                <FormInput
                    label="loading dose (iu)"
                    name="sledd.heparin_loading_dose"
                    v-model="form.heparin_loading_dose"
                    type="number"
                    pattern="\d*"
                    @autosave="validate('heparin_loading_dose')"
                    :error="errors.heparin_loading_dose ?? $page.props.errors['sledd.heparin_loading_dose']"
                    :placeholder="`[${configs.validators.heparin_loading_dose.min}, ${configs.validators.heparin_loading_dose.max}] IU`"
                />
                <FormInput
                    label="maintenance dose (iu/hour)"
                    name="sledd.heparin_maintenance_dose"
                    v-model="form.heparin_maintenance_dose"
                    type="number"
                    pattern="\d*"
                    @autosave="validate('heparin_maintenance_dose')"
                    :error="errors.heparin_maintenance_dose ?? $page.props.errors['sledd.heparin_maintenance_dose']"
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
            v-else-if="form.anticoagulant === 'enoxaparin'"
        >
            <FormInput
                label="dose (ml)"
                name="sledd.enoxaparin_dose"
                v-model="form.enoxaparin_dose"
                type="number"
                @autosave="validate('enoxaparin_dose')"
                :error="errors.enoxaparin_dose ?? $page.props.errors['sledd.enoxaparin_dose']"
                :placeholder="`[${configs.validators.enoxaparin_dose.min}, ${configs.validators.enoxaparin_dose.max}] ml`"
            />
        </div>
        <div
            class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4 my-2 md:my-4 xl:my-8"
            v-else-if="form.anticoagulant === 'fondaparinux'"
        >
            <FormSelect
                label="bolus dose (iu)"
                name="sledd.fondaparinux_bolus_dose"
                v-model="form.fondaparinux_bolus_dose"
                :options="configs.fondaparinux_bolus_doses"
                :error="$page.props.errors['sledd.fondaparinux_bolus_dose']"
            />
        </div>
        <div
            class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4 my-2 md:my-4 xl:my-8"
            v-else-if="form.anticoagulant === 'tinzaparin'"
        >
            <FormInput
                label="dose (iu)"
                name="sledd.tinzaparin_dose"
                v-model="form.tinzaparin_dose"
                type="number"
                pattern="\d*"
                @autosave="validate('tinzaparin_dose')"
                :error="errors.tinzaparin_dose ?? $page.props.errors['sledd.tinzaparin_dose']"
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
                    name="sledd.ultrafiltration_min"
                    v-model="form.ultrafiltration_min"
                    pattern="\d*"
                    type="number"
                    @autosave="validate('ultrafiltration_min')"
                    :error="errors.ultrafiltration_min ?? $page.props.errors['sledd.ultrafiltration_min']"
                    :placeholder="`min [${configs.validators.ultrafiltration_min.min}, ${configs.validators.ultrafiltration_min.max}]`"
                />
                <FormInput
                    name="sledd.ultrafiltration_max"
                    v-model="form.ultrafiltration_max"
                    pattern="\d*"
                    type="number"
                    @autosave="validate('ultrafiltration_max')"
                    :error="errors.ultrafiltration_max ?? $page.props.errors['sledd.ultrafiltration_max']"
                    :placeholder="`max [${configs.validators.ultrafiltration_max.min}, ${configs.validators.ultrafiltration_max.max}]`"
                />
            </div>
        </div>
        <FormInput
            label="dry weight (kg.)"
            v-model="form.dry_weight"
            name="sledd.dry_weight"
            type="number"
            :error="$page.props.errors['sledd.dry_weight']"
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
            name="sledd.nutrition_iv_type"
            :error="$page.props.errors['sledd.nutrition_iv_type']"
        />
        <FormInput
            label="nutrition iv volume (ml)"
            v-model="form.nutrition_iv_volume"
            name="sledd.nutrition_iv_volume"
            type="number"
            pattern="\d*"
            :error="$page.props.errors['sledd.nutrition_iv_volume']"
        />
    </div>
    <!--    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <FormCheckbox
        label="Post Dialysis BW"
        name="post_dialysis_bw"
        v-model="form.post_dialysis_bw"
        :toggler="true"
    />-->

    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <label class="form-label">transfusion :</label>
    <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4">
        <FormInput
            label="prc volume (unit)"
            name="sledd.prc_volume"
            v-model="form.prc_volume"
            type="number"
            :error="$page.props.errors['sledd.prc_volume']"
        />
        <FormInput
            label="ffp volume (ml)"
            name="sledd.ffp_volume"
            v-model="form.ffp_volume"
            type="number"
            pattern="\d*"
            :error="$page.props.errors['sledd.ffp_volume']"
        />
        <FormInput
            label="platelet volume (unit)"
            name="sledd.platelet_volume"
            v-model="form.platelet_volume"
            type="number"
            :error="$page.props.errors['sledd.platelet_volume']"
        />
        <FormInput
            label="other"
            name="sledd.transfusion_other"
            v-model="form.transfusion_other"
            :error="$page.props.errors['sledd.transfusion_other']"
        />
    </div>
    <template v-if="form.access_type && ['DLC', 'Perm cath'].includes(form.access_type)">
        <hr class="border border-dashed my-2 md:my-4 xl:my-8">
        <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4">
            <FormSelect
                label="catheter lock"
                name="sledd.catheter_lock"
                :options="configs.catheter_lock_options"
                v-model="form.catheter_lock"
                :error="$page.props.errors['sledd.catheter_lock']"
                allow-other
                ref="catheterLockInput"
            />
        </div>
    </template>
    <FormTextarea
        class="mt-2 md:mt-4 xl:mt-8"
        label="note"
        name="sledd.remark"
        :error="$page.props.errors['sledd.remark']"
        v-model="form.remark"
    />
    <FormSelectOther
        :placeholder="selectOther.placeholder"
        ref="selectOtherInput"
        @closed="(val) => selectOtherClosed(val, true)"
    />
</template>

<script setup>
import FormCheckbox from '../../../Components/Controls/FormCheckbox.vue';
import FormInput from '../../../Components/Controls/FormInput.vue';
import FormTextarea from '../../../Components/Controls/FormTextarea.vue';
import FormSelect from '../../../Components/Controls/FormSelect.vue';
import FormRadio from '../../../Components/Controls/FormRadio.vue';
import AlertMessage from '../../../Components/Helpers/AlertMessage.vue';
import FormSelectOther from '../../../Components/Controls/FormSelectOther.vue';
import { watch, reactive, ref } from 'vue';
import { useSelectOther } from '../../../functions/useSelectOther.js';
import IconCopy from '../../../Components/Helpers/Icons/IconCopy.vue';

const props = defineProps({
    modelValue: { type: Object, required: true },
    formConfigs: { type: Object, required: true },
});
const emit = defineEmits(['update:modelValue', 'autosave', 'copyPreviousOrder']);

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
            || (val.access_type === 'Remove')
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

if (form.anticoagulant && configs.anticoagulants.findIndex(item => item.value === form.anticoagulant) === -1) {
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
if (form.catheter_lock && configs.catheter_lock_options.findIndex(item => item.value === form.catheter_lock) === -1) {
    configs.catheter_lock_options.push({ value: form.catheter_lock, label: form.catheter_lock });
}
const catheterLockInput = ref(null);
watch (
    () => form.catheter_lock,
    (val) => {
        if (val.toLowerCase() !== 'other') {
            return;
        }

        selectOther.placeholder = 'Other Catheter lock';
        selectOther.configs = configs.catheter_lock_options;
        selectOther.input = catheterLockInput.value;
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
const validate = (fieldName) => {
    let validator = configs.validators[fieldName];
    const value = validator.type === 'integer' ? parseInt(form[fieldName]) :  parseFloat(form[fieldName]);
    if (value < validator.min || value > validator.max) {
        errors[fieldName] = `${form[fieldName]} could not be saved. Accept range [${validator.min}, ${validator.max}].`;
        form[fieldName] = null;
    } else {
        errors[fieldName] = '';
    }
};
</script>
