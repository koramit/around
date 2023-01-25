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
            :error="$page.props.errors.nephrologist"
            :length-to-start="3"
        />
        <FormAutocomplete
            label="surgeon"
            name="surgeon"
            v-model="form.surgeon"
            :endpoint="configs.routes.people"
            :params="configs.routes.surgeons_scope"
            :error="$page.props.errors.surgeon"
            :length-to-start="3"
        />
        <FormDatetime
            name="date_off_drain"
            label="date off drain"
            v-model="form.date_off_drain"
            :error="$page.props.errors.date_off_drain"
        />
        <FormDatetime
            name="date_off_foley"
            label="date off foley"
            v-model="form.date_off_foley"
            :error="$page.props.errors.date_off_foley"
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
    <h3 class="form-label mt-4 md:mt-8 xl:mt-16">
        transfer :
    </h3>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <FormCheckbox
        label="Patient transferred"
        name="patient_transferred"
        v-model="form.patient_transferred"
    />
    <Transition name="slide-fade">
        <div v-if="form.patient_transferred">
            <label class="mt-2 md:mt-4 form-label">
                Transfer to :
            </label>
            <div class="grid gap-2 md:gap-4 md:block md:space-y-0 md:space-x-4">
                <button
                    v-for="(ward, key) in configs.common_transfer_wards"
                    :key="key"
                    class="text-left"
                    @click="form.patient_transferred_to = ward"
                >
                    <span class="italic underline text-accent">{{ ward }}</span>
                </button>
            </div>
            <FormAutocomplete
                class="mt-2 md:mt-4"
                name="patient_transferred_to"
                v-model="form.patient_transferred_to"
                :endpoint="configs.routes.wards"
                :error="$page.props.errors.patient_transferred_to"
            />
        </div>
    </Transition>

    <h2 class="form-label text-lg italic text-complement mt-4 md:mt-8 xl:mt-16 scroll-mt-16 md:scroll-mt-8">
        CLINICAL DATA
    </h2>
    <hr class="my-4 border-b border-accent">

    <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8">
        <FormSelect
            label="cause of esrd"
            name="cause_of_esrd"
            v-model="form.cause_of_esrd"
            :options="configs.cause_of_esrd_options"
            :allow-other="true"
            :error="$page.props.errors.cause_of_esrd"
            ref="causeOfEsrdInput"
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
            name="pra_class_i_percent"
            v-model="form.pra_class_i_percent"
            type="number"
            :error="$page.props.errors.pra_class_i_percent"
        />
        <FormInput
            label="class ii (%)"
            name="pra_class_ii_percent"
            v-model="form.pra_class_ii_percent"
            type="number"
            :error="$page.props.errors.pra_class_ii_percent"
        />
    </div>
    <h3 class="form-label mt-4 md:mt-8 xl:mt-16">
        crossmatch :
    </h3>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <div
        class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8"
        v-for="(crossmatch, key) in configs.crossmatches"
        :key="key"
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
            :name="`${crossmatch.name}_positive_specification`"
            v-model="form[`${crossmatch.name}_positive_specification`]"
            :error="$page.props.errors[`${crossmatch.name}_positive_specification`]"
            :disabled="form[crossmatch.name] !== 'positive'"
        />
    </div>
    <h3
        id="clinical_data_attachments"
        class="form-label mt-4 md:mt-8 xl:mt-16 scroll-mt-16 md:scroll-mt-8"
    >
        attachments :
    </h3>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <MultiImageUploader
        :service-endpoints="configs.routes.upload"
        :pathname="configs.attachment_upload_pathname"
        name="clinical_data_attachments"
        v-model="form.clinical_data_attachments"
    />
    <h2 class="form-label text-lg italic text-complement mt-4 md:mt-8 xl:mt-16 scroll-mt-16 md:scroll-mt-8">
        COMORBIDITIES
    </h2>
    <hr class="my-4 border-b border-accent">
    <FormCheckbox
        label="None"
        name="comorbidities.none"
        v-model="form.comorbidities.none"
        :toggler="true"
    />
    <small
        class="text-red-400 text-sm scroll-mt-16 md:scroll-mt-8"
        id="comorbidities.none"
    >
        {{ $page.props.errors['comorbidities.none'] }}
    </small>
    <Transition name="slide-fade">
        <div
            class="mt-2 md:mt-4"
            v-if="!form.comorbidities.none"
        >
            <AlertMessage
                title="Date diagnosis guideline"
                message="Pick 15th in case of unknown date. Pick July in case of unknown month."
            />
            <div
                class="mt-2 md:mt-4"
                v-for="(comorbidity, key) in configs.comorbid_a"
                :key="key"
            >
                <div class="grid gap-2 md:gap-4 grid-cols-2 xl:gap-8 mb-2 md:mb-4 md:pb-4 md:border-b-2 border-dashed">
                    <FormCheckbox
                        :name="`comorbidities.${comorbidity.name}`"
                        :label="comorbidity.label"
                        v-model="form.comorbidities[comorbidity.name]"
                    />
                    <FormDatetime
                        :name="`comorbidities.date_${comorbidity.name}`"
                        v-model="form.comorbidities[`date_${comorbidity.name}`]"
                        :error="$page.props.errors[`comorbidities.date_${comorbidity.name}`]"
                        :placeholder="`Date of ${comorbidity.label}`"
                        :disabled="!form.comorbidities[comorbidity.name]"
                    />
                </div>
            </div>
            <div class="grid gap-2 md:gap-4 grid-cols-2 xl:gap-8 mb-2 md:mb-4 md:pb-4 md:border-b-2 border-dashed">
                <FormCheckbox
                    name="comorbidities.HT"
                    label="HT"
                    v-model="form.comorbidities.HT"
                />
                <FormDatetime
                    name="comorbidities.date_HT"
                    v-model="form.comorbidities.date_HT"
                    :error="$page.props.errors['comorbidities.date_HT']"
                    placeholder="Date of HT"
                    :disabled="!form.comorbidities.HT"
                />
            </div>
            <div class="grid gap-2 md:gap-4 grid-cols-2 xl:gap-8 mb-2 md:mb-4 md:pb-4 md:border-b-2 border-dashed">
                <FormCheckbox
                    name="comorbidities.on_HT_medication"
                    label="On HT Medication"
                    v-model="form.comorbidities.on_HT_medication"
                />
                <div class="space-y-2 md:space-y-4">
                    <FormDatetime
                        name="comorbidities.date_start_HT_medication"
                        v-model="form.comorbidities.date_start_HT_medication"
                        :error="$page.props.errors['comorbidities.date_start_HT_medication']"
                        placeholder="Date start HT medication"
                        :disabled="!form.comorbidities.on_HT_medication"
                    />
                    <FormInput
                        name="comorbidities.HT_medication"
                        v-model="form.comorbidities.HT_medication"
                        :error="$page.props.errors['comorbidities.HT_medication']"
                        placeholder="HT medication"
                        :disabled="!form.comorbidities.on_HT_medication"
                    />
                </div>
            </div>
            <div class="grid gap-2 md:gap-4 grid-cols-2 xl:gap-8 mb-2 md:mb-4 md:pb-4 md:border-b-2 border-dashed">
                <FormCheckbox
                    name="comorbidities.DM"
                    label="DM"
                    v-model="form.comorbidities.DM"
                />
                <FormDatetime
                    name="comorbidities.date_DM"
                    v-model="form.comorbidities.date_DM"
                    :error="$page.props.errors['comorbidities.date_DM']"
                    placeholder="Date of DM"
                    :disabled="!form.comorbidities.DM"
                />
            </div>
            <div class="grid gap-2 md:gap-4 grid-cols-2 xl:gap-8 mb-2 md:mb-4 md:pb-4 md:border-b-2 border-dashed">
                <FormCheckbox
                    name="comorbidities.on_DM_medication"
                    label="On DM Medication"
                    v-model="form.comorbidities.on_DM_medication"
                />
                <div class="space-y-2 md:space-y-4">
                    <FormDatetime
                        name="comorbidities.date_start_DM_medication"
                        v-model="form.comorbidities.date_start_DM_medication"
                        :error="$page.props.errors['comorbidities.date_start_DM_medication']"
                        placeholder="Date start DM medication"
                        :disabled="!form.comorbidities.on_DM_medication"
                    />
                    <FormInput
                        name="comorbidities.DM_medication"
                        v-model="form.comorbidities.DM_medication"
                        :error="$page.props.errors['comorbidities.DM_medication']"
                        placeholder="DM medication"
                        :disabled="!form.comorbidities.on_DM_medication"
                    />
                </div>
            </div>
            <div
                v-for="(comorbidity, key) in configs.comorbid_b"
                :key="key"
            >
                <div class="grid gap-2 md:gap-4 grid-cols-2 xl:gap-8 mb-2 md:mb-4 md:pb-4 md:border-b-2 border-dashed">
                    <FormCheckbox
                        :name="`comorbidities.${comorbidity.name}`"
                        :label="comorbidity.label"
                        v-model="form.comorbidities[comorbidity.name]"
                    />
                    <FormDatetime
                        :name="`comorbidities.date_${comorbidity.name}`"
                        v-model="form.comorbidities[`date_${comorbidity.name}`]"
                        :error="$page.props.errors[`comorbidities.date_${comorbidity.name}`]"
                        :placeholder="`Date of ${comorbidity.label}`"
                        :disabled="!form.comorbidities[comorbidity.name]"
                        v-if="comorbidity.name !== 'on_allopurinol'"
                    />
                    <FormDatetime
                        name="comorbidities.date_start_allopurinol"
                        v-model="form.comorbidities.date_start_allopurinol"
                        :error="$page.props.errors['comorbidities.date_start_allopurinol']"
                        placeholder="Date start allopurinol"
                        :disabled="!form.comorbidities[comorbidity.name]"
                        v-else
                    />
                </div>
            </div>
            <div class="grid gap-2 md:gap-4 grid-cols-2 xl:gap-8 mb-2 md:mb-4 md:pb-4 md:border-b-2 border-dashed">
                <FormCheckbox
                    name="comorbidities.cancer"
                    label="Cancer"
                    v-model="form.comorbidities.cancer"
                />
                <div class="space-y-2 md:space-y-4">
                    <FormDatetime
                        name="comorbidities.date_cancer"
                        v-model="form.comorbidities.date_cancer"
                        :error="$page.props.errors['comorbidities.date_cancer']"
                        placeholder="Date of cancer"
                        :disabled="!form.comorbidities.cancer"
                    />
                    <FormInput
                        name="comorbidities.cancer_type"
                        v-model="form.comorbidities.cancer_type"
                        :error="$page.props.errors['comorbidities.cancer_type']"
                        placeholder="cancer type"
                        :disabled="!form.comorbidities.cancer"
                    />
                </div>
            </div>
            <div class="grid gap-2 md:gap-4 grid-cols-2 xl:gap-8 mb-2 md:mb-4">
                <FormSelect
                    label="smoking"
                    name="comorbidities.smoking"
                    v-model="form.comorbidities.smoking"
                    :options="configs.smoking_options"
                    :error="$page.props.errors['comorbidities.smoking']"
                />
                <FormDatetime
                    label="date start smoking"
                    name="comorbidities.date_start_smoking"
                    v-model="form.comorbidities.date_start_smoking"
                    :error="$page.props.errors['comorbidities.date_start_smoking']"
                    :disabled="!form.comorbidities.smoking || form.comorbidities.smoking === 'never'"
                />
            </div>
            <FormInput
                label="other comorbidities"
                name="comorbidities.comorbidities_other"
                v-model="form.comorbidities.comorbidities_other"
                :error="$page.props.errors['comorbidities.comorbidities_other']"
            />
        </div>
    </Transition>
    <h2 class="form-label text-lg italic text-complement mt-4 md:mt-8 xl:mt-16 scroll-mt-16 md:scroll-mt-8">
        OPERATIVE DATA
    </h2>
    <hr class="my-4 border-b border-accent">
    <Transition name="slide-fade">
        <div v-if="form.donor_type === 'CD'">
            <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8">
                <FormDatetime
                    label="harvest start time"
                    name="datetime_harvest_start"
                    v-model="form.datetime_harvest_start"
                    :error="$page.props.errors.datetime_harvest_start"
                    mode="datetime"
                />
                <FormDatetime
                    label="harvest finish time"
                    name="datetime_harvest_finish"
                    v-model="form.datetime_harvest_finish"
                    :error="$page.props.errors.datetime_harvest_finish"
                    mode="datetime"
                />
            </div>
            <hr class="border border-dashed my-2 md:my-4 xl:my-8">
        </div>
    </Transition>
    <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8">
        <FormDatetime
            label="operation start time"
            name="datetime_operation_start"
            v-model="form.datetime_operation_start"
            mode="datetime"
            :error="$page.props.errors.datetime_operation_start"
        />
        <FormDatetime
            label="operation finish time"
            name="datetime_operation_finish"
            v-model="form.datetime_operation_finish"
            mode="datetime"
            :error="$page.props.errors.datetime_operation_finish"
        />
    </div>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8">
        <div>
            <label class="form-label">
                cold ischemia time (hr/min) :
            </label>
            <div class="flex space-x-2 md:space-x-4">
                <FormInput
                    name="cold_ischemic_time_hours"
                    v-model="form.cold_ischemic_time_hours"
                    :error="$page.props.errors.cold_ischemic_time_hours"
                    type="tel"
                    placeholder="hours"
                />
                <FormInput
                    name="cold_ischemic_time_minutes"
                    v-model="form.cold_ischemic_time_minutes"
                    :error="$page.props.errors.cold_ischemic_time_minutes"
                    type="tel"
                    placeholder="minutes"
                />
            </div>
        </div>
        <FormInput
            label="warm ischemia time (min)"
            name="warm_ischemic_time_minutes"
            v-model="form.warm_ischemic_time_minutes"
            :error="$page.props.errors.warm_ischemic_time_minutes"
            type="tel"
        />
        <FormInput
            label="anastomosis time (min)"
            name="anastomosis_time_minutes"
            v-model="form.anastomosis_time_minutes"
            :error="$page.props.errors.anastomosis_time_minutes"
            type="tel"
        />
    </div>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8">
        <FormDatetime
            v-for="(input, key) in configs.operative_data"
            :key="key"
            :name="input.name"
            :label="input.label"
            v-model="form[input.name]"
            :error="$page.props.errors[input.name]"
            mode="datetime"
        />
    </div>
    <h3
        id="operative_data_attachments"
        class="form-label mt-4 md:mt-8 xl:mt-16 scroll-mt-16 md:scroll-mt-8"
    >
        attachments :
    </h3>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <MultiImageUploader
        :service-endpoints="configs.routes.upload"
        :pathname="configs.attachment_upload_pathname"
        name="operative_data_attachments"
        v-model="form.operative_data_attachments"
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
                        name="delayed_graft_function_dialysis_mode"
                        v-model="form.delayed_graft_function_dialysis_mode"
                        :options="configs.dialysis_mode_options"
                        :error="$page.props.errors.delayed_graft_function_dialysis_mode"
                    />
                    <FormDatetime
                        label="date of dialysis start"
                        name="date_delayed_graft_function_dialysis_start"
                        v-model="form.date_delayed_graft_function_dialysis_start"
                        :error="$page.props.errors.date_delayed_graft_function_dialysis_start"
                    />
                    <div>
                        <label class="form-label">indication for dialysis</label>
                        <FormCheckbox
                            class="my-2 md:my-4 xl:my-8"
                            v-for="(field, key) in configs.dialysis_indication_fields"
                            :key="key"
                            :label="field.label"
                            :name="field.name"
                            v-model="form[field.name]"
                            :error="$page.props.errors[field.name]"
                        />
                        <FormInput
                            name="delayed_graft_function_dialysis_indication_other"
                            v-model="form.delayed_graft_function_dialysis_indication_other"
                            :error="$page.props.errors.delayed_graft_function_dialysis_indication_other"
                            placeholder="other indication"
                        />
                    </div>
                </div>
            </Transition>
            <Transition name="slide-fade">
                <FormCheckbox
                    v-if="form.graft_function === 'primary non-function'"
                    class="md:mt-8 lg:mt-10"
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
    <div
        v-for="(biopsy, key) in form.graft_biopsies"
        :key="key"
        class="my-2 md:my-4 space-y-2 md:space-y-4"
    >
        <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8">
            <div class="space-y-2 md:space-y-4">
                <FormDatetime
                    :label="`date of biopsy#${key+1}`"
                    :name="`graft_biopsies.${key}.date_biopsy`"
                    v-model="biopsy.date_biopsy"
                />
                <div>
                    <label class="form-label">result biopsy#{{ key+1 }} :</label>
                    <small
                        class="text-red-400 text-sm scroll-mt-16 md:scroll-mt-8"
                        :id="`graft_biopsies.${key}.result`"
                    >
                        {{ $page.props.errors[`graft_biopsies.${key}.result`] }}
                    </small>
                    <div class="mt-2 md:mt-4 grid grid-cols-2 gap-2 md:gap-4">
                        <FormCheckbox
                            v-for="(field, index) in configs.biopsy_result_fields"
                            :key="index"
                            :label="field.label"
                            :name="`graft_biopsies.${key}.result.${field.name}`"
                            v-model="biopsy[field.name]"
                        />
                    </div>
                    <FormInput
                        class="mt-2 md:mt-4"
                        :name="`graft_biopsies.${key}.result.other_result`"
                        v-model="biopsy.other_result"
                        placeholder="other result"
                    />
                </div>
            </div>
            <ImageUploader
                :label="`attachment biopsy#${key+1}`"
                :service-endpoints="configs.routes.upload"
                :pathname="configs.attachment_upload_pathname"
                v-model="biopsy.attachment"
            />
            <button
                class="block"
                @click="removeBiopsy(key)"
            >
                <IconTrashXMark
                    class="w-4 h-4 text-red-400"
                />
            </button>
        </div>
        <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    </div>
    <button
        @click="form.graft_biopsies.push(configs.graft_biopsy)"
    >
        <IconFileCirclePlus
            class="w-4 h-4 text-accent"
        />
    </button>
    <h2 class="form-label text-lg italic text-complement mt-4 md:mt-8 xl:mt-16 scroll-mt-16 md:scroll-mt-8">
        complications
    </h2>
    <hr class="my-4 border-b border-accent">
    <FormCheckbox
        label="None"
        name="complications.none"
        v-model="form.complications.none"
        :toggler="true"
    />
    <small class="text-red-400 text-sm">
        {{ $page.props.errors['complications.none'] }}
    </small>
    <Transition name="slide-fade">
        <div
            class="my-2 md:my-4 space-y-2 md:space-y-4"
            v-if="!form.complications.none"
        >
            <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8">
                <div>
                    <label class="form-label">infection :</label>
                    <FormCheckbox
                        v-for="(field, key) in configs.complication_infection_fields"
                        :key="key"
                        :label="field.label"
                        :name="`complications.${field.name}`"
                        v-model="form.complications[field.name]"
                    />
                    <FormInput
                        name="complications.infection_other"
                        v-model="form.complications.infection_other"
                        placeholder="other infection"
                    />
                </div>
                <div>
                    <label class="form-label">hematoma :</label>
                    <FormCheckbox
                        label="hematoma"
                        name="complications.hematoma"
                        v-model="form.complications.hematoma"
                    />
                    <Transition name="slide-fade">
                        <div
                            v-if="form.complications.hematoma"
                            class="border-l-2 border-accent ml-1 md:ml-2 pl-2 md:pl-4"
                        >
                            <FormCheckbox
                                label="blood transfusion"
                                name="complications.blood_transfusion"
                                v-model="form.complications.blood_transfusion"
                            />
                            <FormInput
                                label="blood transfusion (unit)"
                                name="complications.blood_transfusion_unit"
                                v-model="form.complications.blood_transfusion_unit"
                                :disabled="!form.complications.blood_transfusion"
                            />
                        </div>
                    </Transition>
                    <label class="form-label mt-2 md:mt-4 xl:mt-8">vascular :</label>
                    <FormCheckbox
                        v-for="(field, key) in configs.complication_vascular_fields"
                        :key="key"
                        :label="field.label"
                        :name="`complications.${field.name}`"
                        v-model="form.complications[field.name]"
                    />
                </div>
                <div>
                    <label class="form-label">urological complications :</label>
                    <FormCheckbox
                        v-for="(field, key) in configs.complication_urological_fields"
                        :key="key"
                        :label="field.label"
                        :name="`complications.${field.name}`"
                        v-model="form.complications[field.name]"
                    />
                </div>
                <div>
                    <label class="form-label">investigations :</label>
                    <FormCheckbox
                        v-for="(field, key) in configs.complication_investigation_fields"
                        :key="key"
                        :label="field.label"
                        :name="`complications.${field.name}`"
                        v-model="form.complications[field.name]"
                    />
                </div>
            </div>

            <h3
                id="complication_data_attachments"
                class="form-label mt-4 md:mt-8 xl:mt-16 scroll-mt-16 md:scroll-mt-8"
            >
                attachments :
            </h3>
            <hr class="border border-dashed my-2 md:my-4 xl:my-8">
            <MultiImageUploader
                :service-endpoints="configs.routes.upload"
                :pathname="configs.attachment_upload_pathname"
                name="complication.attachments"
                v-model="form.complications.attachments"
            />
        </div>
    </Transition>
    <h2 class="form-label text-lg italic text-complement mt-4 md:mt-8 xl:mt-16 scroll-mt-16 md:scroll-mt-8">
        note
    </h2>
    <hr class="my-4 border-b border-accent">
    <FormTextarea
        name="remarks"
        v-model="form.remarks"
    />

    <SpinnerButton
        :spin="form.processing"
        v-if="formConfigs.can.update"
        @click="complete"
        class="mt-4 md:mt-8 w-full btn-accent"
    >
        COMPLETE
    </SpinnerButton>

    <SpinnerButton
        v-if="formConfigs.can.destroy"
        :spin="form.processing"
        @click="handleButtonActionClicked('destroy-case')"
        class="mt-4 md:mt-8 w-full btn-danger"
    >
        DELETE
    </SpinnerButton>

    <FormSelectOther
        :placeholder="selectOther.placeholder"
        ref="selectOtherInput"
        @closed="selectOtherClosed"
    />

    <ConfirmFormComposable
        ref="confirmForm"
        @confirmed="(reason) => confirmed(reason, handleConfirmedAction)"
    />
