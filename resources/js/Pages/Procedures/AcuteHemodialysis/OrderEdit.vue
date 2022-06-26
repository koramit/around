<template>
    <!-- temp errors -->
    <ul v-if="Object.keys(form.errors).length">
        <li
            v-for="(name, key) in Object.keys(form.errors)"
            :key="key"
        >
            {{ form.errors[name] }}
        </li>
    </ul>
    <!-- reservation -->
    <h2
        class="form-label text-lg italic text-complement"
        id="reservation"
    >
        Reservation data
    </h2>
    <hr class="my-4 border-b border-accent">
    <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4">
        <FormInput
            v-model="configs.hn"
            name="hn"
            label="hn"
            :readonly="true"
        />
        <FormInput
            v-model="configs.an"
            name="an"
            label="an"
            placeholder="No active admission"
            :readonly="true"
        />
        <FormInput
            v-model="configs.dialysis_at"
            name="dialysis_at"
            label="dialysis at"
            :readonly="true"
        />
        <FormInput
            v-model="configs.dialysis_type"
            name="dialysis_type"
            label="dialysis type"
            :readonly="true"
        />
    </div>

    <HDForm
        v-if="orderForm.hd !== undefined"
        v-model="form.hd"
        :form-configs="formConfigs"
        @autosave="autosave"
    />

    <HFForm
        v-if="orderForm.hf !== undefined"
        v-model="form.hf"
        :form-configs="formConfigs"
        @autosave="autosave"
    />

    <SLEDDForm
        v-if="orderForm.sledd !== undefined"
        v-model="form.sledd"
        :form-configs="formConfigs"
        @autosave="autosave"
    />

    <TPEForm
        v-if="orderForm.tpe !== undefined"
        v-model="form.tpe"
        :form-configs="formConfigs"
        @autosave="autosave"
    />

    <!-- predialysis -->
    <h2
        class="mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement"
        id="predialysis-evaluation"
    >
        predialysis evaluation
    </h2>
    <hr class="my-4 border-b border-accent">
    <label class="form-label">hemodynamic :</label>
    <FormCheckbox
        name="hemodynamic_stable"
        v-model="form.hemodynamic.stable"
        label="Stable"
        :toggler="true"
        :error="form.errors['hemodynamic.stable']"
    />
    <transition name="slide-fade">
        <div
            v-if="!form.hemodynamic.stable"
            class="mt-2 md:mt-4 xl:mt-8 space-y-2 md:space-y-4 lg:space-y-0 lg:grid grid-flow-col grid-cols-2 grid-rows-3 gap-4"
        >
            <FormCheckbox
                v-for="symptom in configs.hemodynamic_symptoms"
                :key="symptom.name"
                name="hypotension"
                v-model="form.hemodynamic[symptom.name]"
                :label="symptom.label"
            />
        </div>
    </transition>

    <!-- respiration -->
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <label class="form-label">Respiration :</label>
    <FormCheckbox
        name="respiration_stable"
        v-model="form.respiration.stable"
        label="Stable"
        :toggler="true"
        :error="form.errors['respiration.stable']"
    />
    <transition name="slide-fade">
        <div
            v-if="!form.respiration.stable"
            class="mt-2 md:mt-4 xl:mt-8 space-y-2 md:space-y-4 xl:space-y-4"
        >
            <FormCheckbox
                v-for="symptom in configs.raspiration_options"
                :key="symptom.name"
                name="hypotension"
                v-model="form.respiration[symptom.name]"
                :label="symptom.label"
            />
        </div>
    </transition>

    <!-- o2 support -->
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <label class="form-label">Oxygen support :</label>
    <FormSelect
        v-model="form.oxygen_support"
        name="oxygen_support"
        :options="configs.oxygen_options"
    />

    <!-- nurological -->
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <label class="form-label">Neurological evaluation :</label>
    <FormCheckbox
        name="neurological_stable"
        v-model="form.neurological.stable"
        label="Stable"
        :toggler="true"
        :error="form.errors['neurological.stable']"
    />
    <transition name="slide-fade">
        <div
            v-if="!form.neurological.stable"
            class="mt-2 md:mt-4 xl:mt-8 space-y-2 md:space-y-4 lg:space-y-0 lg:grid grid-flow-col grid-cols-2 grid-rows-1 gap-4"
        >
            <FormCheckbox
                v-for="symptom in configs.neurological_options"
                :key="symptom.name"
                name="hypotension"
                v-model="form.neurological[symptom.name]"
                :label="symptom.label"
            />
        </div>
    </transition>

    <!-- Life threatening condition -->
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <label class="form-label">Life threatening condition in the past 24 hours :</label>
    <FormCheckbox
        name="life_threatening_condition"
        v-model="form.life_threatening_condition.stable"
        label="Stable"
        :toggler="true"
        :error="form.errors['life_threatening_condition.stable']"
    />
    <transition name="slide-fade">
        <div
            v-if="!form.life_threatening_condition.stable"
            class="mt-2 md:mt-4 xl:mt-8 space-y-2 md:space-y-4 lg:space-y-0 lg:grid grid-flow-col grid-cols-2 grid-rows-3 gap-4"
        >
            <FormCheckbox
                v-for="symptom in configs.life_threatening_condition_options"
                :key="symptom.name"
                name="hypotension"
                v-model="form.life_threatening_condition[symptom.name]"
                :label="symptom.label"
            />
        </div>
    </transition>

    <!-- monitoring -->
    <h2
        class="mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement"
        id="monitoring"
    >
        monitoring
    </h2>
    <hr class="my-4 border-b border-accent">
    <FormCheckbox
        name="standard"
        v-model="form.monitor.standard"
        label="Standard (MAP ≥ 65 mmHg)"
        :toggler="true"
        :error="form.errors['monitor.standard']"
    />
    <transition name="slide-fade">
        <div v-if="!form.monitor.standard">
            <div class="mt-2 md:mt-4 xl:mt-8 space-y-2 md:space-y-4 lg:space-y-0 lg:grid grid-flow-col grid-cols-2 grid-rows-2 gap-4">
                <FormCheckbox
                    v-for="(monitor, key) in configs.monitors"
                    :key="key"
                    :label="monitor.label"
                    :name="monitor.name"
                    v-model="form.monitor[monitor.name]"
                />
            </div>
            <FormTextarea
                class="mt-2 md:mt-4 xl:mt-8"
                label="other"
                placeholder="others..."
                name="monitoring_other"
                v-model="form.monitor.other"
            />
        </div>
    </transition>

    <!-- special order -->
    <h2
        class="mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement"
        id="special-orders"
    >
        special orders
    </h2>
    <hr class="my-4 border-b border-accent">
    <FormCheckbox
        class="mt-2 md:bt-4 xl:mt-8"
        name="predialysis_labs_request"
        v-model="form.predialysis_labs_request"
        label="Predialysis Labs request"
        :toggler="true"
    />
    <FormCheckbox
        class="mt-2 md:mt-4 xl:mt-8"
        name="postdialysis_esa"
        v-model="form.postdialysis_esa"
        label="Postdialysis ESA"
        :toggler="true"
    />
    <FormCheckbox
        class="mt-2 md:mt-4 xl:mt-8"
        name="postdialysis_iron_iv"
        v-model="form.postdialysis_iron_iv"
        label="Postdialysis Iron IV"
        :toggler="true"
    />
    <FormTextarea
        class="mt-2 md:mt-4 xl:mt-8"
        label="treatments request"
        name="treatments_request"
        v-model="form.treatments_request"
        :error="form.errors.treatments_request"
    />

    <button @click="form.post(configs.submit_endpoint)">
        submit
    </button>
