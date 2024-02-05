<template>
    <h2
        class="form-label text-lg italic text-complement form-scroll-mt"
        id="admission-data"
    >
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
        <template v-if="form.reason_for_admission === 'kt'">
            <div class="space-y-2 md:space-y-4">
                <FormCheckbox
                    label="Retain Jackson drain"
                    name="retain_jackson_drain"
                    v-model="form.retain_jackson_drain"
                    :error="$page.props.errors.retain_jackson_drain"
                />
                <transition name="slide-fade">
                    <FormDatetime
                        v-if="!form.retain_jackson_drain"
                        name="date_off_drain"
                        label="date off drain"
                        v-model="form.date_off_drain"
                        :error="$page.props.errors.date_off_drain"
                    />
                </transition>
            </div>
            <div class="space-y-2 md:space-y-4">
                <FormCheckbox
                    label="Retain foley's catheter"
                    name="retain_foley_catheter"
                    v-model="form.retain_foley_catheter"
                    :error="$page.props.errors.retain_foley_catheter"
                />
                <transition name="slide-fade">
                    <FormDatetime
                        v-if="!form.retain_foley_catheter"
                        name="date_off_foley"
                        label="date off foley"
                        v-model="form.date_off_foley"
                        :error="$page.props.errors.date_off_foley"
                    />
                </transition>
            </div>
        </template>

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
        <FormInput
            label="tel no"
            name="tel_no"
            v-model="form.tel_no"
            :error="$page.props.errors.tel_no"
        />
        <FormInput
            label="alternative contact"
            name="alternative_contact"
            v-model="form.alternative_contact"
            :error="$page.props.errors.alternative_contact"
        />
        <div>
            <FormCheckbox
                label="Contact information confirmed"
                name="contact_info_confirmed"
                v-model="form.contact_info_confirmed"
            />
            <small
                class="form-error-block form-scroll-mt"
            >{{ $page.props.errors.contact_info_confirmed }}</small>
        </div>
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
    <template v-if="form.reason_for_admission === 'kt'">
        <h2
            class="form-label text-lg italic text-complement mt-4 md:mt-8 xl:mt-16 form-scroll-mt"
            id="clinical-data"
        >
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
                    :options="form.donor_type === 'CD' ? configs.donor_types : configs.donor_types.filter(type => type !== 'CD')"
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
                    :error="$page.props.errors['hla_mismatch_'+antigen.toLowerCase()]"
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
                :error="$page.props.errors[crossmatch.name+'_positive_specification']"
                :disabled="form[crossmatch.name] !== 'positive'"
            />
        </div>
        <h3
            id="clinical_data_attachments"
            class="form-label mt-4 md:mt-8 xl:mt-16 form-scroll-mt"
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
        <small class="form-error-block">{{ $page.props.errors.clinical_data_attachments }}</small>

        <h2
            class="form-label text-lg italic text-complement mt-4 md:mt-8 xl:mt-16 form-scroll-mt"
            id="comorbidities"
        >
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
            class="form-error-block"
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
                    <div
                        class="grid gap-2 md:gap-4 grid-cols-2 xl:gap-8 mb-2 md:mb-4 md:pb-4 md:border-b-2 border-dashed"
                    >
                        <FormCheckbox
                            :name="`comorbidities.${comorbidity.name}`"
                            :label="comorbidity.label"
                            v-model="form.comorbidities[comorbidity.name]"
                            @autosave="focusNextDatePicker(`comorbidities.date_${comorbidity.name}`, form.comorbidities[comorbidity.name])"
                        />
                        <FormDatetime
                            :name="`comorbidities.date_${comorbidity.name}`"
                            v-model="form.comorbidities[`date_${comorbidity.name}`]"
                            :error="$page.props.errors['comorbidities.date_'+comorbidity.name]"
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
                        @autosave="focusNextDatePicker('comorbidities.date_HT', form.comorbidities.HT)"
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
                        @autosave="focusNextDatePicker('comorbidities.date_start_HT_medication', form.comorbidities.on_HT_medication)"
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
                        @autosave="focusNextDatePicker('comorbidities.date_DM', form.comorbidities.DM)"
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
                        @autosave="focusNextDatePicker('comorbidities.date_start_DM_medication', form.comorbidities.on_DM_medication)"
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
                    <div
                        class="grid gap-2 md:gap-4 grid-cols-2 xl:gap-8 mb-2 md:mb-4 md:pb-4 md:border-b-2 border-dashed"
                    >
                        <FormCheckbox
                            :name="`comorbidities.${comorbidity.name}`"
                            :label="comorbidity.label"
                            v-model="form.comorbidities[comorbidity.name]"
                            @autosave="focusNextDatePicker(`comorbidities.date_${comorbidity.name}`, form.comorbidities[comorbidity.name])"
                        />
                        <FormDatetime
                            :name="`comorbidities.date_${comorbidity.name}`"
                            v-model="form.comorbidities[`date_${comorbidity.name}`]"
                            :error="$page.props.errors['comorbidities.date_'+comorbidity.name]"
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
                    <FormCheckbox
                        label="Smoker/Ex-Smoker"
                        name="comorbidities.smoking"
                        v-model="form.comorbidities.smoking"
                        @autosave="focusNextDatePicker('comorbidities.date_start_smoking', form.comorbidities.smoking)"
                    />
                    <div class="space-y-2 md:space-y-4">
                        <FormDatetime
                            placeholder="Date start smoking"
                            name="comorbidities.date_start_smoking"
                            v-model="form.comorbidities.date_start_smoking"
                            :error="$page.props.errors['comorbidities.date_start_smoking']"
                            :disabled="!form.comorbidities.smoking"
                        />
                        <FormRadio
                            name="comorbidities.smoking_type"
                            :options="configs.smoking_types"
                            v-model="form.comorbidities.smoking_type"
                            :disabled="!form.comorbidities.smoking"
                            :error="$page.props.errors['comorbidities.smoking_type']"
                        />
                    </div>
                </div>
                <FormInput
                    label="other comorbidities"
                    name="comorbidities.comorbidities_other"
                    v-model="form.comorbidities.comorbidities_other"
                    :error="$page.props.errors['comorbidities.comorbidities_other']"
                />
            </div>
        </Transition>
        <h2
            class="form-label text-lg italic text-complement mt-4 md:mt-8 xl:mt-16 form-scroll-mt"
            id="operative-data"
        >
            OPERATIVE DATA
        </h2>
        <hr class="my-4 border-b border-accent">
        <FormInput
            label="donor creatinine before harvest (mg/dl)"
            name="donor_creatinine_before_harvest"
            v-model="form.donor_creatinine_before_harvest"
            :error="$page.props.errors.donor_creatinine_before_harvest"
            type="tel"
        />
        <FormCheckbox
            class="mt-4"
            label="No immunosuppressive drugs induction"
            name="immunosuppressive_drugs_induction.none"
            v-model="form.immunosuppressive_drugs_induction.none"
            :toggler="true"
        />
        <small
            class="form-error-block"
            id="immunosuppressive_drugs_induction.none"
        >
            {{ $page.props.errors['immunosuppressive_drugs_induction.none'] }}
        </small>
        <Transition name="slide-fade">
            <div
                class="mt-2 md:mt-4"
                v-if="!form.immunosuppressive_drugs_induction.none"
            >
                <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8">
                    <FormCheckbox
                        label="Simulate"
                        name="immunosuppressive_drugs_induction.simulate"
                        v-model="form.immunosuppressive_drugs_induction.simulate"
                    />
                    <FormCheckbox
                        label="ATG"
                        name="immunosuppressive_drugs_induction.ATG"
                        v-model="form.immunosuppressive_drugs_induction.ATG"
                    />
                    <FormCheckbox
                        label="Rituximab"
                        name="immunosuppressive_drugs_induction.rituximab"
                        v-model="form.immunosuppressive_drugs_induction.rituximab"
                    />
                    <FormInput
                        placeholder="other induction"
                        name="immunosuppressive_drugs_induction.other"
                        v-model="form.immunosuppressive_drugs_induction.other"
                        :error="$page.props.errors['immunosuppressive_drugs_induction.other']"
                    />
                </div>
            </div>
        </Transition>
        <hr class="border border-dashed my-2 md:my-4 xl:my-8">
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
                    <FormInput
                        label="donor hospital"
                        name="donor_cd_hospital"
                        v-model="form.donor_cd_hospital"
                        :error="$page.props.errors.donor_cd_hospital"
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
            class="form-label mt-4 md:mt-8 xl:mt-16 form-scroll-mt"
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
        <small class="form-error-block">{{ $page.props.errors.operative_data_attachments }}</small>
        <h2
            class="form-label text-lg italic text-complement mt-4 md:mt-8 xl:mt-16 form-scroll-mt"
            id="outcomes"
        >
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
                            <small
                                class="form-error-block"
                                id="delayed_graft_function_dialysis_indication"
                            >
                                {{ $page.props.errors.delayed_graft_function_dialysis_indication }}
                            </small>
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
            <FormInput
                label="creatinine at discharge (mg/dl)"
                name="creatinine_at_discharge"
                v-model="form.creatinine_at_discharge"
                :error="$page.props.errors.creatinine_at_discharge"
                type="number"
            />
        </div>
        <h3 class="form-label mt-4 md:mt-8 xl:mt-16">
            graft biopsy :
        </h3>
        <hr class="border border-dashed my-2 md:my-4 xl:my-8">
        <GraftBiopsyForm
            :configs="{
                routes: configs.routes,
                attachment_upload_pathname: configs.attachment_upload_pathname,
                graft_biopsy: {...configs.graft_biopsy},
                graft_biopsy_fields: {...configs.graft_biopsy_fields},
                biopsy_result_fields: {...configs.biopsy_result_fields},
            }"
            v-model="form.graft_biopsies"
        />
        <h2
            class="form-label text-lg italic text-complement mt-4 md:mt-8 xl:mt-16 form-scroll-mt"
            id="complications"
        >
            complications
        </h2>
        <hr class="my-4 border-b border-accent">
        <FormCheckbox
            label="None"
            name="complications.none"
            v-model="form.complications.none"
            :toggler="true"
        />
        <small
            class="form-error-block"
            id="complications.none"
        >
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
                        <label class="form-label">hematologic complication :</label>
                        <FormCheckbox
                            label="Hematoma"
                            name="complications.hematoma"
                            v-model="form.complications.hematoma"
                        />
                        <Transition name="slide-fade">
                            <div
                                v-if="form.complications.hematoma"
                                class="border-l-2 border-accent ml-1 md:ml-2 pl-2 md:pl-4"
                            >
                                <FormCheckbox
                                    label="Blood transfusion"
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
                        <FormCheckbox
                            v-for="(field, key) in configs.complication_hematologic_fields"
                            :key="key"
                            :label="field.label"
                            :name="`complications.${field.name}`"
                            v-model="form.complications[field.name]"
                        />
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
                    class="form-label mt-4 md:mt-8 xl:mt-16 form-scroll-mt"
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
    </template>

    <template v-else>
        <h2
            class="form-label text-lg italic text-complement mt-4 md:mt-8 xl:mt-16 form-scroll-mt"
            id="indications"
        >
            Indication for Admission
        </h2>
        <hr class="my-4 border-b border-accent">
        <small
            id="complications"
            class="form-error-block form-scroll-mt"
        >{{ $page.props.errors.complications }}</small>
        <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8">
            <div>
                <FormCheckbox
                    label="AKI"
                    v-model="form.complications.AKI"
                />
                <FormCheckbox
                    label="Metabolic disturbance"
                    v-model="form.complications.metabolic_disturbance"
                />
                <Transition name="slide-fade">
                    <FormInput
                        v-if="form.complications.metabolic_disturbance"
                        class="mb-2 md:mb-2 border-l-2 border-accent ml-1 md:ml-2 pl-2 md:pl-4"
                        name="complications.metabolic_disturbance_specification"
                        placeholder="please specify"
                        v-model="form.complications.metabolic_disturbance_specification"
                        :error="$page.props.errors['complications.metabolic_disturbance_specification']"
                    />
                </Transition>
                <FormCheckbox
                    label="Treatment rejection"
                    v-model="form.complications.treatment_rejection"
                />
                <FormCheckbox
                    label="Desensitization protocol"
                    v-model="form.complications.desensitization_protocol"
                />
                <FormCheckbox
                    label="Surgical complication"
                    v-model="form.complications.surgical_complication"
                />
                <Transition name="slide-fade">
                    <FormInput
                        v-if="form.complications.surgical_complication"
                        class="mb-2 md:mb-2 border-l-2 border-accent ml-1 md:ml-2 pl-2 md:pl-4"
                        name="complications.surgical_complication_specification"
                        placeholder="please specify"
                        v-model="form.complications.surgical_complication_specification"
                        :error="$page.props.errors['complications.surgical_complication_specification']"
                    />
                </Transition>
            </div>
            <div>
                <label class="form-label mt-2 md:mt-0">Infection :</label>
                <FormCheckbox
                    v-for="(field, key) in configs.indication_for_admission_infection_fields"
                    :key="key"
                    :label="field.label"
                    v-model="form.complications[field.name]"
                />
                <FormInput
                    name="complications.infection_other"
                    label="other infection"
                    v-model="form.complications.infection_other"
                    :error="$page.props.errors['complications.infection_other']"
                />
            </div>
        </div>
        <FormInput
            class="mt-2 md:mt-4"
            name="complications.complications_other"
            v-model="form.complications.complications_other"
            :error="$page.props.errors['complications.complications_other']"
            placeholder="other indications"
        />
        <h3
            id="complication_data_attachments"
            class="form-label mt-4 md:mt-8 xl:mt-16 form-scroll-mt"
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

        <h2
            class="form-label text-lg italic text-complement mt-4 md:mt-8 xl:mt-16 form-scroll-mt"
            id="procedures"
        >
            PROCEDURES
        </h2>
        <hr class="my-4 border-b border-accent">
        <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8">
            <FormCheckbox
                label="Angioplasty"
                v-model="form.angioplasty"
            />
            <div>
                <FormCheckbox
                    label="Imaging"
                    v-model="form.complications.imaging"
                />
                <Transition name="slide-fade">
                    <FormInput
                        v-if="form.complications.imaging"
                        class="mb-2 md:mb-2 border-l-2 border-accent ml-1 md:ml-2 pl-2 md:pl-4"
                        name="complications.imaging_specification"
                        placeholder="please specify"
                        v-model="form.complications.imaging_specification"
                        :error="$page.props.errors['complications.imaging_specification']"
                    />
                </Transition>
            </div>
        </div>
        <h3 class="form-label mt-4 md:mt-8 xl:mt-16">
            graft biopsy :
        </h3>
        <hr class="border border-dashed my-2 md:my-4 xl:my-8">
        <GraftBiopsyForm
            :configs="{
                routes: configs.routes,
                attachment_upload_pathname: configs.attachment_upload_pathname,
                graft_biopsy: configs.graft_biopsy,
                graft_biopsy_fields: configs.graft_biopsy_fields,
                biopsy_result_fields: configs.biopsy_result_fields,
            }"
            v-model="form.graft_biopsies"
        />
        <h3
            id="procedure_data_attachments"
            class="form-label mt-4 md:mt-8 xl:mt-16 form-scroll-mt"
        >
            attachments :
        </h3>
        <hr class="border border-dashed my-2 md:my-4 xl:my-8">
        <MultiImageUploader
            :service-endpoints="configs.routes.upload"
            :pathname="configs.attachment_upload_pathname"
            name="procedure_data_attachments"
            v-model="form.procedure_data_attachments"
        />
        <h2
            class="form-label text-lg italic text-complement mt-4 md:mt-8 xl:mt-16 form-scroll-mt"
            id="diagnosis"
        >
            final diagnosis
        </h2>
        <hr class="my-4 border-b border-accent">
        <FormInput
            name="final_diagnosis"
            v-model="form.final_diagnosis"
            :error="$page.props.errors.final_diagnosis"
        />
    </template>

    <h3
        id="follow_ups"
        class="form-label mt-4 md:mt-8 xl:mt-16"
    >
        follow up :
    </h3>
    <hr class="border border-dashed my-2 md:my-4 xl:my-8">
    <FollowUpForm
        :configs="{follow_up: {...configs.follow_up}}"
        v-model="form.follow_ups"
    />

    <h2 class="form-label text-lg italic text-complement mt-4 md:mt-8 xl:mt-16 form-scroll-mt">
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
        :spin="form.processing"
        v-if="configs.can.off"
        @click="handleButtonActionClicked('off-case')"
        class="mt-4 md:mt-8 w-full btn-complement"
    >
        OFF CASE
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

    <SpinnerButton
        :spin="form.processing"
        v-if="configs.can.addendum"
        @click="addendum"
        class="mt-4 md:mt-8 w-full btn-complement"
    >
        ADDENDUM
    </SpinnerButton>

    <SpinnerButton
        v-if="configs.can.cancel"
        :spin="form.processing"
        @click="handleButtonActionClicked('cancel-case')"
        class="mt-4 md:mt-8 w-full btn-warning"
    >
        CANCEL
    </spinnerbutton>

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
import {useSelectOther} from '../../../functions/useSelectOther.js';
import FormTextarea from '../../../Components/Controls/FormTextarea.vue';
import MultiImageUploader from '../../../Components/Controls/MultiImageUploader.vue';
import {useFormAutosave} from '../../../functions/useFormAutosave.js';
import AlertMessage from '../../../Components/Helpers/AlertMessage.vue';
import SpinnerButton from '../../../Components/Controls/SpinnerButton.vue';
import {useConfirmForm} from '../../../functions/useConfirmForm.js';
import {useActionStore} from '../../../functions/useActionStore.js';
import FollowUpForm from '../../../Partials/Wards/KidneyTransplantAdmission/FollowUpForm.vue';
const GraftBiopsyForm = defineAsyncComponent(() => import('../../../Partials/Wards/KidneyTransplantAdmission/GraftBiopsyForm.vue'));
const FormSelectOther = defineAsyncComponent(() => import('../../../Components/Controls/FormSelectOther.vue'));
const ConfirmFormComposable = defineAsyncComponent(() => import('../../../Components/Forms/ConfirmFormComposable.vue'));

