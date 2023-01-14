<template>
    <h2 class="form-label text-lg italic text-complement scroll-mt-16 md:scroll-mt-8">
        ADMISSION DATA
    </h2>
    <hr class="my-4 border-b border-accent">
    <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8">
        <FormInput
            label="admitted on"
            :readonly="true"
            name="admitted_at"
            v-model="form.admitted_at"
        />
        <FormInput
            label="discharged on"
            :readonly="true"
            name="discharged_at"
            v-model="form.discharged_at"
        />
        <FormInput
            label="discharge status"
            :readonly="true"
            name="discharge_status"
            v-model="form.discharge_status"
        />
        <FormInput
            label="discharge type"
            :readonly="true"
            name="discharge_type"
            v-model="form.discharge_type"
        />
        <FormAutocomplete
            label="nephrologist"
            name="nephrologist"
            v-model="form.nephrologist"
            :endpoint="configs.routes.people"
            :params="configs.routes.nephrologists_scope"
            :error="form.errors.nephrologist"
            :length-to-start="3"
        />
        <FormAutocomplete
            label="surgeon"
            name="surgeon"
            v-model="form.surgeon"
            :endpoint="configs.routes.people"
            :params="configs.routes.surgeons_scope"
            :error="form.errors.surgeon"
            :length-to-start="3"
        />
        <!--input date off drain using FormDatetime-->
        <FormDatetime
            name="date_off_drain"
            label="date off drain"
            v-model="form.date_off_drain"
            :error="form.errors.date_off_drain"
        />
        <!--input date off foley -->
        <FormDatetime
            name="date_off_foley"
            label="date off foley"
            v-model="form.date_off_foley"
            :error="form.errors.date_off_foley"
        />
        <FormSelect
            label="medical scheme"
            name="insurance"
            v-model="form.insurance"
            :options="configs.insurances"
            :allow-other="true"
            ref="insuranceInput"
            :error="$page.props.errors.insurance"
        />
        <FormInput
            label="cost (baht)"
            name="cost"
            v-model="form.cost"
            type="number"
            :error="$page.props.errors.cost"
        />
        <FormInput
            label="length of stay (days)"
            name="los"
            v-model="form.los"
            :readonly="true"
        />
        <FormInput
            label="discharged at"
            name="ward_discharged"
            v-model="form.ward_discharged"
            :readonly="true"
        />
    </div>

    <h2 class="form-label text-lg italic text-complement mt-4 md:mt-8 xl:mt-16 scroll-mt-16 md:scroll-mt-8">
        CLINICAL DATA
    </h2>
    <hr class="my-4 border-b border-accent">

    <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8">
        <FormSelect
            label="cause of esrd"
            name="cause_of_esrd"
            :options="configs.esrd_causes"
            :allow-other="true"
            :error="$page.props.errors.cause_of_esrd"
        />
        <div>
            <label class="form-label">donor type :</label>
            <FormRadio
                class="grid grid-cols-2 gap-4"
                name="donor_type"
                v-model="form.donor_type"
                :options="configs.donor_types"
                :error="$page.props.errors.donor_type"
            />
        </div>
    </div>

    <Transition name="slide-fade">
        <div
            class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8"
            v-if="form.donor_type === 'LD'"
        >
            <FormSelect
                label="recipient is"
                name="recipient_is"
                :options="configs.recipient_is_options"
                v-model="form.recipient_is"
                :error="$page.props.errors.recipient_is"
            />
            <FormSelect
                :disabled="!form.recipient_is"
                label="donor is"
                name="donor_is"
                :options="configs.donor_is_options[form.recipient_is] ?? []"
                v-model="form.donor_is"
                :error="$page.props.errors.donor_is"
            />
        </div>
    </Transition>

    <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8">
        <FormSelect
            label="abo"
            name="blood_group_abo"
            v-model="form.blood_group_abo"
            :options="configs.abo_options"
            :error="$page.props.errors.blood_group_abo"
        />
        <FormSelect
            label="rh"
            name="blood_group_rh"
            v-model="form.blood_group_rh"
            :options="configs.rh_options"
            :error="$page.props.errors.blood_group_rh"
        />
    </div>
    <h3 class="form-label mt-4 md:mt-8 xl:mt-16">
        hla mismatch :
    </h3>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8">
        <div
            v-for="antigen in configs.hla_mismatch_antigens"
            :key="antigen"
        >
            <label class="form-label">{{ antigen }} :</label>
            <FormRadio
                class="grid grid-cols-2 gap-4"
                :name="`hla_mismatch_${antigen.toLowerCase()}`"
                v-model="form[`hla_mismatch_${antigen.toLowerCase()}`]"
                :options="configs.hla_mismatch_options"
                :error="$page.props.errors[`hla_mismatch_${antigen.toLowerCase()}`]"
                :allow-reset="true"
            />
        </div>
    </div>
    <h3 class="form-label mt-4 md:mt-8 xl:mt-16">
        pra :
    </h3>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8">
        <FormInput
            label="class i (%)"
            name="pra_class_i"
            v-model="form.pra_class_i"
            type="number"
            :error="$page.props.errors.pra_class_i"
        />
        <FormInput
            label="class ii (%)"
            name="pra_class_ii"
            v-model="form.pra_class_ii"
            type="number"
            :error="$page.props.errors.pra_class_ii"
        />
    </div>
    <h3 class="form-label mt-4 md:mt-8 xl:mt-16">
        crossmatch :
    </h3>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <div
        class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8"
        v-for="crossmatch in configs.crossmatches"
    >
        <FormRadio
            :label="crossmatch.label"
            :name="crossmatch.name"
            v-model="form[crossmatch.name]"
            :options="configs.crossmatch_options"
            :error="$page.props.errors[crossmatch.name]"
            :allow-reset="true"
        />
        <FormInput
            :label="`specify ${crossmatch.label} positive`"
            :name="`${crossmatch.name}_positive_specify`"
            v-model="form[`${crossmatch.name}_positive_specify`]"
            :error="$page.props.errors[`${crossmatch.name}_positive_specify`]"
            :disabled="form[crossmatch.name] !== 'positive'"
        />
    </div>
    <!--    <FormInput
        label="crossmatch"
        name="crossmatch"
        v-model="form.crossmatch"
        :error="$page.props.errors.crossmatch"
    />-->
    <h3 class="form-label mt-4 md:mt-8 xl:mt-16">
        attachments :
    </h3>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <ImageUploader
        v-for="index in 3"
        :key="index"
        :name="`clinical_data_attachment_${index}`"
        v-model="form[`clinical_data_attachment_${index}`]"
        :service-endpoints="configs.routes.upload"
        :pathname="configs.attachment_upload_pathname"
        :label="`clinical data attachment#${index}`"
    />
    <h2 class="form-label text-lg italic text-complement mt-4 md:mt-8 xl:mt-16 scroll-mt-16 md:scroll-mt-8">
        COMORBIDITIES
    </h2>
    <hr class="my-4 border-b border-accent">
    <div
        class="grid gap-2 md:gap-4 grid-cols-2 xl:gap-8 mb-2 md:mb-4"
        v-for="(comorbidity, key) in configs.comorbid_a"
        :key="key"
    >
        <FormCheckbox
            :name="comorbidity.name"
            :label="comorbidity.label"
            v-model="form.comorbidities[comorbidity.name]"
        />
        <FormDatetime
            :name="`date_${comorbidity.name}`"
            v-model="form.comorbidities[`date_${comorbidity.name}`]"
            :error="$page.props.errors[`date_${comorbidity.name}`]"
            :placeholder="`Date of ${comorbidity.label}`"
        />
    </div>
    <div class="grid gap-2 md:gap-4 grid-cols-2 xl:gap-8 mb-2 md:mb-4">
        <FormCheckbox
            name="HT"
            label="HT"
            v-model="form.comorbidities.HT"
        />
        <FormDatetime
            name="date_HT"
            v-model="form.comorbidities.date_HT"
            :error="$page.props.errors.date_HT"
            placeholder="Date of HT"
        />
    </div>
    <div class="grid gap-2 md:gap-4 grid-cols-2 xl:gap-8 mb-2 md:mb-4">
        <FormCheckbox
            name="on_HT_medication"
            label="On HT Medication"
            v-model="form.comorbidities.HT"
        />
        <div class="space-y-2 md:space-y-4">
            <FormDatetime
                name="date_on_HT_medication"
                v-model="form.comorbidities.date_on_HT_medication"
                :error="$page.props.errors.date_on_HT_medication"
                placeholder="Date start HT medication"
            />
            <FormInput
                name="HT_medication"
                v-model="form.comorbidities.HT_medication"
                :error="$page.props.errors.HT_medication"
                placeholder="HT medication"
            />
        </div>
    </div>
    <div class="grid gap-2 md:gap-4 grid-cols-2 xl:gap-8 mb-2 md:mb-4">
        <FormCheckbox
            name="DM"
            label="DM"
            v-model="form.comorbidities.DM"
        />
        <FormDatetime
            name="date_DM"
            v-model="form.comorbidities.date_DM"
            :error="$page.props.errors.date_DM"
            placeholder="Date of DM"
        />
    </div>
    <div class="grid gap-2 md:gap-4 grid-cols-2 xl:gap-8 mb-2 md:mb-4">
        <FormCheckbox
            name="on_DM_medication"
            label="On DM Medication"
            v-model="form.comorbidities.DM"
        />
        <div class="space-y-2 md:space-y-4">
            <FormDatetime
                name="date_on_DM_medication"
                v-model="form.comorbidities.date_on_DM_medication"
                :error="$page.props.errors.date_on_DM_medication"
                placeholder="Date start DM medication"
            />
            <FormInput
                name="DM_medication"
                v-model="form.comorbidities.DM_medication"
                :error="$page.props.errors.DM_medication"
                placeholder="DM medication"
            />
        </div>
    </div>
    <div
        class="grid gap-2 md:gap-4 grid-cols-2 xl:gap-8 mb-2 md:mb-4"
        v-for="(comorbidity, key) in configs.comorbid_b"
        :key="key"
    >
        <FormCheckbox
            :name="comorbidity.name"
            :label="comorbidity.label"
            v-model="form.comorbidities[comorbidity.name]"
        />
        <FormDatetime
            :name="`date_${comorbidity.name}`"
            v-model="form.comorbidities[`date_${comorbidity.name}`]"
            :error="$page.props.errors[`date_${comorbidity.name}`]"
            :placeholder="`Date of ${comorbidity.label}`"
        />
    </div>
    <div class="grid gap-2 md:gap-4 grid-cols-2 xl:gap-8 mb-2 md:mb-4">
        <FormSelect
            label="smoking"
            name="smoking"
            v-model="form.comorbidities.smoking"
            :options="configs.smoking_options"
            :error="$page.props.errors.smoking"
        />
        <FormDatetime
            label="date start smoking"
            name="date_smoking"
            v-model="form.comorbidities.date_smoking"
            :error="$page.props.errors.date_smoking"
        />
    </div>
    <h2 class="form-label text-lg italic text-complement mt-4 md:mt-8 xl:mt-16 scroll-mt-16 md:scroll-mt-8">
        OPERATIVE DATA
    </h2>
    <hr class="my-4 border-b border-accent">
    <Transition name="slide-fade">
        <div v-if="form.donor_type === 'CD'">
            <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8">
                <FormDatetime
                    label="date of harvest start"
                    name="date_harvest_start"
                    v-model="form.date_harvest"
                    :error="$page.props.errors.date_harvest_start"
                />
                <FormDatetime
                    label="time of harvest start"
                    name="time_harvest_start"
                    v-model="form.time_harvest"
                    mode="time"
                    :error="$page.props.errors.time_harvest_start"
                />
                <FormDatetime
                    label="date of harvest finish"
                    name="date_harvest_finish"
                    v-model="form.date_harvest"
                    :error="$page.props.errors.date_harvest_finish"
                />
                <FormDatetime
                    label="time of harvest finish"
                    name="time_harvest_finish"
                    v-model="form.time_harvest"
                    mode="time"
                    :error="$page.props.errors.time_harvest_finish"
                />
            </div>
            <hr class="border border-dashed my-2 md:my-4 xl:my-8">
        </div>
    </Transition>
    <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8">
        <FormDatetime
            label="date of operation start"
            name="date_operation_start"
            v-model="form.date_operation"
            :error="$page.props.errors.date_operation_start"
        />
        <FormDatetime
            label="time of operation start"
            name="time_operation_start"
            v-model="form.time_operation"
            mode="time"
            :error="$page.props.errors.time_operation_start"
        />
        <FormDatetime
            label="date of operation finish"
            name="date_operation_finish"
            v-model="form.date_operation"
            :error="$page.props.errors.date_operation_finish"
        />
        <FormDatetime
            label="time of operation finish"
            name="time_operation_finish"
            v-model="form.time_operation"
            mode="time"
            :error="$page.props.errors.time_operation_finish"
        />
    </div>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8">
        <FormInput
            v-for="(input, key) in configs.operative_data"
            :key="key"
            :name="input.name"
            :label="input.label"
            v-model="form[input.name]"
            :error="$page.props.errors[input.name]"
            type="tel"
        />
    </div>
    <h3 class="form-label mt-4 md:mt-8 xl:mt-16">
        attachments :
    </h3>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <ImageUploader
        v-for="index in 3"
        :key="index"
        :name="`operative_data_attachment_${index}`"
        v-model="form[`operative_data_attachment_${index}`]"
        :service-endpoints="configs.routes.upload"
        :pathname="configs.attachment_upload_pathname"
        :label="`operative data attachment#${index}`"
    />
    <h2 class="form-label text-lg italic text-complement mt-4 md:mt-8 xl:mt-16 scroll-mt-16 md:scroll-mt-8">
        OUTCOMES
    </h2>
    <hr class="my-4 border-b border-accent">
    <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8">
        <FormSelect
            label="graft function"
            name="graft_function"
            v-model="form.graft_function"
            :options="configs.graft_function_options"
            :error="$page.props.errors.graft_function"
        />
        <div>
            <Transition name="slide-fade">
                <div
                    v-if="form.graft_function === 'delayed graft function'"
                    class="space-y-2 md:space-y-4"
                >
                    <FormRadio
                        label="dialysis mode"
                        name="graft_function_dialysis_mode"
                        v-model="form.graft_function_dialysis_mode"
                        :options="configs.dialysis_mode_options"
                        :error="$page.props.errors.graft_function_dialysis_mode"
                    />
                    <FormDatetime
                        label="date of dialysis start"
                        name="graft_function_date_dialysis_start"
                        v-model="form.graft_function_date_dialysis"
                        :error="$page.props.errors.graft_function_date_dialysis_start"
                    />
                    <div>
                        <label class="form-label">indication for dialysis</label>
                        <FormCheckbox
                            class="my-2 md:my-4 xl:my-8"
                            v-for="(field, key) in configs.dialysis_indication_fields"
                            :key="key"
                            :label="field.label"
                            :name="`graft_function_${field.name}`"
                            v-model="form[`graft_function_${field.name}`]"
                            :error="$page.props.errors[`graft_function_${field.name}`]"
                        />
                        <FormInput
                            name="graft_function_dialysis_indication_other"
                            v-model="form.graft_function_dialysis_indication_other"
                            :error="$page.props.errors.graft_function_dialysis_indication_other"
                            placeholder="other indication"
                        />
                    </div>
                </div>
            </Transition>
            <Transition name="slide-fade">
                <FormCheckbox
                    v-if="form.graft_function === 'primary non-function'"
                    class="md:mt-10"
                    label="graft nephrectomy"
                    name="graft_function_graft_nephrectomy"
                    v-model="form.graft_function_graft_nephrectomy"
                    :error="$page.props.errors.graft_function_graft_nephrectomy"
                />
            </Transition>
        </div>
    </div>
    <h3 class="form-label mt-4 md:mt-8 xl:mt-16">
        graft biopsy :
    </h3>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <button
        class="btn btn-accent"
        @click="addBiopsy"
    >
        ADD
    </button>
    <div
        v-for="(biopsy, key) in form.graft_biopsy"
        :key="key"
        class="my-2 md:my-4 space-y-2 md:space-y-4"
    >
        <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8">
            <FormDatetime
                :label="`date of graft biopsy#${key+1}`"
                :name="`date_graft_biopsy`"
                v-model="biopsy.date_graft_biopsy"
            />
            <FormSelect
                :label="`result graft biopsy#${key+1}`"
                name="graft_biopsy_result"
                v-model="biopsy.graft_biopsy_result"
                :options="configs.graft_biopsy_result_options"
                :allow-other="true"
            />

            <ImageUploader
                :label="`attachment biopsy#${key+1}`"
                :service-endpoints="configs.routes.upload"
                :pathname="configs.attachment_upload_pathname"
            />
            <button
                class="btn btn-danger"
                @click="removeBiopsy(key)"
            >
                REMOVE
            </button>
        </div>
        <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    </div>
    <h2 class="form-label text-lg italic text-complement mt-4 md:mt-8 xl:mt-16 scroll-mt-16 md:scroll-mt-8">
        complications
    </h2>
    <hr class="my-4 border-b border-accent">
    <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8">
        <div>
            <label class="form-label">infection :</label>
            <FormCheckbox
                v-for="(field, key) in configs.complication_infection_fields"
                :key="key"
                :label="field.label"
                :name="`complication_infection_${field.name}`"
                v-model="form[`complication_infection_${field.name}`]"
            />
            <FormInput
                name="complication_infection_other"
                v-model="form.complication_infection_other"
                placeholder="other infection"
            />
        </div>
        <div>
            <label class="form-label">hematoma :</label>
            <FormCheckbox
                label="hematoma"
                name="complication_hematoma"
                v-model="form.complication_hematoma"
            />
            <label class="form-label mt-2 md:mt-4 xl:mt-8">vascular :</label>
            <FormCheckbox
                v-for="(field, key) in configs.complication_vascular_fields"
                :key="key"
                :label="field.label"
                :name="`complication_infection_${field.name}`"
                v-model="form[`complication_infection_${field.name}`]"
            />
        </div>
        <div>
            <label class="form-label">investications :</label>
            <FormCheckbox
                v-for="(field, key) in configs.complication_investigation_fields"
                :key="key"
                :label="field.label"
                :name="`complication_investigation_${field.name}`"
                v-model="form[`complication_investigation_${field.name}`]"
            />
        </div>
        <div>
            <label class="form-label">urological complications :</label>
            <FormCheckbox
                v-for="(field, key) in configs.complication_urological_fields"
                :key="key"
                :label="field.label"
                :name="`complication_urological_${field.name}`"
                v-model="form[`complication_urological_${field.name}`]"
            />
        </div>
    </div>
