<template>
    <Teleport to="body">
        <ModalDialog
            ref="modal"
            width-mode="form-cols-1"
            @closed="resetAdmission"
        >
            <template #header>
                <div class="font-semibold text-complement">
                    {{ heading }}
                </div>
            </template>
            <template #body>
                <div class="py-4 my-2 md:py-6 md:my-4 border-t border-b border-accent-darker">
                    <FormInput
                        name="an"
                        :label="mode"
                        v-model="an"
                        pattern="\d*"
                        type="number"
                        ref="anInput"
                        :error="anError"
                        @keydown.enter="searchAdmission"
                    />
                    <SpinnerButton
                        :spin="busy"
                        class="btn-complement w-full mt-2"
                        @click="searchAdmission"
                        :disabled="!an.length"
                    >
                        SEARCH
                    </SpinnerButton>
                    <hr class="my-4 md:my-6">
                    <span class="form-label block">{{ dataLabel }}</span>
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
                </div>
            </template>
            <template #footer>
                <div class="flex justify-end items-center">
                    <button
                        class="btn btn-accent"
                        @click="confirm"
                        :disabled="!admission.hn"
                    >
                        {{ confirmLabel }}
                    </button>
                </div>
            </template>
        </ModalDialog>
    </Teleport>
</template>

<script setup>
import ModalDialog from '../Helpers/ModalDialog.vue';
import FormInput from '../Controls/FormInput.vue';
import SpinnerButton from '../Controls/SpinnerButton.vue';
import { computed, nextTick, reactive, ref } from 'vue';

const props = defineProps({
    heading: { type: String, default: 'Search Admission'},
    confirmLabel: { type: String, default: 'CONFIRM'},
    mode: { type: String, default: 'an' },
    serviceEndpoint: { type: String, required: true },
});

const emits = defineEmits(['confirmed']);

const modal = ref(null);
const anInput = ref(null);
const an = ref('');
const anError = ref('');
const busy = ref(false);
const admission = reactive({
    an: '',
    hn: '',
    name: '',
    gender: '',
    age: '',
    ward_admit: '',
    admitted_at: '',
    discharged_at: '',
});

const dataLabel = computed(() => {
    if (admission.admitted_at) {
        return  admission.discharged_at
            ? 'latest admission'
            : 'active admission';
    }

    if (admission.hn) {
        return 'patient data';
    }

    return null;
});

const searchAdmission = () => {
    busy.value = true;
    anError.value = '';
    admission.hn = '';

    window.axios
        .post(props.serviceEndpoint, {key: an.value})
        .then(response => {
            if (! response.data.hn) {
                anError.value = 'Patient not found';
                return;
            }

            if (! response.data.found || response.data.discharged_at) {
                anError.value = 'No active admission';
            }

            if (props.mode === 'hn') {
                admission.an = response.data.an;
                admission.location = response.data.location;
            }

            admission.hn = response.data.hn;
            admission.name = response.data.name;
            admission.gender = response.data.gender;
            admission.age = response.data.age;
            admission.ward_admit = response.data.ward_admit;
            admission.admitted_at = response.data.admitted_at;
            admission.discharged_at = response.data.discharged_at;
        }).catch(error => {
            console.log(error);
        }).finally(() => busy.value = false);
};

const resetAdmission = () => {
    an.value = '';
    anError.value = '';
    admission.hn = '';
};

const open = () => {
    modal.value.open();
    nextTick(() => anInput.value.focus());
};

const confirm = () => {
    modal.value.close();
    emits('confirmed', { hn: admission.hn, an: admission.an});
};

defineExpose({ open });
</script>