const props = defineProps({
    formData: {type: Object, required: true},
    formConfigs: {type: Object, required: true},
});

const form = useForm({...props.formData});
const configs = reactive({...props.formConfigs});

const {autosave} = useFormAutosave();
if (configs.can.update) {
    watch(
        () => form.data(),
        (value) => {
            autosave(value, configs.routes.update);
        },
        {deep: true},
    );
}

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
                value.smoking_type = null;
            } else {
                value[`date_${key}`] = null;
            }
        });
    },
    {deep: true},
);

watch (
    () => form.complications.hematoma,
    (value) => {
        if (value === false) {
            form.complications.blood_transfusion = false;
            form.complications.blood_transfusion_unit = null;
        }
    },
);

watch (
    () => form.complications.blood_transfusion,
    (value) => {
        if (value === false) {
            form.complications.blood_transfusion_unit = null;
        } else if (value === true) {
            nextTick(() => document.getElementById('complications.blood_transfusion_unit').focus());
        }
    },
);

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
        case 'off-case':
            actionStoreName = value.name;
            console.log(value.config);
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
        useForm({reason: reason}).delete(configs.routes.cancel);
        break;
    case 'off-case':
        useForm({reason: reason}).delete(configs.routes.off);
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
    form.put(configs.routes.addendum);
}

function handleButtonActionClicked (actionName) {
    actionStoreName = actionName;
    openConfirmForm(props.formConfigs.actions.find(a => a.name === actionName).config);
}

function focusNextDatePicker(activeElementId, go) {
    let nextElement = document.getElementById(activeElementId).querySelector('input');
    if (go && nextElement) {
        nextTick(() => nextElement._flatpickr._input.focus());
    }
}
</script>

<style scoped>

</style>
