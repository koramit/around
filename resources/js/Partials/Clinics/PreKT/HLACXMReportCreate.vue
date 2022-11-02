<template>
    <Teleport to="body">
        <ModalDialog
            ref="modal"
            width-mode="form-cols-1"
            @closed="Object.keys(form).map(k => form[k] = null)"
        >
            <template #header>
                <div class="font-semibold text-complement">
                    HLA typing for KT Report
                </div>
            </template>
            <template #body>
                <div class="py-4 my-2 md:py-6 md:my-4 border-t border-b border-accent-darker">
                    <FormRadio
                        name="patient_type"
                        label="patient type"
                        v-model="form.patient_type"
                        :options="['Patient', 'Recipient with LD']"
                        @autosave="focusHnInput"
                    />
                    <transition name="slide-fade">
                        <div
                            v-if="form.patient_type"
                            class="space-y-2"
                        >
                            <div
                                v-if="form.patient_type === 'Patient'"
                                class="space-y-2"
                            >
                                <FormInput
                                    label="patient hn"
                                    name="hn"
                                    v-model="form.hn"
                                    type="tel"
                                    :error="form.patient_error"
                                    ref="hnInput"
                                    @autosave="searchHn('recipient')"
                                />
                                <FormInput
                                    label="patient name"
                                    name="patient_name"
                                    v-model="form.patient_name"
                                    readonly
                                />
                            </div>
                            <div
                                v-else-if="form.patient_type === 'Recipient with LD'"
                                class="space-y-2"
                            >
                                <FormInput
                                    label="recipient hn"
                                    name="hn"
                                    v-model="form.hn"
                                    type="tel"
                                    :error="form.patient_error"
                                    ref="hnInput"
                                    @autosave="searchHn('recipient')"
                                />
                                <FormInput
                                    label="recipient name"
                                    name="patient_name"
                                    v-model="form.patient_name"
                                    readonly
                                />
                                <FormInput
                                    label="donor hn"
                                    name="donor_hn"
                                    v-model="form.donor_hn"
                                    type="tel"
                                    :error="form.donor_error"
                                    @autosave="searchHn('donor')"
                                />
                                <FormInput
                                    label="donor name"
                                    name="donor_name"
                                    v-model="form.donor_name"
                                    readonly
                                />
                            </div>
                            <FormDatetime
                                label="collection date"
                                name="date_serum"
                                v-model="form.date_serum"
                            />
                            <div>
                                <label class="form-label">Cause of investigation</label>
                                <div class="grid grid-cols-2 gap-x-2 md:gap-x-4">
                                    <FormCheckbox
                                        label="HLA typing"
                                        name="request_hla"
                                        v-model="form.request_hla"
                                    />
                                    <FormCheckbox
                                        label="Crossmatch"
                                        name="request_cxm"
                                        v-model="form.request_cxm"
                                    />
                                </div>
                            </div>
                        </div>
                    </transition>
                </div>
            </template>
            <template #footer>
                <div class="flex justify-end items-center">
                    <button
                        class="btn btn-accent"
                        @click="confirm"
                        :disabled="invalidForm"
                    >
                        <label class="px-4">Confirm</label>
                    </button>
                </div>
            </template>
        </ModalDialog>
    </Teleport>
</template>

<script setup>
import {computed, nextTick, reactive, ref} from 'vue';
import ModalDialog from '../../../Components/Helpers/ModalDialog.vue';
import FormInput from '../../../Components/Controls/FormInput.vue';
import FormDatetime from '../../../Components/Controls/FormDatetime.vue';
import FormRadio from '../../../Components/Controls/FormRadio.vue';
import FormCheckbox from '../../../Components/Controls/FormCheckbox.vue';
const props = defineProps({
    serviceEndpoint: { type: String, required: true },
});
const emits = defineEmits(['confirmed']);
const form = reactive({
    patient_type: null,
    hn: null,
    patient_name: null,
    patient_error: null,
    date_serum: null,
    donor_hn: null,
    donor_name: null,
    donor_error: null,
    request_hla: false,
    request_cxm: false,
});

const modal = ref(null);
const hnInput = ref(null);
const searchHn = (patient) => {
    let hn = patient === 'recipient' ? form.hn : form.donor_hn;
    form.patient_name = null;
    form.patient_error = null;
    form.donor_name = null;
    form.donor_error = null;
    window.axios
        .post(props.serviceEndpoint, {hn: hn})
        .then(res => {
            if (res.data.found) {
                if (patient === 'recipient') {
                    form.patient_name = res.data.name;
                } else {
                    form.donor_name = res.data.name;
                }
            } else {
                if (patient === 'recipient') {
                    form.patient_error = res.data.message;
                } else {
                    form.donor_error = res.data.message;
                }
            }
        }).catch(error => console.log(error));
};

const open = () => {
    modal.value.open();
};
const focusHnInput = () => {
    nextTick(() => hnInput.value.focus());
};
const invalidForm = computed(() => {
    if (! form.patient_type) {
        return true;
    }
    if (form.patient_type === 'Patient') {
        return !form.patient_name || !form.date_serum || (!form.request_cxm && !form.request_hla);
    }

    return !form.patient_name
        || !form.date_serum
        || (!form.request_cxm && !form.request_hla)
        || !form.donor_name;
});
const confirm = () => {
    modal.value.close();
    emits('confirmed', {...form});
};
defineExpose({ open });
</script>

<style scoped>

</style>