</template>

<script setup>
import {useForm} from '@inertiajs/vue3';
import {defineAsyncComponent, nextTick, reactive, ref, watch} from 'vue';
import FormAutocomplete from '../../../Components/Controls/FormAutocomplete.vue';
import FormInput from '../../../Components/Controls/FormInput.vue';
import FormSelect from '../../../Components/Controls/FormSelect.vue';
import FormRadio from '../../../Components/Controls/FormRadio.vue';
import FormCheckbox from '../../../Components/Controls/FormCheckbox.vue';
import FormDatetime from '../../../Components/Controls/FormDatetime.vue';
import ImageUploader from '../../../Components/Controls/ImageUploader.vue';
import {useSelectOther} from '../../../functions/useSelectOther.js';
import FormTextarea from '../../../Components/Controls/FormTextarea.vue';
import MultiImageUploader from '../../../Components/Controls/MultiImageUploader.vue';
import IconTrashXMark from '../../../Components/Helpers/Icons/IconTrashXMark.vue';
import IconFileCirclePlus from '../../../Components/Helpers/Icons/IconFileCirclePlus.vue';
import {useFormAutosave} from '../../../functions/useFormAutosave.js';
import AlertMessage from '../../../Components/Helpers/AlertMessage.vue';
import SpinnerButton from '../../../Components/Controls/SpinnerButton.vue';
import {useConfirmForm} from '../../../functions/useConfirmForm.js';
import {useActionStore} from '../../../functions/useActionStore.js';
const FormSelectOther = defineAsyncComponent(() => import('../../../Components/Controls/FormSelectOther.vue'));
const ConfirmFormComposable = defineAsyncComponent(() => import('../../../Components/Forms/ConfirmFormComposable.vue'));

