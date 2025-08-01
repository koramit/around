<template>
    <SerologyInfo
        class="mb-4 md:mb-8"
        :serology="formConfigs.serology"
    />
    <!-- reservation -->
    <h2
        class="flex justify-between items-center"
        id="reservation"
    >
        <span class="form-label !mb-0 text-lg italic text-complement">Reservation data</span>
        <button
            v-if="configs.can.reschedule"
            class="flex items-center text-sm text-accent"
            @click="showReschedule = !showReschedule"
        >
            <IconRotate
                class="w-3 h-3 mr-1 transition-all transform duration-200 ease-out"
                :class="{'rotate-180 text-accent-darker': showReschedule}"
            />
            Reschedule
        </button>
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

    <!-- reschedule -->
    <Transition name="slide-fade">
        <div v-if="showReschedule && configs.can.reschedule">
            <div class="mt-4 md:mt-8 xl:mt-16 grid xl:grid-cols-2 gap-2 md:gap-4 lg:gap-6">
                <FormDatetime
                    label="reschedule date"
                    name="date_note"
                    v-model="order.date_note"
                    :options="{ enable: configs.reserve_available_dates, inline: true }"
                    ref="dateNoteInput"
                />
                <div>
                    <DialysisSlot
                        :slots="reservedSlots.slots"
                        v-if="order.dialysis_at.indexOf('Hemo') !== -1"
                    />
                    <WardSlot
                        v-else
                        :slots="reservedSlots.slots"
                    />
                    <AlertMessage
                        v-if="reservedSlots.reply && !reservedSlots.available"
                        class="mt-4"
                        type="warning"
                        title="Cannot make a reservation"
                        :message="reservedSlots.reply"
                    />
                </div>
                <div class="mt-2 lg:mt-0 md:pt-4">
                    <SpinnerButton
                        class="block w-full text-center btn btn-accent"
                        :spin="order.processing"
                        :disabled="order.date_note === configs.date_note || !reservedSlots.available"
                        @click="
                            order.date_note !== configs.today
                                ? order.patch(configs.routes.reschedule, { onFinish: ensureConfigsRefreshAfterCall })
                                : order.patch(configs.routes.today_slot_request, { onFinish: ensureConfigsRefreshAfterCall })
                        "
                    >
                        {{ order.date_note === configs.today || configs.date_note === configs.today ? 'REQUEST RESCHEDULE' : 'RESCHEDULE' }}
                    </SpinnerButton>
                </div>
            </div>
            <div class="mt-4 md:mt-8 xl:mt-16 grid xl:grid-cols-2 gap-2 md:gap-4 lg:gap-6">
                <div class="space-y-2 md:space-y-4 lg:space-y-6">
                    <label class="form-label block">
                        swap code :
                        <CopyToClipboardButton :text="configs.swap_code">
                            <span>{{ configs.swap_code }}</span>
                        </CopyToClipboardButton>
                    </label>
                    <FormInput
                        v-model="order.swap_with"
                        name="swap_with"
                        label="swap with"
                    />
                    <SpinnerButton
                        class="block w-full text-center btn btn-accent"
                        :spin="order.processing"
                        :disabled="!order.swap_with || order.swap_with === configs.swap_code"
                        @click="order.patch(configs.routes.swap, { onFinish: ensureConfigsRefreshAfterCall })"
                    >
                        {{ order.swap_with !== configs.swap_code ? 'SWAP' : '🙄🙄🙄' }}
                    </SpinnerButton>
                </div>
            </div>
        </div>
    </Transition>

    <Transition name="slide-fade">
        <AlertMessage
            v-if="showNoPreviousOrder"
            class="mt-4 md:mt-8"
            message="No previous order"
            title="😅"
            type="warning"
        />
    </Transition>
    <Transition name="slide-fade">
        <div v-if="orderForm.hd !== undefined && !copying">
            <HDForm
                v-model="form.hd"
                :form-configs="formConfigs"
                @copy-previous-order="copyPreviousOrder"
            />
        </div>
    </Transition>

    <Transition name="slide-fade">
        <div v-if="orderForm.hf !== undefined && !copying">
            <HFForm
                v-model="form.hf"
                :form-configs="formConfigs"
                @copy-previous-order="copyPreviousOrder"
            />
        </div>
    </Transition>

    <Transition name="slide-fade">
        <div v-if="orderForm.sledd !== undefined && !copying">
            <SLEDDForm
                v-model="form.sledd"
                :form-configs="formConfigs"
                @copy-previous-order="copyPreviousOrder"
            />
        </div>
    </Transition>

    <Transition name="slide-fade">
        <div v-if="orderForm.pe !== undefined && !copying">
            <PEForm
                v-model="form.pe"
                :form-configs="formConfigs"
                @copy-previous-order="copyPreviousOrder"
            />
        </div>
    </Transition>

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
        name="hemodynamic.stable"
        v-model="form.hemodynamic.stable"
        label="Stable"
        :toggler="true"
        :error="form.errors['hemodynamic.stable']"
    />
    <Transition name="slide-fade">
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
    </Transition>

    <!-- respiration -->
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <label class="form-label">Respiration :</label>
    <FormCheckbox
        name="respiration.stable"
        v-model="form.respiration.stable"
        label="Stable"
        :toggler="true"
        :error="form.errors['respiration.stable']"
    />
    <Transition name="slide-fade">
        <div
            v-if="!form.respiration.stable"
            class="mt-2 md:mt-4 xl:mt-8 space-y-2 md:space-y-4 xl:space-y-4"
        >
            <FormCheckbox
                v-for="symptom in configs.respiration_options"
                :key="symptom.name"
                name="hypotension"
                v-model="form.respiration[symptom.name]"
                :label="symptom.label"
            />
        </div>
    </Transition>

    <!-- o2 support -->
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <label class="form-label">Oxygen support :</label>
    <FormSelect
        v-model="form.oxygen_support"
        name="oxygen_support"
        :options="configs.oxygen_options"
    />

    <!-- neurological -->
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <label class="form-label">Neurological evaluation :</label>
    <FormCheckbox
        name="neurological.stable"
        v-model="form.neurological.stable"
        label="Stable"
        :toggler="true"
        :error="form.errors['neurological.stable']"
    />
    <Transition name="slide-fade">
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
    </Transition>

    <!-- Life threatening condition -->
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <label class="form-label">Life threatening condition in the past 24 hours :</label>
    <FormCheckbox
        name="life_threatening_condition.stable"
        v-model="form.life_threatening_condition.stable"
        label="Stable"
        :toggler="true"
        :error="form.errors['life_threatening_condition.stable']"
    />
    <Transition name="slide-fade">
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
    </Transition>

    <!-- monitoring -->
    <h2
        class="mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement"
        id="monitoring"
    >
        monitoring
    </h2>
    <hr class="my-4 border-b border-accent">
    <FormCheckbox
        name="monitor.standard"
        v-model="form.monitor.standard"
        label="Standard (MAP ≥ 65 mmHg)"
        :toggler="true"
        :error="form.errors['monitor.standard']"
    />
    <Transition name="slide-fade">
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
    </Transition>

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
        name="postdialysis_bw"
        v-model="form.postdialysis_bw"
        label="Postdialysis BW"
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
    <!--suppress JSValidateTypes -->
    <FormTextarea
        class="mt-2 md:mt-4 xl:mt-8"
        label="treatments request"
        name="treatments_request"
        v-model="form.treatments_request"
        :error="form.errors.treatments_request"
    />

    <SpinnerButton
        @click="submit"
        :spin="form.processing"
        class="mt-4 md:mt-8 w-full btn-accent"
    >
        SUBMIT
    </SpinnerButton>

    <!--discussion-->
    <h2
        class="mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement scroll-mt-16 md:scroll-mt-8"
        id="discussion"
    >
        discussion
    </h2>
    <hr class="my-4 border-b border-accent">
    <CommentSection :configs="configs.comment" />
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { nextTick, reactive, watch, ref, onMounted, defineAsyncComponent } from 'vue';
import {useFormAutosave} from '../../../functions/useFormAutosave.js';
import {useActionStore} from '../../../functions/useActionStore.js';
import FormInput from '../../../Components/Controls/FormInput.vue';
import FormCheckbox from '../../../Components/Controls/FormCheckbox.vue';
import FormSelect from '../../../Components/Controls/FormSelect.vue';
import FormTextarea from '../../../Components/Controls/FormTextarea.vue';
import SpinnerButton from '../../../Components/Controls/SpinnerButton.vue';
import AlertMessage from '../../../Components/Helpers/AlertMessage.vue';
import FormDatetime from '../../../Components/Controls/FormDatetime.vue';
import CopyToClipboardButton from '../../../Components/Controls/CopyToClipboardButton.vue';
import IconRotate from '../../../Components/Helpers/Icons/IconRotate.vue';
import CommentSection from '../../../Components/Forms/CommentSection.vue';
import SerologyInfo from '../../../Components/Helpers/SerologyInfo.vue';
const HDForm = defineAsyncComponent(() => import('../../../Partials/Procedures/AcuteHemodialysis/HDForm.vue'));
const HFForm = defineAsyncComponent(() => import('../../../Partials/Procedures/AcuteHemodialysis/HFForm.vue'));
const SLEDDForm = defineAsyncComponent(() => import('../../../Partials/Procedures/AcuteHemodialysis/SLEDDForm.vue'));
const PEForm = defineAsyncComponent(() => import('../../../Partials/Procedures/AcuteHemodialysis/PEForm.vue'));
const DialysisSlot = defineAsyncComponent(() => import('../../../Partials/Procedures/AcuteHemodialysis/DialysisSlot.vue'));
const WardSlot = defineAsyncComponent(() => import('../../../Partials/Procedures/AcuteHemodialysis/WardSlot.vue'));

