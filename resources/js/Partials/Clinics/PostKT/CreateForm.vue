<script setup>

import FormInput from '../../../Components/Controls/FormInput.vue';
import ModalDialog from '../../../Components/Helpers/ModalDialog.vue';
import SpinnerButton from '../../../Components/Controls/SpinnerButton.vue';
import {computed, nextTick, reactive, ref} from 'vue';
import FormDatetime from '../../../Components/Controls/FormDatetime.vue';
import FormRadio from '../../../Components/Controls/FormRadio.vue';
import FormAutocomplete from '../../../Components/Controls/FormAutocomplete.vue';
import FormSelect from '../../../Components/Controls/FormSelect.vue';

const props = defineProps({
    serviceEndpoints: { type: Object, required: true },
});
const emits = defineEmits(['confirmed']);

const modal = ref(null);
const anInput = ref(null);
const busy = ref(false);
const form = reactive({
    hn: null,
    date_transplant: null,
    case_no: null,
    donor_type: null,
    donor_hn: null,
    donor_name: null,
    donor_hospital: null,
    donor_redcross_id: null,
});
const recipient = reactive({
    hn: null,
    name: null,
    gender: null,
    age: null,
});
const errors = reactive({
    hn: null,
});

function searchPatient(isDonor = false) {
    busy.value = true;
    errors.hn = null;
    if (!isDonor) {
        Object.keys(recipient).map(k => recipient[k] = null);
    }

    window.axios
        .post(props.serviceEndpoints.patients_show, {hn: isDonor ? form.donor_hn : form.hn})
        .then((response) => {
            if (! response.data.found) {
                errors.hn = response.data.message;
                return;
            }

            if (isDonor) {
                form.donor_name = response.data.name;
                return;
            }

            Object.keys(recipient).map(k => recipient[k] = response.data[k] ?? null);
            recipient.hn = form.hn;
        })
        .catch((error) => {
            console.log(error);
        })
        .finally(() => {
            busy.value = false;
        });
}

function open() {
    modal.value.open();
    Object.keys(recipient).map(k => recipient[k] = null);
    nextTick(() => anInput.value.focus());
}

function confirm() {
    form.hn = recipient.hn;
    emits('confirmed', {...form});
    modal.value.close();
}

const canConfirm = computed(() => {
    if (!form.hn || !form.date_transplant || !form.case_no || !form.donor_type) {
        return false;
    }

    if (form.donor_type?.startsWith('LD')) {
        return form.donor_hn && form.donor_name;
    } else {
        return form.donor_redcross_id && form.donor_hospital;
    }
});

defineExpose({ open });
</script>

<template>
    <Teleport to="body">
        <ModalDialog
            ref="modal"
            width-mode="form-cols-1"
            @closed="Object.keys(form).map(k => form[k] = null)"
        >
            <template #header>
                <div class="font-semibold text-complement">
                    Search HN
                </div>
            </template>
            <template #body>
                <div class="py-4 my-2 md:py-6 md:my-4 border-t border-b border-accent-darker">
                    <FormInput
                        label="recipient hn"
                        name="hn"
                        v-model="form.hn"
                        type="tel"
                        :error="errors.hn"
                        ref="anInput"
                        @keydown.enter="searchPatient()"
                    />
                    <SpinnerButton
                        :spin="busy"
                        class="btn-complement w-full mt-2"
                        @click="searchPatient()"
                        :disabled="!form.hn?.length"
                    >
                        SEARCH
                    </SpinnerButton>
                    <hr class="my-4 md:my-6">
                    <span class="form-label block">recipient data</span>
                    <div
                        v-if="!recipient.hn"
                        class="bg-white rounded shadow p-2 lg:p-4 text-sm"
                        :class="{ 'animate-pulse': busy }"
                    >
                        <div
                            class="mt-1"
                            v-for="key in Object.keys(recipient)"
                            :key="key"
                        >
                            <span class="bg-gray-100 text-gray-100 whitespace-nowrap">
                                {{ key }} placeholder
                            </span>
                        </div>
                    </div>
                    <div
                        v-else
                        class="bg-white rounded shadow p-2 lg:p-4 text-sm"
                    >
                        <p
                            class="mt-1 whitespace-nowrap"
                            v-for="key in [...Object.keys(recipient)].filter(k => recipient[k])"
                            :key="key"
                        >
                            <span class="text-complement uppercase font-semibold">{{ key.replaceAll('_', ' ') }} : </span> {{ recipient[key] }}
                        </p>
                    </div>
                    <transition name="slide-fade">
                        <div v-if="recipient.hn">
                            <hr class="my-4 md:my-6">
                            <div class="grid grid-cols-2 gap-4">
                                <FormDatetime
                                    label="date transplant"
                                    name="date_transplant"
                                    v-model="form.date_transplant"
                                />
                                <FormInput
                                    label="case no"
                                    name="case_no"
                                    v-model="form.case_no"
                                    type="tel"
                                />
                            </div>
                            <hr class="my-4 md:my-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <FormRadio
                                    label="kidney donor type"
                                    name="donor_type"
                                    v-model="form.donor_type"
                                    :options="['CD single kidney', 'CD dual kidneys', 'LD']"
                                />
                                <div
                                    v-if="form.donor_type?.startsWith('LD')"
                                    class="space-y-2"
                                >
                                    <FormInput
                                        label="Donor HN"
                                        name="donor_hn"
                                        v-model="form.donor_hn"
                                        type="tel"
                                        @autosave="searchPatient(true)"
                                    />
                                    <FormInput
                                        name="donor_name"
                                        v-model="form.donor_name"
                                        readonly
                                    />
                                </div>
                                <div
                                    v-else-if="form.donor_type?.startsWith('CD')"
                                    class="space-y-2"
                                >
                                    <FormInput
                                        label="donor redcross id"
                                        name="donor_redcross_id"
                                        v-model="form.donor_redcross_id"
                                    />
                                    <FormAutocomplete
                                        placeholder="donor hospital"
                                        name="donor_hospital"
                                        v-model="form.donor_hospital"
                                        :endpoint="serviceEndpoints.hospitals"
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
                        :disabled="!canConfirm"
                    >
                        Confirm
                    </button>
                </div>
            </template>
        </ModalDialog>
    </Teleport>
</template>

<style scoped>

</style>