</template>

<script setup>
import { useForm } from '@inertiajs/inertia-vue3';
import debounce from 'lodash/debounce';
import { reactive, watch } from 'vue';
import FormInput from '@/Components/Controls/FormInput';
import HDForm from '@/Partials/Procedures/AcuteHemodialysis/HDForm';
import HFForm from '@/Partials/Procedures/AcuteHemodialysis/HFForm';
import SLEDDForm from '@/Partials/Procedures/AcuteHemodialysis/SLEDDForm';
import TPEForm from '@/Partials/Procedures/AcuteHemodialysis/TPEForm';
import FormCheckbox from '@/Components/Controls/FormCheckbox';
import FormSelect from '@/Components/Controls/FormSelect';
import FormTextarea from '@/Components/Controls/FormTextarea';
const props = defineProps({
    orderForm: { type: Object, required: true },
    formConfigs: { type: Object, required: true },
});
const form = useForm({...props.orderForm});
watch (
    () => form,
    (val) => {
        // reset predialysis_evaluations
        if (val.hemodynamic.stable) {
            val.hemodynamic.hypotention = false;
            val.hemodynamic.inotropic_dependent = false;
            val.hemodynamic.severe_hypertension = false;
            val.hemodynamic.bradycardia = false;
            val.hemodynamic.arrhythmia = false;
        }
        if (val.respiration.stable) {
            val.respiration.hypoxia = false;
            val.respiration.high_risk_airway_obstruction = false;
        }
        if (val.neurological.stable) {
            val.neurological.gcs_drop = false;
            val.neurological.drowsiness = false;
        }
        if (val.life_threatening_condition.stable) {
            val.life_threatening_condition.acute_coronary_syndrome = false;
            val.life_threatening_condition.cardiac_arrhymia_with_hypotension = false;
            val.life_threatening_condition.acute_ischemic_stroke = false;
            val.life_threatening_condition.acute_ich = false;
            val.life_threatening_condition.seizure = false;
            val.life_threatening_condition.cardiac_arrest = false;
        }

        // reset monitoring
        if (val.monitor.standard) {
            val.monitor.ekg = false;
            val.monitor.observe_chest_pain = false;
            val.monitor.observe_neuro_sign = false;
            val.monitor.other = null;
        }

        autosave();
    },
    { deep: true }
);
const autosave = debounce(function () {
    window.axios
        .patch(configs.update_endpoint, form.data())
        .catch(error => {
            console.log(error);
        });
}, 3000);
const configs = reactive({...props.formConfigs});
</script>