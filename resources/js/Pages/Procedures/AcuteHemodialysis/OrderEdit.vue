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
            v-model="form.reservation.hn"
            name="hn"
            label="hn"
            :readonly="true"
        />
        <FormInput
            v-model="form.reservation.an"
            name="an"
            label="an"
            placeholder="No active admission"
            :readonly="true"
        />
        <FormInput
            v-model="form.reservation.dialysis_at"
            name="dialysis_at"
            label="dialysis at"
            :readonly="true"
        />
        <FormInput
            v-model="form.dialysis_type"
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
        v-model="form.predialysis_evaluations.hemodynamic_stable"
        label="Stable"
        :toggler="true"
    />
    <transition name="slide-fade">
        <div
            v-if="!form.predialysis_evaluations.hemodynamic_stable"
            class="mt-2 md:mt-4 xl:mt-8 space-y-2 md:space-y-4 lg:space-y-0 lg:grid grid-flow-col grid-cols-2 grid-rows-3 gap-4"
        >
            <FormCheckbox
                v-for="symptom in configs.hemodynamic_symptoms"
                :key="symptom.name"
                name="hypotension"
                v-model="form.predialysis_evaluations[symptom.name]"
                :label="symptom.label"
            />
        </div>
    </transition>

    <!-- respiration -->
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <label class="form-label">Respiration :</label>
    <FormCheckbox
        name="respiration_stable"
        v-model="form.predialysis_evaluations.respiration_stable"
        label="Stable"
        :toggler="true"
    />
    <transition name="slide-fade">
        <div
            v-if="!form.predialysis_evaluations.respiration_stable"
            class="mt-2 md:mt-4 xl:mt-8 space-y-2 md:space-y-4 xl:space-y-4"
        >
            <FormCheckbox
                v-for="symptom in configs.raspiration_options"
                :key="symptom.name"
                name="hypotension"
                v-model="form.predialysis_evaluations[symptom.name]"
                :label="symptom.label"
            />
        </div>
    </transition>

    <!-- o2 support -->
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <label class="form-label">Oxygen support :</label>
    <FormSelect
        v-model="form.predialysis_evaluations.oxygen_support"
        name="oxygen_support"
        :options="configs.oxygen_options"
    />

    <!-- nurological -->
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <label class="form-label">Neurological evaluation :</label>
    <FormCheckbox
        name="neurological_stable"
        v-model="form.predialysis_evaluations.neurological_stable"
        label="Stable"
        :toggler="true"
    />
    <transition name="slide-fade">
        <div
            v-if="!form.predialysis_evaluations.neurological_stable"
            class="mt-2 md:mt-4 xl:mt-8 space-y-2 md:space-y-4 lg:space-y-0 lg:grid grid-flow-col grid-cols-2 grid-rows-1 gap-4"
        >
            <FormCheckbox
                v-for="symptom in configs.neurological_options"
                :key="symptom.name"
                name="hypotension"
                v-model="form.predialysis_evaluations[symptom.name]"
                :label="symptom.label"
            />
        </div>
    </transition>

    <!-- Life threatening condition -->
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <label class="form-label">Life threatening condition in the past 24 hours :</label>
    <FormCheckbox
        name="life_threatening_condition"
        v-model="form.predialysis_evaluations.life_threatening_condition"
        label="Stable"
        :toggler="true"
    />
    <transition name="slide-fade">
        <div
            v-if="!form.predialysis_evaluations.life_threatening_condition"
            class="mt-2 md:mt-4 xl:mt-8 space-y-2 md:space-y-4 lg:space-y-0 lg:grid grid-flow-col grid-cols-2 grid-rows-3 gap-4"
        >
            <FormCheckbox
                v-for="symptom in configs.life_threatening_condition_options"
                :key="symptom.name"
                name="hypotension"
                v-model="form.predialysis_evaluations[symptom.name]"
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
        if (val.predialysis_evaluations.hemodynamic_stable) {
            val.predialysis_evaluations.hypotention = false;
            val.predialysis_evaluations.inotropic_dependent = false;
            val.predialysis_evaluations.severe_hypertension = false;
            val.predialysis_evaluations.bradycardia = false;
            val.predialysis_evaluations.arrhythmia = false;
        }
        if (val.predialysis_evaluations.respiration_stable) {
            val.predialysis_evaluations.hypoxia = false;
            val.predialysis_evaluations.high_risk_airway_obstruction = false;
        }
        if (val.predialysis_evaluations.neurological_stable) {
            val.predialysis_evaluations.gcs_drop = false;
            val.predialysis_evaluations.drowsiness = false;
        }
        if (val.predialysis_evaluations.life_threatening_condition) {
            val.predialysis_evaluations.acute_coronary_syndrome = false;
            val.predialysis_evaluations.cardiac_arrhymia_with_hypotension = false;
            val.predialysis_evaluations.acute_ischemic_stroke = false;
            val.predialysis_evaluations.acute_ich = false;
            val.predialysis_evaluations.seizure = false;
            val.predialysis_evaluations.cardiac_arrest = false;
        }

        // reset monitoring
        if (form.monitor.standard) {
            form.monitor.ekg = false;
            form.monitor.observe_chest_pain = false;
            form.monitor.observe_neuro_sign = false;
            form.monitor.other = null;
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