const props = defineProps({
    formData: {type: Object, required: true},
    formConfigs: {type: Object, required: true},
});

const form = useForm({...props.formData});
const configs = reactive({...props.formConfigs});

const {autosave} = useFormAutosave();
watch (
    () => form.data(),
    (value) => {
        autosave(value, configs.routes.update);
    },
    {deep: configs.can.update},
);

const { selectOtherInput, selectOther, selectOtherClosed } = useSelectOther();
const insuranceInput = ref(null);
if (form.insurance && !configs.insurances.includes(form.insurance)) {
    configs.insurances.push(form.insurance);
}
watch (
    () => form.insurance,
    (val) => {
        if (val !== 'other') {
            return;
        }

        selectOther.placeholder = 'Other scheme';
        selectOther.configs = configs.insurances;
        selectOther.input = insuranceInput.value;
        selectOtherInput.value.open();
    }
);
const causeOfEsrdInput = ref(null);
if (form.cause_of_esrd && !configs.cause_of_esrd_options.includes(form.cause_of_esrd)) {
    configs.cause_of_esrd_options.push(form.cause_of_esrd);
}
watch (
    () => form.cause_of_esrd,
    (val) => {
        if (val !== 'other') {
            return;
        }

        selectOther.placeholder = 'Other cause of ESRD';
        selectOther.configs = configs.cause_of_esrd_options;
        selectOther.input = causeOfEsrdInput.value;
        selectOtherInput.value.open();
    }
);

