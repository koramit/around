<script setup>

import {reactive, ref, watch} from 'vue';
import CreateForm from '../../../Partials/Clinics/PostKT/CreateForm.vue';
import {router, useForm} from '@inertiajs/vue3';
import NoteStatusBadge from '../../../Components/Helpers/NoteStatusBadge.vue';
import ActionColumn from '../../../Components/Controls/ActionColumn.vue';
import PaginationNav from '../../../Components/Helpers/PaginationNav.vue';
import SearchIndex from '../../../Components/Controls/SearchIndex.vue';
import FormSelect from '../../../Components/Controls/FormSelect.vue';
import UpdateByMonth from '../../../Partials/Clinics/PostKT/UpdateByMonth.vue';
import {useActionStore} from '../../../functions/useActionStore.js';

const props = defineProps({
    caseRecords: {type: Object, required: true},
    configs: {type: Object, required: true},
});

const searchInput = ref(null);
const searchForm = reactive({
    search: props.configs.filters.search,
    scope: props.configs.filters.scope,
    mo: props.configs.filters.mo,
});

const createFormRef = ref(null);
const updateFormRef = ref(null);

function createCase(data) {
    useForm({...data})
        .post(props.configs.routes.store);
}

const {actionStore} = useActionStore();
watch (
    () => actionStore.value,
    (value) => {
        switch (value.name) {
        case 'update-by-month':
            updateFormRef.value.open();
            break;
        default:
            return;
        }
    },
    {deep: true}
);
</script>

<template>
    <div>
        <div class="flex flex-col-reverse md:flex-row justify-between items-center mb-4">
            <SearchIndex
                class="lg:hidden"
                :scopes="configs.scopes"
                :form="searchForm"
                @search-changed="(val) => searchForm.search = val"
                @scope-changed="(val) => searchForm.scope = val"
                ref="searchInput"
            />
            <div class="hidden lg:flex lg:gap-x-2">
                <SearchIndex
                    :scopes="configs.scopes"
                    :form="searchForm"
                    @search-changed="(val) => searchForm.search = val"
                    @scope-changed="(val) => searchForm.scope = val"
                    ref="searchInput"
                />
                <FormSelect
                    class="hidden lg:block lg:w-auto"
                    v-model="searchForm.mo"
                    name="md"
                    :options="configs.month_options"
                    placeholder="Filter by Month"
                />
            </div>
            <button
                v-if="configs.can.create"
                class="btn btn-accent w-full mb-4 md:w-auto md:px-4 md:mb-0"
                @click="createFormRef.open()"
            >
                New Case
            </button>
        </div>

        <!-- table -->
        <div class="bg-white rounded shadow overflow-x-auto">
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
            :service-endpoints="configs.routes"
            ref="createFormRef"
            @confirmed="createCase"
        />

        <UpdateByMonth
            ref="updateFormRef"
            :url="configs.routes.month_cases"
            @done="router.reload()"
        />
    </div>
</template>

<style scoped>

</style>
