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
            label="recipient hn"
            :readonly="true"
            name="recipient_hn"
            v-model="meta.recipient_hn"
        />
        <FormInput
            label="recipient name"
            :readonly="true"
            name="recipient_name"
            v-model="meta.recipient_name"
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
                name="recipient_relate_to_donor"
                :options="['น้อง','พี่','บุตร','ภรรยา', 'สามี', 'มารดา', 'บิดา', 'หลาน', 'ป้า', 'น้า', 'อา', 'ลุง']"
                v-model="form.recipient_is"
            />
            <FormSelect
                label="donor is"
                name="donor_relate_to_recipient"
                :options="['น้อง','พี่','บุตร','ภรรยา', 'สามี', 'มารดา', 'บิดา', 'หลาน', 'ป้า', 'น้า', 'อา', 'ลุง']"
                v-model="form.donor_is"
            />
        </template>
        <FormInput
            label="diagnosis"
            name="diagnosis"
            v-model="form.diagnosis"
        />
        <FormSelect
            label="clinician"
            name="clinician"
            :options="['อ.อรรถพงศ์','อ.นลินี','อ.นัฐสิทธิ์','อ.ปีณิดา']"
            v-model="form.clinician"
        />
        <FormDatetime
            label="collection date"
            name="date_collection"
            v-model="form.date_collection"
        />
        <FormDatetime
            label="report date"
            name="date_report"
            v-model="form.date_report"
        />
        <FormSelect
            label="report by"
            name="reporter"
            :options="['technician 1','technician 2','technician 3']"
            v-model="form.reporter"
        />
        <FormSelect
            label="approve by"
            name="approver"
            :options="['technician 1','technician 2','technician 3']"
            v-model="form.approver"
        />
    </div>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">

    <!--    <h3 class="form-label mt-4 md:mt-8 xl:mt-16">
        Cause to Investigation :
    </h3>
    <div class="mt-4 grid gap-2 md:grid-cols-2 md:gap-4 xl:gap-6">
        <FormCheckbox
            label="HLA Typing"
            v-model="form.request_hla_typing"
            name="request_hla_typing"
        />
        <FormCheckbox
            label="Lymphocyte crossmatch"
            v-model="form.request_lymphocyte_crossmatch"
            name="request_lymphocyte_crossmatch"
        />
    </div>-->
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">

    <transition name="slide-fade">
        <div v-if="meta.request_hla_typing">
            <h3 class="form-label mt-4 md:mt-8 xl:mt-16">
                HLA TYPING :
            </h3>
            <div class="mt-4 grid gap-2 md:grid-cols-2 md:gap-4 xl:gap-6">
                <div
                    class="space-y-2 md:space-y-4 p-2 md:p-4 rounded-lg border-2"
                    :class="{
                        'border-green-400': patient === 'recipient',
                        'border-amber-400': patient === 'donor',
                    }"
                    v-for="patient in meta.patients"
                    :key="patient"
                >
                    <h4 class="form-label underline">
                        {{ patient }}
                    </h4>
                    <FormDatetime
                        label="date test"
                        :name="`${patient}_date_hla_typing`"
                        v-model="form[`${patient}_date_hla_typing`]"
                    />
                    <div class="grid gap-2 md:grid-cols-2 md:gap-4 xl:gap-6">
                        <FormSelect
                            label="abo"
                            :name="`${patient}_abo`"
                            v-model="form[`${patient}_abo`]"
                            :options="['O', 'A', 'B', 'AB']"
                        />
                        <FormSelect
                            label="rh"
                            :name="`${patient}_rh`"
                            v-model="form[`${patient}_rh`]"
                            :options="['positive', 'negative']"
                        />
                    </div>
                    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
                    <label class="form-label italic">class i</label>
                    <div class="grid gap-2 md:grid-cols-2 md:gap-4 xl:gap-6">
                        <FormInput
                            v-for="antigen in antigens"
                            :key="antigen.name"
                            :label="antigen.label"
                            :name="`${patient}_hls_typing_class_i_${antigen.name}`"
                            v-model="form[`${patient}_hls_typing_class_i_${antigen.name}`]"
                        />
                    </div>
                    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
                    <label class="form-label italic">class ii</label>
                    <div class="grid gap-2 md:grid-cols-2 md:gap-4 xl:gap-6">
                        <FormInput
                            v-for="antigen in classIIAntigens.filter(a => a.group === 1)"
                            :key="antigen.name"
                            :label="antigen.label"
                            :name="`${patient}_hls_typing_class_ii_${antigen.name}`"
                            v-model="form[`${patient}_hls_typing_class_ii_${antigen.name}`]"
                        />
                    </div>
                    <div class="grid gap-2 md:grid-cols-3 md:gap-4 xl:gap-6">
                        <FormInput
                            v-for="antigen in classIIAntigens.filter(a => a.group === 2)"
                            :key="antigen.name"
                            :label="antigen.label"
                            :name="`${patient}_hls_typing_class_ii_${antigen.name}`"
                            v-model="form[`${patient}_hls_typing_class_ii_${antigen.name}`]"
                        />
                    </div>
                    <div class="grid gap-2 md:grid-cols-2 md:gap-4 xl:gap-6">
                        <FormInput
                            v-for="antigen in classIIAntigens.filter(a => a.group === 3)"
                            :key="antigen.name"
                            :label="antigen.label"
                            :name="`${patient}_hls_typing_class_ii_${antigen.name}`"
                            v-model="form[`${patient}_hls_typing_class_ii_${antigen.name}`]"
                        />
                    </div>
                    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
                    <FormInput
                        label="hla mismatch"
                        :name="`${patient}_hls_typing_mismatch`"
                        v-model="form[`${patient}_hls_typing_mismatch`]"
                    />
                </div>
            </div>
        </div>
    </transition>
    <transition name="slide-fade">
        <div v-if="meta.request_lymphocyte_crossmatch">
            <h3 class="form-label mt-4 md:mt-8 xl:mt-16">
                lymphocyte crossmatch :
            </h3>
            <div class="mt-4 grid gap-2 md:grid-cols-2 md:gap-4 xl:gap-6">
                <div
                    class="space-y-2 md:space-y-4 p-2 md:p-4 rounded-lg border-2"
                    :class="{
                        'border-green-400': patient === 'recipient',
                        'border-amber-400': patient === 'donor',
                    }"
                    v-for="patient in meta.patients"
                    :key="patient"
                >
                    <h4 class="form-label underline">
                        {{ patient }}
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
                                            :options="lymphocyteCrossmatchOptions"
                                            v-model="form[`${patient}_t_lymphocyte_cdc_neat_rt`]"
                                        />
                                        <FormSelect
                                            class="w-1/2"
                                            label="37℃"
                                            :name="`${patient}_t_lymphocyte_cdc_neat_37_degree_celsius`"
                                            :options="lymphocyteCrossmatchOptions"
                                            v-model="form[`${patient}_t_lymphocyte_cdc_neat_37_degree_celsius`]"
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
                                            :options="lymphocyteCrossmatchOptions"
                                            v-model="form[`${patient}_t_lymphocyte_cdc_dtt_rt`]"
                                        />
                                        <FormSelect
                                            class="w-1/2"
                                            label="37℃"
                                            :name="`${patient}_t_lymphocyte_cdc_dtt_37_degree_celsius`"
                                            :options="lymphocyteCrossmatchOptions"
                                            v-model="form[`${patient}_t_lymphocyte_cdc_dtt_37_degree_celsius`]"
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
                                        :options="lymphocyteCrossmatchOptions"
                                        v-model="form[`${patient}_t_lymphocyte_cdc_ahg_neat_rt`]"
                                    />
                                </div>
                                <div class="w-1/2 text-center p-1 md:p-2 bg-primary rounded">
                                    <label class="form-label">dtt</label>
                                    <FormSelect
                                        label="rt"
                                        :name="`${patient}_t_lymphocyte_cdc_ahg_dtt_rt`"
                                        :options="lymphocyteCrossmatchOptions"
                                        v-model="form[`${patient}_t_lymphocyte_cdc_ahg_dtt_rt`]"
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
                                        :options="lymphocyteCrossmatchOptions"
                                        v-model="form[`${patient}_b_lymphocyte_cdc_neat_37_degree_celsius`]"
                                    />
                                </div>
                                <div class="w-1/2 text-center p-1 md:p-2 bg-primary rounded">
                                    <label class="form-label">dtt</label>
                                    <FormSelect
                                        class="w-1/2"
                                        label="37℃"
                                        :name="`${patient}_b_lymphocyte_cdc_dtt_37_degree_celsius`"
                                        :options="lymphocyteCrossmatchOptions"
                                        v-model="form[`${patient}_b_lymphocyte_cdc_dtt_37_degree_celsius`]"
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
                                        :options="lymphocyteCrossmatchOptions"
                                        v-model="form[`${patient}_b_lymphocyte_cdc_ahg_neat_37_degree_celsius`]"
                                    />
                                </div>
                                <div class="w-1/2 text-center p-1 md:p-2 bg-primary rounded">
                                    <label class="form-label">dtt</label>
                                    <FormSelect
                                        label="37℃"
                                        :name="`${patient}_b_lymphocyte_cdc_ahg_dtt_degree_celsius`"
                                        :options="lymphocyteCrossmatchOptions"
                                        v-model="form[`${patient}_b_lymphocyte_cdc_ahg_dtt_degree_celsius`]"
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
                        v-model="form[`${patient}_t_lymphocyte_crossmatch`]"
                    />
                    <FormInput
                        label="b-lymphocyte"
                        :name="`${patient}_b_lymphocyte_crossmatch`"
                        v-model="form[`${patient}_b_lymphocyte_crossmatch`]"
                    />
                </div>
            </div>
        </div>
    </transition>
