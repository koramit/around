<script setup>

import FormInput from '../../../Components/Controls/FormInput.vue';
import ModalDialog from '../../../Components/Helpers/ModalDialog.vue';
import SpinnerButton from '../../../Components/Controls/SpinnerButton.vue';
import {nextTick, reactive, ref} from 'vue';
import FormDatetime from '../../../Components/Controls/FormDatetime.vue';

const props = defineProps({
    serviceEndpoint: { type: String, required: true },
});
const emits = defineEmits(['confirmed']);

const modal = ref(null);
const anInput = ref(null);
const busy = ref(false);
const form = reactive({
    hn: null,
    date_transplant: null,
    case_no: null,
});
const patient = reactive({
    hn: null,
    name: null,
    gender: null,
    age: null,
});
const errors = reactive({
    hn: null,
});

function searchPatient() {
    busy.value = true;
    errors.hn = null;
    Object.keys(patient).map(k => patient[k] = null);

    window.axios
        .post(props.serviceEndpoint, {hn: form.hn})
        .then((response) => {
            if (! response.data.found) {
                errors.hn = response.data.message;
                return;
            }

            Object.keys(patient).map(k => patient[k] = response.data[k] ?? null);
            patient.hn = form.hn;
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
    Object.keys(patient).map(k => patient[k] = null);
    nextTick(() => anInput.value.focus());
}

function confirm() {
    form.hn = patient.hn;
    emits('confirmed', {...form});
    modal.value.close();
}
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
                        label="hn"
                        name="hn"
                        v-model="form.hn"
                        type="tel"
                        :error="errors.hn"
                        ref="anInput"
                        @keydown.enter="searchPatient"
                    />
                    <SpinnerButton
                        :spin="busy"
                        class="btn-complement w-full mt-2"
                        @click="searchPatient"
                        :disabled="!form.hn?.length"
                    >
                        SEARCH
                    </SpinnerButton>
                    <hr class="my-4 md:my-6">
                    <span class="form-label block">Patient data</span>
                    <div
                        v-if="!patient.hn"
                        class="bg-white rounded shadow p-2 lg:p-4 text-sm"
                        :class="{ 'animate-pulse': busy }"
                    >
                        <div
                            class="mt-1"
                            v-for="key in Object.keys(patient)"
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
                            v-for="key in [...Object.keys(patient)].filter(k => patient[k])"
                            :key="key"
                        >
                            <span class="text-complement uppercase font-semibold">{{ key.replaceAll('_', ' ') }} : </span> {{ patient[key] }}
                        </p>
                    </div>
                    <transition name="slide-fade">
                        <div v-if="patient.hn">
                            <hr class="my-4 md:my-6">
                            <FormDatetime
                                label="date transplant"
                                name="date_transplant"
                                v-model="form.date_transplant"
                            />
                            <FormInput
                                class="mt-4"
                                label="case no"
                                name="case_no"
                                v-model="form.case_no"
                                type="tel"
                            />
                        </div>
                    </transition>
                </div>
            </template>
            <template #footer>
                <div class="flex justify-end items-center">
                    <button
                        class="btn btn-accent"
                        @click="confirm"
                        :disabled="!patient.hn || !form.date_transplant || !form.case_no"
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