const props = defineProps({
    orderForm: { type: Object, required: true },
    formConfigs: { type: Object, required: true },
});
const configs = reactive({...props.formConfigs});
/** @member {Object} */
const form = useForm({...props.orderForm});

const {autosave} = useFormAutosave();
watch (
    () => form.data(),
    (val) => {
        // reset predialysis_evaluations
        if (val.hemodynamic.stable) {
            val.hemodynamic.hypotension = false;
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
            val.life_threatening_condition.cardiac_arrhythmia_with_hypotension = false;
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

        autosave(form.data(), configs.routes.update);
    },
    { deep: configs.can.update }
);

const submit = () =>form.patch(configs.routes.submit);

const {actionStore} = useActionStore();
watch(
    () => actionStore.value,
    (value) => {
        switch (value.name) {
        case 'submit':
            submit();
            break;
        default:
            return;
        }
    },
    { deep: true }
);

// Reschedule
const showReschedule = ref(false);
const order = useForm({
    dialysis_type: configs.dialysis_type,
    dialysis_at: configs.dialysis_at,
    attending_staff: null,
    date_note: configs.date_note,
    patient_type: null,
    swap_with: null,
});
const dateNoteInput = ref(null);
const reservedSlots = reactive({
    slots: [],
    available: false,
    reply: '',
});
const checkSlot = () => window.axios
    .post(configs.routes.acute_hemodialysis_slot_available, {
        dialysis_type: order.dialysis_type,
        dialysis_at: order.dialysis_at,
        date_note: order.date_note,
    }).then(response => {
        reservedSlots.slots = response.data.slots;
        reservedSlots.available = response.data.available;
        reservedSlots.reply = response.data.reply;
    });
onMounted(checkSlot);
watch (
    () => order.date_note,
    (val) => {
        if (!val) {
            return;
        }
        checkSlot();
    }
);
const ensureConfigsRefreshAfterCall = () => {
    configs.reserve_available_dates = props.formConfigs.reserve_available_dates;
    configs.date_note = props.formConfigs.date_note;
    configs.dialysis_type = props.formConfigs.dialysis_type;
    configs.dialysis_at = props.formConfigs.dialysis_at;
    configs.swap_code = props.formConfigs.swap_code;
    configs.can = props.formConfigs.can;
};

const copying = ref(false);
const showNoPreviousOrder = ref(false);
const copyPreviousOrder = () => {
    window.axios
        .patch(configs.routes.copy)
        .then(res => {
            if (!res.data.found) {
                showNoPreviousOrder.value = true;
                setTimeout(() => showNoPreviousOrder.value = false, 2000);
                return; // reply no data
            }

            copying.value = true;

            nextTick(() => {
                res.data.records.map(data => {
                    form[data.type] = data.form;
                });
            });

            setTimeout(() => copying.value = false, 200);
        });
};
</script>
