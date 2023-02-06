<template>
    <Teleport to="body">
        <ModalDialog
            ref="modal"
            width-mode="form-cols-1"
            @closed="Object.keys(form).map(k => form[k] = null)"
        >
            <template #header>
                <div class="font-semibold text-complement">
                    Search Admission
                </div>
            </template>
            <template #body>
                <div class="py-4 my-2 md:py-6 md:my-4 border-t border-b border-accent-darker">
                    <FormInput
                        label="an"
                        name="an"
                        v-model="form.an"
                        type="tel"
                        :error="errors.an"
                        ref="anInput"
                        @keydown.enter="searchAdmission"
                    />
                    <SpinnerButton
                        :spin="busy"
                        class="btn-complement w-full mt-2"
                        @click="searchAdmission"
                        :disabled="!form.an?.length"
                    >
                        SEARCH
                    </SpinnerButton>
                    <hr class="my-4 md:my-6">
                    <span class="form-label block">admission data</span>
                    <div
                        v-if="!admission.hn"
                        class="bg-white rounded shadow p-2 lg:p-4 text-sm"
                        :class="{ 'animate-pulse': busy }"
                    >
                        <div
                            class="mt-1"
                            v-for="key in Object.keys(admission)"
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
                            v-for="key in [...Object.keys(admission)].filter(k => admission[k])"
                            :key="key"
                        >
                            <span class="text-complement uppercase font-semibold">{{ key.replaceAll('_', ' ') }} : </span> {{ admission[key] }}
                        </p>
                    </div>
                    <transition name="slide-fade">
                        <div v-if="admission.hn">
                            <hr class="my-4 md:my-6">
                            <FormRadio
                                label="reason for admission"
                                v-model="form.reason_for_admission"
                                name="reason_for_admission"
                                :options="admitReasons"
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
                        :disabled="!admission.an || !form.reason_for_admission"
                    >
                        Confirm
                    </button>
                </div>
            </template>
        </ModalDialog>
    </Teleport>
</template>

<script setup>
import ModalDialog from '../../../Components/Helpers/ModalDialog.vue';
import {nextTick, reactive, ref} from 'vue';
import FormInput from '../../../Components/Controls/FormInput.vue';
import SpinnerButton from '../../../Components/Controls/SpinnerButton.vue';
import FormRadio from '../../../Components/Controls/FormRadio.vue';

const props = defineProps({
    serviceEndpoint: { type: String, required: true },
    admitReasons: { type: Array, default: () => ['KT', 'Complication'] },
});
const emits = defineEmits(['confirmed']);

const modal = ref(null);
const anInput = ref(null);
const busy = ref(false);
const form = reactive({
    an: null,
    hn: null,
    reason_for_admission: null,
});
const admission = reactive({
    an: null,
    hn: null,
    name: null,
    gender: null,
    age: null,
    ward_admit: null,
    admitted_at: null,
    discharged_at: null,
});
const errors = reactive({
    an: null,
});

function searchAdmission() {
    busy.value = true;
    errors.an = null;
    Object.keys(admission).map(k => admission[k] = null);

    window.axios
        .post(props.serviceEndpoint, {key: form.an})
        .then((response) => {
            if (! response.data.found) {
                errors.an = response.data.message;
                return;
            }

            Object.keys(admission).map(k => admission[k] = response.data[k]);
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
    Object.keys(admission).map(k => admission[k] = null);
    nextTick(() => anInput.value.focus());
}

function confirm() {
    form.an = admission.an;
    form.hn = admission.hn;
    emits('confirmed', {...form});
    modal.value.close();
}
defineExpose({ open });
</script>