</template>

<script setup>
import {reactive} from 'vue';
import FormInput from '../../../Components/Controls/FormInput.vue';
import FormSelect from '../../../Components/Controls/FormSelect.vue';
import FormDatetime from '../../../Components/Controls/FormDatetime.vue';
import FormCheckbox from '../../../Components/Controls/FormCheckbox.vue';
import {useForm} from '@inertiajs/inertia-vue3';

const props = defineProps({
    formData: {type: Object, required: true},
    metaData: {type: Object, required: true},
});
const form = useForm({...props.formData});
const meta = reactive(props.metaData);

const antigens = [
    {name: 'a1', label: 'a'},
    {name: 'a2', label: 'a'},
    {name: 'b1', label: 'b'},
    {name: 'b2', label: 'b'},
    {name: 'c1', label: 'c'},
    {name: 'c2', label: 'c'},
    {name: 'bw4', label: 'bw4'},
    {name: 'bw6', label: 'bw6'},
];

const classIIAntigens = [
    {name: 'drb11', label: 'drb1*', group: 1},
    {name: 'drb12', label: 'drb1*', group: 1},
    {name: 'drb3', label: 'drb3*', group: 2},
    {name: 'drb4', label: 'drb4*', group: 2},
    {name: 'drb5', label: 'drb5*', group: 2},
    {name: 'dqb11', label: 'dqb1*', group: 3},
    {name: 'dqb12', label: 'dqb1*', group: 3},
];

const lymphocyteCrossmatchOptions = ['N', 'WkP', 'P'];
</script>

<style scoped>

</style>
