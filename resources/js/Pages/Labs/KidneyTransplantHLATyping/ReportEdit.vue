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
                :error="form.errors.recipient_is"
            />
            <FormSelect
                :disabled="!form.recipient_is"
                label="donor is"
                name="donor_is"
                :options="formConfigs.donor_is_options[form.recipient_is] ?? []"
                v-model="form.donor_is"
                :error="form.errors.donor_is"
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
            :error="form.errors.clinician"
            :length-to-start="1"
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
            :error="form.errors.date_report"
        />
        <FormInput
            label="report by"
            name="reporter"
            v-model="form.reporter"
            :error="form.errors.reporter"
        />
        <FormInput
            label="approve by"
            name="approver"
            v-model="form.approver"
            :error="form.errors.approver"
        />
    </div>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <transition name="slide-fade">
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
                    />
                    <div class="grid gap-2 md:grid-cols-2 md:gap-4 xl:gap-6">
                        <FormSelect
                            label="abo"
                            :name="`${patient}_abo`"
                            v-model="form[`${patient}_hla_note`].abo"
                            :options="formConfigs.abo_options"
                        />
                        <FormSelect
                            label="rh"
                            :name="`${patient}_rh`"
                            v-model="form[`${patient}_hla_note`].rh"
                            :options="formConfigs.rh_options"
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
                            v-model="form[`${patient}_hla_note`][`hls_typing_class_i_${antigen.name}`]"
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
                            v-model="form[`${patient}_hla_note`][`hls_typing_class_ii_${antigen.name}`]"
                        />
                    </div>
                    <div class="grid gap-2 md:grid-cols-3 md:gap-4 xl:gap-6">
                        <FormInput
                            v-for="antigen in formConfigs.classIIAntigens.filter(a => a.group === 2)"
                            :key="antigen.name"
                            :label="antigen.label"
                            :name="`${patient}_hla_typing_class_ii_${antigen.name}`"
                            v-model="form[`${patient}_hla_note`][`hls_typing_class_ii_${antigen.name}`]"
                        />
                    </div>
                    <div class="grid gap-2 md:grid-cols-2 md:gap-4 xl:gap-6">
                        <FormInput
                            v-for="antigen in formConfigs.classIIAntigens.filter(a => a.group === 3)"
                            :key="antigen.name"
                            :label="antigen.label"
                            :name="`${patient}_hla_typing_class_ii_${antigen.name}`"
                            v-model="form[`${patient}_hla_note`][`hls_typing_class_ii_${antigen.name}`]"
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
    </transition>
    <transition name="slide-fade">
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
                    <label class="form-label italic">t-lymphocyte</label>
                    <div class="flex space-x-1 md:space-x-2">
                        <div class="w-4/6 text-center p-1 md:p-2 bg-white rounded">
                            <label class="form-label">cdc</label>
                            <div class="flex space-x-1 md:space-x-2">
                                <div class="w-1/2 text-center p-1 md:p-2 bg-primary rounded">
                                    <label class="form-label">neat</label>
                                    <div class="flex">
                                        <FormSelect
                                            class="w-1/2"
                                            label="rt"
                                            :name="`${patient}_t_lymphocyte_cdc_neat_rt`"
                                            :options="formConfigs.lymphocyteCrossmatchOptions"
                                            v-model="form[`${patient}_cxm_note`].t_lymphocyte_cdc_neat_rt"
                                        />
                                        <FormSelect
                                            class="w-1/2"
                                            label="37℃"
                                            :name="`${patient}_t_lymphocyte_cdc_neat_37_degree_celsius`"
                                            :options="formConfigs.lymphocyteCrossmatchOptions"
                                            v-model="form[`${patient}_cxm_note`].t_lymphocyte_cdc_neat_37_degree_celsius"
                                        />
                                    </div>
                                </div>
                                <div class="w-1/2 text-center p-1 md:p-2 bg-primary rounded">
                                    <label class="form-label">dtt</label>
                                    <div class="flex">
                                        <FormSelect
                                            class="w-1/2"
                                            label="rt"
                                            :name="`${patient}_t_lymphocyte_cdc_dtt_rt`"
                                            :options="formConfigs.lymphocyteCrossmatchOptions"
                                            v-model="form[`${patient}_cxm_note`].t_lymphocyte_cdc_dtt_rt"
                                        />
                                        <FormSelect
                                            class="w-1/2"
                                            label="37℃"
                                            :name="`${patient}_t_lymphocyte_cdc_dtt_37_degree_celsius`"
                                            :options="formConfigs.lymphocyteCrossmatchOptions"
                                            v-model="form[`${patient}_cxm_note`].t_lymphocyte_cdc_dtt_37_degree_celsius"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-2/6 text-center p-1 md:p-2 bg-white rounded">
                            <label class="form-label">cdc - ahg</label>
                            <div class="flex space-x-1 md:space-x-2">
                                <div class="w-1/2 text-center p-1 md:p-2 bg-primary rounded">
                                    <label class="form-label">neat</label>
                                    <FormSelect
                                        label="rt"
                                        :name="`${patient}_t_lymphocyte_cdc_ahg_neat_rt`"
                                        :options="formConfigs.lymphocyteCrossmatchOptions"
                                        v-model="form[`${patient}_cxm_note`].t_lymphocyte_cdc_ahg_neat_rt"
                                    />
                                </div>
                                <div class="w-1/2 text-center p-1 md:p-2 bg-primary rounded">
                                    <label class="form-label">dtt</label>
                                    <FormSelect
                                        label="rt"
                                        :name="`${patient}_t_lymphocyte_cdc_ahg_dtt_rt`"
                                        :options="formConfigs.lymphocyteCrossmatchOptions"
                                        v-model="form[`${patient}_cxm_note`].t_lymphocyte_cdc_ahg_dtt_rt"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
                    <label class="form-label italic">b-lymphocyte</label>
                    <div class="flex space-x-1 md:space-x-2">
                        <div class="w-1/2 text-center p-1 md:p-2 bg-white rounded">
                            <label class="form-label">cdc</label>
                            <div class="flex space-x-1 md:space-x-2">
                                <div class="w-1/2 text-center p-1 md:p-2 bg-primary rounded">
                                    <label class="form-label">neat</label>
                                    <FormSelect
                                        class="w-1/2"
                                        label="37℃"
                                        :name="`${patient}_b_lymphocyte_cdc_neat_37_degree_celsius`"
                                        :options="formConfigs.lymphocyteCrossmatchOptions"
                                        v-model="form[`${patient}_cxm_note`].b_lymphocyte_cdc_neat_37_degree_celsius"
                                    />
                                </div>
                                <div class="w-1/2 text-center p-1 md:p-2 bg-primary rounded">
                                    <label class="form-label">dtt</label>
                                    <FormSelect
                                        class="w-1/2"
                                        label="37℃"
                                        :name="`${patient}_b_lymphocyte_cdc_dtt_37_degree_celsius`"
                                        :options="formConfigs.lymphocyteCrossmatchOptions"
                                        v-model="form[`${patient}_cxm_note`].b_lymphocyte_cdc_dtt_37_degree_celsius"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="w-1/2 text-center p-1 md:p-2 bg-white rounded">
                            <label class="form-label">cdc - ahg</label>
                            <div class="flex space-x-1 md:space-x-2">
                                <div class="w-1/2 text-center p-1 md:p-2 bg-primary rounded">
                                    <label class="form-label">neat</label>
                                    <FormSelect
                                        label="37℃"
                                        :name="`${patient}_b_lymphocyte_cdc_ahg_neat_37_degree_celsius`"
                                        :options="formConfigs.lymphocyteCrossmatchOptions"
                                        v-model="form[`${patient}_cxm_note`].b_lymphocyte_cdc_ahg_neat_37_degree_celsius"
                                    />
                                </div>
                                <div class="w-1/2 text-center p-1 md:p-2 bg-primary rounded">
                                    <label class="form-label">dtt</label>
                                    <FormSelect
                                        label="37℃"
                                        :name="`${patient}_b_lymphocyte_cdc_ahg_dtt_degree_celsius`"
                                        :options="formConfigs.lymphocyteCrossmatchOptions"
                                        v-model="form[`${patient}_cxm_note`].b_lymphocyte_cdc_ahg_dtt_degree_celsius"
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
    </transition>
</template>

<script setup>
import FormInput from '../../../Components/Controls/FormInput.vue';
import FormSelect from '../../../Components/Controls/FormSelect.vue';
import FormDatetime from '../../../Components/Controls/FormDatetime.vue';
import {watch} from 'vue';
import {useForm} from '@inertiajs/inertia-vue3';
import debounce from 'lodash/debounce';
import FormAutocomplete from '../../../Components/Controls/FormAutocomplete.vue';

const props = defineProps({
    metaData: {type: Object, required: true},
    formData: {type: Object, required: true},
    formConfigs: {type: Object, required: true},
});

const meta = {...props.metaData};
const form = useForm({...props.formData});

watch(
    () => form,
    (value) => {
        autosave(value);
    },
    {deep: true},
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

const autosave = debounce((value) => {
    window.axios
        .patch(props.formConfigs.routes.update, {...value.data()})
        .catch((error) => {
            console.log(error);
        });
}, 2000);
</script>

<style scoped>

</style>
