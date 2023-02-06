<!--suppress JSValidateTypes, JSUnresolvedFunction -->
<template>
    <!-- summary  -->
    <h2
        class="form-label text-lg italic text-complement scroll-mt-16 md:scroll-mt-8"
        id="case-record"
    >
        HLA typing for Kidney Transplant Report
    </h2>
    <hr class="my-4 border-b border-accent">
    <div class="grid gap-2 md:grid md:gap-4 md:grid-cols-2 xl:gap-8">
        <FormInput
            :label="meta.donor_hn ? 'recipient hn':'patient hn'"
            :readonly="true"
            name="patient_hn"
            v-model="meta.patient_hn"
        />
        <FormInput
            :label="meta.donor_hn ? 'recipient name':'patient name'"
            :readonly="true"
            name="patient_name"
            v-model="meta.patient_name"
        />
        <template v-if="meta.donor_hn">
            <FormInput
                label="donor hn"
                :readonly="true"
                name="donor_hn"
                v-model="meta.donor_hn"
            />
            <FormInput
                label="donor name"
                :readonly="true"
                name="donor_name"
                v-model="meta.donor_name"
            />
            <FormSelect
                label="recipient is"
                name="recipient_is"
                :options="formConfigs.recipient_is_options"
                v-model="form.recipient_is"
                :error="$page.props.errors.recipient_is"
            />
            <FormSelect
                :disabled="!form.recipient_is"
                label="donor is"
                name="donor_is"
                :options="formConfigs.donor_is_options[form.recipient_is] ?? []"
                v-model="form.donor_is"
                :error="$page.props.errors.donor_is"
            />
        </template>
        <FormInput
            label="diagnosis"
            name="diagnosis"
            v-model="form.diagnosis"
        />
        <FormAutocomplete
            label="clinician"
            name="clinician"
            v-model="form.clinician"
            :endpoint="formConfigs.routes.clinicians"
            :params="formConfigs.routes.clinicians_scope_params"
            :error="$page.props.errors.clinician"
            :length-to-start="3"
        />
        <FormDatetime
            label="collection date"
            name="date_serum"
            v-model="meta.date_serum"
            :disabled="true"
        />
        <FormDatetime
            label="report date"
            name="date_report"
            v-model="form.date_report"
            :error="$page.props.errors.date_report"
        />
        <FormInput
            label="report by"
            name="reporter"
            v-model="form.reporter"
            :error="$page.props.errors.reporter"
        />
        <FormInput
            label="approve by"
            name="approver"
            v-model="form.approver"
            :error="$page.props.errors.approver"
        />
    </div>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <Transition name="slide-fade">
        <div v-if="meta.request_hla">
            <h3 class="form-label mt-4 md:mt-8 xl:mt-16">
                HLA TYPING :
            </h3>
            <div
                class="mt-4 gap-2 md:grid-cols-2 md:gap-4 xl:gap-6"
                :class="{
                    'grid': meta.donor_hn,
                }"
            >
                <div
                    v-for="patient in meta.patients"
                    :key="patient"
                    class="space-y-2 md:space-y-4"
                    :class="{
                        'border-green-400 p-2 md:p-4 rounded-lg border-2': patient === 'patient' && meta.donor_hn,
                        'border-amber-400 p-2 md:p-4 rounded-lg border-2': patient === 'donor',
                    }"
                >
                    <h4
                        class="form-label underline"
                        v-if="meta.donor_hn"
                    >
                        {{ meta.donor_hn && patient === 'patient' ? 'recipient' : patient }}
                    </h4>
                    <FormDatetime
                        label="date test"
                        :name="`${patient}_date_hla_typing`"
                        v-model="form[`${patient}_hla_note`].date_hla_typing"
                        :error="$page.props.errors[`${patient}_hla_note.date_hla_typing`]"
                    />
                    <div class="grid gap-2 md:grid-cols-2 md:gap-4 xl:gap-6">
                        <FormSelect
                            label="abo"
                            :name="`${patient}_abo`"
                            v-model="form[`${patient}_hla_note`].abo"
                            :options="formConfigs.abo_options"
                            :error="$page.props.errors[`${patient}_hla_note.abo`]"
                        />
                        <FormSelect
                            label="rh"
                            :name="`${patient}_rh`"
                            v-model="form[`${patient}_hla_note`].rh"
                            :options="formConfigs.rh_options"
                            :error="$page.props.errors[`${patient}_hla_note.rh`]"
                        />
                    </div>
                    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
                    <label class="form-label italic">class i</label>
                    <div class="grid gap-2 md:grid-cols-2 md:gap-4 xl:gap-6">
                        <FormInput
                            v-for="antigen in formConfigs.antigens"
                            :key="antigen.name"
                            :label="antigen.label"
                            :name="`${patient}_hla_typing_class_i_${antigen.name}`"
                            v-model="form[`${patient}_hla_note`][`hla_typing_class_i_${antigen.name}`]"
                        />
                    </div>
                    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
                    <label class="form-label italic">class ii</label>
                    <div class="grid gap-2 md:grid-cols-2 md:gap-4 xl:gap-6">
                        <FormInput
                            v-for="antigen in formConfigs.classIIAntigens.filter(a => a.group === 1)"
                            :key="antigen.name"
                            :label="antigen.label"
                            :name="`${patient}_hla_typing_class_ii_${antigen.name}`"
                            v-model="form[`${patient}_hla_note`][`hla_typing_class_ii_${antigen.name}`]"
                        />
                    </div>
                    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
                    <FormInput
                        label="hla mismatch"
                        :name="`${patient}_hla_typing_mismatch`"
                        v-model="form[`${patient}_hla_note`][`hla_typing_mismatch`]"
                    />
                </div>
            </div>
        </div>
    </Transition>
    <Transition name="slide-fade">
        <div v-if="meta.request_cxm">
            <h3 class="form-label mt-4 md:mt-8 xl:mt-16">
                lymphocyte crossmatch :
            </h3>
            <div
                class="mt-4 space-y-4 md:space-y-0 md:grid-cols-2 md:gap-4 xl:gap-6"
                :class="{
                    'grid': meta.donor_hn
                }"
            >
                <div
                    class="space-y-2 md:space-y-4"
                    :class="{
                        'border-green-400 p-2 md:p-4 rounded-lg border-2': patient === 'patient' && meta.donor_hn,
                        'border-amber-400 p-2 md:p-4 rounded-lg border-2': patient === 'donor',
                    }"
                    v-for="patient in meta.patients"
                    :key="patient"
                >
                    <h4
                        class="form-label underline"
                        v-if="meta.donor_hn"
                    >
                        {{ patient === 'patient' && meta.donor_hn ? 'recipient' : patient }}
                    </h4>
                    <FormDatetime
                        label="date test"
                        :name="`${patient}_cxm_note.date_cross_matching`"
                        v-model="form[`${patient}_cxm_note`].date_cross_matching"
                        :error="$page.props.errors[`${patient}_cxm_note.date_cross_matching`]"
                    />
                    <label class="form-label italic">t-lymphocyte</label>
                    <div class="md:flex md:space-x-2">
                        <div class="md:w-4/6 p-1 md:p-2 bg-white rounded">
                            <label class="form-label text-center">cdc</label>
                            <div class="md:flex space-y-1 md:space-y-0 md:space-x-2">
                                <div class="md:w-1/2 p-1 md:p-2 bg-primary rounded">
                                    <label class="form-label text-center">neat</label>
                                    <div class="flex">
                                        <FormRadio
                                            class="w-1/2"
                                            label="rt"
                                            :name="`${patient}_t_lymphocyte_cdc_neat_rt`"
                                            :options="formConfigs.lymphocyteCrossmatchOptions"
                                            v-model="form[`${patient}_cxm_note`].t_lymphocyte_cdc_neat_rt"
                                            :allow-reset="true"
                                            :narrow="true"
                                        />
                                        <FormRadio
                                            class="w-1/2"
                                            label="37℃"
                                            :name="`${patient}_t_lymphocyte_cdc_neat_37_degree_celsius`"
                                            :options="formConfigs.lymphocyteCrossmatchOptions"
                                            v-model="form[`${patient}_cxm_note`].t_lymphocyte_cdc_neat_37_degree_celsius"
                                            :allow-reset="true"
                                            :narrow="true"
                                        />
                                    </div>
                                </div>
                                <div class="md:w-1/2 p-1 md:p-2 bg-primary rounded">
                                    <label class="form-label text-center">dtt</label>
                                    <div class="flex">
                                        <FormRadio
                                            class="w-1/2"
                                            label="rt"
                                            :name="`${patient}_t_lymphocyte_cdc_dtt_rt`"
                                            :options="formConfigs.lymphocyteCrossmatchOptions"
                                            v-model="form[`${patient}_cxm_note`].t_lymphocyte_cdc_dtt_rt"
                                            :allow-reset="true"
                                            :narrow="true"
                                        />
                                        <FormRadio
                                            class="w-1/2"
                                            label="37℃"
                                            :name="`${patient}_t_lymphocyte_cdc_dtt_37_degree_celsius`"
                                            :options="formConfigs.lymphocyteCrossmatchOptions"
                                            v-model="form[`${patient}_cxm_note`].t_lymphocyte_cdc_dtt_37_degree_celsius"
                                            :allow-reset="true"
                                            :narrow="true"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 md:w-2/6 md:mt-0 p-1 md:p-2 bg-white rounded">
                            <label class="form-label text-center">cdc - ahg</label>
                            <div class="flex space-x-1 md:space-x-2">
                                <div class="w-1/2 p-1 md:p-2 bg-primary rounded">
                                    <label class="form-label text-center">neat</label>
                                    <FormRadio
                                        label="rt"
                                        :name="`${patient}_t_lymphocyte_cdc_ahg_neat_rt`"
                                        :options="formConfigs.lymphocyteCrossmatchOptions"
                                        v-model="form[`${patient}_cxm_note`].t_lymphocyte_cdc_ahg_neat_rt"
                                        :allow-reset="true"
                                        :narrow="true"
                                    />
                                </div>
                                <div class="w-1/2 p-1 md:p-2 bg-primary rounded">
                                    <label class="form-label text-center">dtt</label>
                                    <FormRadio
                                        label="rt"
                                        :name="`${patient}_t_lymphocyte_cdc_ahg_dtt_rt`"
                                        :options="formConfigs.lymphocyteCrossmatchOptions"
                                        v-model="form[`${patient}_cxm_note`].t_lymphocyte_cdc_ahg_dtt_rt"
                                        :allow-reset="true"
                                        :narrow="true"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
                    <label class="form-label italic">b-lymphocyte</label>
                    <div class="flex space-x-1 md:space-x-2">
                        <div class="w-1/2 p-1 md:p-2 bg-white rounded">
                            <label class="form-label text-center">cdc</label>
                            <div class="md:flex space-y-1 md:space-y-0 md:space-x-2">
                                <div class="md:w-1/2 p-1 md:p-2 bg-primary rounded">
                                    <label class="form-label text-center">neat</label>
                                    <FormRadio
                                        label="37℃"
                                        :name="`${patient}_b_lymphocyte_cdc_neat_37_degree_celsius`"
                                        :options="formConfigs.lymphocyteCrossmatchOptions"
                                        v-model="form[`${patient}_cxm_note`].b_lymphocyte_cdc_neat_37_degree_celsius"
                                        :allow-reset="true"
                                        :narrow="true"
                                    />
                                </div>
                                <div class="md:w-1/2 p-1 md:p-2 bg-primary rounded">
                                    <label class="form-label text-center">dtt</label>
                                    <FormRadio
                                        label="37℃"
                                        :name="`${patient}_b_lymphocyte_cdc_dtt_37_degree_celsius`"
                                        :options="formConfigs.lymphocyteCrossmatchOptions"
                                        v-model="form[`${patient}_cxm_note`].b_lymphocyte_cdc_dtt_37_degree_celsius"
                                        :allow-reset="true"
                                        :narrow="true"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="w-1/2 p-1 md:p-2 bg-white rounded">
                            <label class="form-label text-center">cdc - ahg</label>
                            <div class="md:flex space-y-1 md:space-y-0 md:space-x-2">
                                <div class="md:w-1/2 p-1 md:p-2 bg-primary rounded">
                                    <label class="form-label text-center">neat</label>
                                    <FormRadio
                                        label="37℃"
                                        :name="`${patient}_b_lymphocyte_cdc_ahg_neat_37_degree_celsius`"
                                        :options="formConfigs.lymphocyteCrossmatchOptions"
                                        v-model="form[`${patient}_cxm_note`].b_lymphocyte_cdc_ahg_neat_37_degree_celsius"
                                        :allow-reset="true"
                                        :narrow="true"
                                    />
                                </div>
                                <div class="md:w-1/2 p-1 md:p-2 bg-primary rounded">
                                    <label class="form-label text-center">dtt</label>
                                    <FormRadio
                                        label="37℃"
                                        :name="`${patient}_b_lymphocyte_cdc_ahg_dtt_37_degree_celsius`"
                                        :options="formConfigs.lymphocyteCrossmatchOptions"
                                        v-model="form[`${patient}_cxm_note`].b_lymphocyte_cdc_ahg_dtt_37_degree_celsius"
                                        :allow-reset="true"
                                        :narrow="true"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
                    <label class="form-label italic">flow cytometry crossmatch</label>
                    <FormInput
                        label="t-lymphocyte"
                        :name="`${patient}_t_lymphocyte_crossmatch`"
                        v-model="form[`${patient}_cxm_note`].t_lymphocyte_crossmatch"
                    />
                    <FormInput
                        label="b-lymphocyte"
                        :name="`${patient}_b_lymphocyte_crossmatch`"
                        v-model="form[`${patient}_cxm_note`].b_lymphocyte_crossmatch"
                    />
                </div>
            </div>
        </div>
    </Transition>
    <Transition name="slide-fade">
        <div v-if="meta.request_addition_tissue">
            <h3 class="form-label mt-4 md:mt-8 xl:mt-16">
                ADDITION TISSUE TYPING :
            </h3>
            <div
                class="mt-4 gap-2 md:grid-cols-2 md:gap-4 xl:gap-6"
                :class="{
                    'grid': meta.donor_hn,
                }"
            >
                <div
                    v-for="patient in meta.patients"
                    :key="patient"
                    class="space-y-2 md:space-y-4"
                    :class="{
                        'border-green-400 p-2 md:p-4 rounded-lg border-2': patient === 'patient' && meta.donor_hn,
                        'border-amber-400 p-2 md:p-4 rounded-lg border-2': patient === 'donor',
                    }"
                >
                    <h4
                        class="form-label underline"
                        v-if="meta.donor_hn"
                    >
                        {{ meta.donor_hn && patient === 'patient' ? 'recipient' : patient }}
                    </h4>
                    <FormDatetime
                        label="date test"
                        :name="`${patient}_date_addition_tissue_typing`"
                        v-model="form[`${patient}_addition_tissue_typing_note`].date_addition_tissue_typing"
                        :error="$page.props.errors[`${patient}_addition_tissue_typing_note.date_addition_tissue_typing`]"
                    />
                    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
                    <div class="grid gap-2 md:grid-cols-2 md:gap-4 xl:gap-6">
                        <FormInput
                            v-for="antigen in formConfigs.additionAntigens"
                            :key="antigen.name"
                            :label="antigen.label"
                            :name="`${patient}_tissue_typing_${antigen.name}`"
                            v-model="form[`${patient}_addition_tissue_typing_note`][`tissue_typing_${antigen.name}`]"
                        />
                    </div>
                </div>
            </div>
        </div>
    </Transition>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <FormTextarea
        label="comments"
        name="remark"
        v-model="form.remark"
    />
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <FileUploader
        label="scanned report"
        :pathname="formConfigs.upload_pathname"
        v-model="form.scanned_report"
        :service-endpoints="formConfigs.routes.upload"
        :error="$page.props.errors.scanned_report"
        name="scanned_report"
    />

    <SpinnerButton
        :spin="form.processing"
        v-if="formConfigs.can.update"
        @click="publish"
        class="mt-4 md:mt-8 w-full btn-accent"
    >
        PUBLISH
    </SpinnerButton>

    <SpinnerButton
        :spin="form.processing"
        v-if="formConfigs.can.addendum"
        @click="addendum"
        class="mt-4 md:mt-8 w-full btn-complement"
    >
        ADDENDUM
    </SpinnerButton>

    <SpinnerButton
        v-if="formConfigs.can.destroy"
        :spin="form.processing"
        @click="handleButtonActionClicked('destroy-report')"
        class="mt-4 md:mt-8 w-full btn-danger"
    >
        DELETE
    </SpinnerButton>

    <SpinnerButton
        v-if="formConfigs.can.cancel"
        :spin="form.processing"
        @click="handleButtonActionClicked('cancel-report')"
        class="mt-4 md:mt-8 w-full btn-warning"
    >
        CANCEL
    </SpinnerButton>

    <ConfirmFormComposable
        ref="confirmForm"
        @confirmed="(reason) => confirmed(reason, handleConfirmedAction)"
    />
