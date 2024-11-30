<script setup>

import {ref} from 'vue';
import CreateForm from '../../../Partials/Clinics/PostKT/CreateForm.vue';
import {useForm} from '@inertiajs/vue3';
import NoteStatusBadge from '../../../Components/Helpers/NoteStatusBadge.vue';
import ActionColumn from '../../../Components/Controls/ActionColumn.vue';
import PaginationNav from '../../../Components/Helpers/PaginationNav.vue';

const props = defineProps({
    caseRecords: {type: Object, required: true},
    configs: {type: Object, required: true},
});

const createFormRef = ref(null);

function createCase(data) {
    useForm({...data})
        .post(props.configs.routes.store);
}
</script>

<template>
    <div>
        <div class="flex flex-col-reverse md:flex-row justify-between items-center mb-4">
            <button
                v-if="configs.can.create"
                class="btn btn-accent w-full mb-4 md:w-auto md:px-4 md:mb-0"
                @click="createFormRef.open()"
            >
                New Case
            </button>
        </div>

        <!-- table -->
        <div class="bg-white rounded shadow overflow-x-auto hidden md:block">
            <table class="w-full whitespace-nowrap">
                <thead>
                    <tr class="text-left font-semibold text-complement">
                        <th
                            class="px-6 pt-6 pb-4"
                            v-for="column in ['KT-NO', 'Date Tx', 'Patient', 'Status', 'Annual Cr', 'Last Update']"
                            :key="column"
                            v-text="column"
                            :colspan="column === 'Last Update' ? 2:1"
                        />
                    </tr>
                </thead>
                <tbody>
                    <tr
                        class="focus-within:bg-primary-darker"
                        v-for="(caseRecord, key) in caseRecords.data"
                        :key="key"
                    >
                        <template
                            v-for="field in ['kt_no', 'date_transplant', 'patient', 'status', 'annual_cr', 'last_update']"
                            :key="field"
                        >
                            <td
                                v-if="field === 'status'"
                                class="px-6 py-4 border-t"
                            >
                                <NoteStatusBadge :status="caseRecord.status" />
                            </td>
                            <td
                                v-else-if="field === 'annual_cr'"
                                :class="[
                                    'px-6 py-4 border-t font-medium',
                                    (caseRecord.annual_cr && parseFloat(caseRecord.annual_cr)) <= 4.0 ? 'text-green-500' : 'text-red-500'
                                ]"
                                v-text="caseRecord.annual_cr"
                            />
                            <td
                                v-else
                                class="px-6 py-4 border-t"
                                v-text="caseRecord[field]"
                            />
                        </template>
                        <td class="border-t">
                            <div class="flex justify-between items-center">
                                <ActionColumn
                                    :actions="caseRecord.actions"
                                />
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- pagination -->
        <PaginationNav :links="caseRecords.links" />

        <CreateForm
            :service-endpoint="configs.routes.admissionsShow"
            ref="createFormRef"
            @confirmed="createCase"
        />
    </div>
</template>

<style scoped>

</style>