watch (
    () => form.recipient_is,
    () => {
        form.donor_is = null;
    },
);

watch (
    () => form.comorbidities,
    (value) => {
        Object.keys(value).map((key) => {
            if (key.includes('date_') || key === 'none' || key === 'comorbidities_other' || value[key] === true) {
                return;
            }

            if (key === 'on_HT_medication') {
                value.date_start_HT_medication = null;
                value.HT_medication = null;
            } else if (key === 'on_DM_medication') {
                value.date_start_DM_medication = null;
                value.DM_medication = null;
            } else if (key === 'on_allopurinol') {
                value.date_start_allopurinol = null;
            } else if (key === 'smoking') {
                value.date_start_smoking = null;
            } else {
                value[`date_${key}`] = null;
            }
        });
    },
    {deep: true},
);

function removeBiopsy(index) {
    let temp = [...form.graft_biopsies];
    temp.splice(index, 1);
    form.graft_biopsies = [];
    nextTick(() => {
        form.graft_biopsies = temp;
    });
}

const {confirmForm, openConfirmForm, confirmed} = useConfirmForm();
const {actionStore} = useActionStore();
let actionStoreName = null;
watch(
    () => actionStore.value,
    (value) => {
        switch (value.name) {
        case 'complete-case':
            complete();
            break;
        case 'addendum-case':
            addendum();
            break;
        case 'destroy-case':
        case 'cancel-case':
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
    case 'destroy-case':
        useForm({reason: reason}).delete(configs.routes.destroy);
        break;
    case 'cancel-case':
        cancel(reason);
        break;
    default :
        return;
    }
    actionStoreName = null;
};
function complete() {
    form.post(configs.routes.complete);
}

function addendum() {

}

function cancel(reason) {

}

function handleButtonActionClicked (actionName) {
    actionStoreName = actionName;
    openConfirmForm(props.formConfigs.actions.find(a => a.name === actionName).config);
}
</script>

<style scoped>

</style>