</template>

<script setup>
import {useForm} from '@inertiajs/inertia-vue3';
import {nextTick, reactive, ref} from 'vue';
import FormAutocomplete from '../../../Components/Controls/FormAutocomplete.vue';
import FormInput from '../../../Components/Controls/FormInput.vue';
import FormSelect from '../../../Components/Controls/FormSelect.vue';
import FormRadio from '../../../Components/Controls/FormRadio.vue';
import FileUploader from '../../../Components/Controls/FileUploader.vue';
import FormCheckbox from '../../../Components/Controls/FormCheckbox.vue';
import FormDatetime from '../../../Components/Controls/FormDatetime.vue';
import ImageUploader from '../../../Components/Controls/ImageUploader.vue';

const props = defineProps({
    formData: {type: Object, required: true},
    formConfigs: {type: Object, required: true},
});

const form = useForm({...props.formData});
const configs = reactive({...props.formConfigs});

const insuranceInput = ref(null);

function addBiopsy() {
    form.graft_biopsy.push({
        date_graft_biopsy: null,
        graft_biopsy_result: null,
        graft_biopsy_attachment: null,
    });
}

function removeBiopsy(index) {
    let temp = [...form.graft_biopsy];
    temp.splice(index, 1);
    form.graft_biopsy = [];
    nextTick(() => {
        form.graft_biopsy = temp;
    });
}
</script>

<style scoped>

</style>