</template>

<script setup>
import {defineAsyncComponent, watch} from 'vue';
import {useForm} from '@inertiajs/vue3';
import {useFormAutosave} from '../../../functions/useFormAutosave.js';
import {useConfirmForm} from '../../../functions/useConfirmForm.js';
import {useActionStore} from '../../../functions/useActionStore.js';
import FormInput from '../../../Components/Controls/FormInput.vue';
import FormSelect from '../../../Components/Controls/FormSelect.vue';
import FormDatetime from '../../../Components/Controls/FormDatetime.vue';
import FormAutocomplete from '../../../Components/Controls/FormAutocomplete.vue';
import FormTextarea from '../../../Components/Controls/FormTextarea.vue';
import FormRadio from '../../../Components/Controls/FormRadio.vue';
import FileUploader from '../../../Components/Controls/FileUploader.vue';
import SpinnerButton from '../../../Components/Controls/SpinnerButton.vue';
const ConfirmFormComposable = defineAsyncComponent(() => import('../../../Components/Forms/ConfirmFormComposable.vue'));

const props = defineProps({
    metaData: {type: Object, required: true},
    formData: {type: Object, required: true},
    formConfigs: {type: Object, required: true},
});

const meta = {...props.metaData};
const form = useForm({...props.formData});
const {autosave} = useFormAutosave();
watch(
    () => form.data(),
    (value) => {
        autosave(value, props.formConfigs.routes.update);
    },
    {deep: props.formConfigs.can.update},
);

