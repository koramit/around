<script setup>

import ModalDialog from '../../../Components/Helpers/ModalDialog.vue';
import FormSelect from '../../../Components/Controls/FormSelect.vue';
import {ref, watch} from 'vue';
import SpinnerButton from '../../../Components/Controls/SpinnerButton.vue';

const props = defineProps({
    url: {type: String, required: true},
});

const modal = ref(null);
const updatingCount = ref(0);
const doneCount = ref(0);
const selectedMonth = ref(null);
const monthCases = ref([]);
const emits = defineEmits(['done']);

watch (
    () => selectedMonth.value,
    (val) => {
        if (!val) {
            monthCases.value = [];
            updatingCount.value = 0;
            doneCount.value = 0;
            return;
        }
        const endpoint = props.url.replace('month', val);
        window.axios
            .get(endpoint)
            .then(res => {
                monthCases.value = [...res.data];
            }).catch(err => {
                console.log(err);
            });
    },
    {immediate: true}
);

function updateByMonth() {
    if (monthCases.value.length === doneCount.value) {
        modal.value.close();
        emits('done');
    }
    monthCases.value.forEach((caseRecord) => {
        console.log(caseRecord.url);
        updatingCount.value++;
        window.axios
            .post(caseRecord.url)
            .then(res => {
                console.log(res.data);
            }).finally(() => {
                doneCount.value++;
            });
    });
}

function open() {
    modal.value.open();
    selectedMonth.value = null;
}

defineExpose({ open });
</script>

<template>
    <Teleport to="body">
        <ModalDialog
            ref="modal"
            width-mode="form-cols-1"
        >
            <template #header>
                <div class="font-semibold text-complement">
                    Update all cases by month
                </div>
            </template>
            <template #body>
                <div class="py-4 my-2 md:py-6 md:my-4 border-t border-b border-accent-darker">
                    <FormSelect
                        :disabled="doneCount && doneCount !== updatingCount"
                        v-model="selectedMonth"
                        name="month"
                        :options="['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']"
                    />
                    <div class="flex justify-between">
                        <p
                            v-if="monthCases.length"
                            class="mt-2 pl-1 font-semibold italic text-accent"
                        >
                            {{ monthCases.length }} active cases
                        </p>
                        <p
                            v-if="updatingCount"
                            class="mt-2 pl-1 font-semibold italic text-accent"
                        >
                            {{ doneCount }} cases done.
                        </p>
                    </div>
                </div>
            </template>
            <template #footer>
                <div class="flex justify-end items-center">
                    <SpinnerButton
                        class="btn btn-accent w-32"
                        :spin="updatingCount > 0 && updatingCount !== doneCount"
                        :disabled="monthCases.length === 0"
                        @click="updateByMonth"
                    >
                        {{ (updatingCount > 0 && updatingCount === doneCount) ? 'OK' : 'UPDATE' }}
                    </SpinnerButton>
                </div>
            </template>
        </ModalDialog>
    </Teleport>
</template>

<style scoped>

</style>