watch(
    () => form.recipient_is,
    (value) => {
        form.donor_is = null;
        if (props.formConfigs.donor_is_options[value].length === 1) {
            form.donor_is = props.formConfigs.donor_is_options[value][0];
        }
    }
);

const {actionStore} = useActionStore();
let actionStoreName = null;
watch(
    () => actionStore.value,
    (value) => {
        switch (value.name) {
        case 'publish-report':
            publish();
            break;
        case 'addendum-report':
            addendum();
            break;
        case 'destroy-report':
        case 'cancel-report':
            actionStoreName = value.name;
            openConfirmForm(value.config);
            break;
        default :
            return;
        }
    },
    {deep: true}
);
const handleConfirmedAction = (reason) => {
    switch (actionStoreName) {
    case 'destroy-report':
        destroy(reason);
        break;
    case 'cancel-report':
        cancel(reason);
        break;
    default :
        return;
    }
    actionStoreName = null;
};
const handleButtonActionClicked = (actionName) => {
    actionStoreName = actionName;
    openConfirmForm(props.formConfigs.actions.find(a => a.name === actionName).config);
};
const publish = () => form.post(props.formConfigs.routes.publish);
const addendum = () => form.put(props.formConfigs.routes.addendum);
const destroy = (reason) => useForm({reason: reason}).delete(props.formConfigs.routes.destroy);
const cancel = (reason) => useForm({reason: reason}).delete(props.formConfigs.routes.cancel);

const {confirmForm, openConfirmForm, confirmed} = useConfirmForm();
</script>
