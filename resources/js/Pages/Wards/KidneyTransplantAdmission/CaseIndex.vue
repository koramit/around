<template>
    <!-- search tool & create case -->
    <div class="flex flex-col-reverse md:flex-row justify-between items-center mb-4">
        <SearchIndex
            :scopes="configs.scopes"
            :form="searchForm"
            @search-changed="(val) => searchForm.search = val"
            @scope-changed="(val) => searchForm.scope = val"
            ref="searchInput"
        />
        <button
            v-if="can.create"
            class="btn btn-accent w-full mb-4 md:w-auto md:px-4 md:mb-0"
            @click="$refs.createFormRef.open()"
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
                        v-for="column in ['AN', 'Patient', 'Reason', 'Admitted On', 'Status', 'Author']"
                        :key="column"
                        v-text="column"
                        :colspan="column === 'Author' ? 2:1"
                    />
                </tr>
            </thead>
            <tr
                class="focus-within:bg-primary-darker"
                v-for="(caseRecord, key) in caseRecords.data"
                :key="key"
            >
                <template
                    v-for="field in ['an', 'patient', 'reason', 'admitted_at', 'status', 'author']"
                    :key="field"
                >
                    <td
                        v-if="field === 'status'"
                        class="px-6 py-4 border-t"
                    >
                        <NoteStatusBadge :status="caseRecord.status" />
                    </td>
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
                            @action-clicked="handleActionClicked"
                        />
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!--card -->
    <div class="md:hidden">
        <div
            class="bg-white rounded shadow my-4 p-4"
            v-for="(caseRecord, key) in caseRecords.data"
            :key="key"
        >
            <div class="flex justify-between items-center my-2 px-2">
                <div>
                    {{ caseRecord.patient }}
                </div>
                <DropdownList v-if="caseRecord.actions.length > 1">
                    <template #default>
                        <div class="bg-primary-darker p-2 rounded-full">
                            <IconDoubleDown class="w-4 h-4 text-accent" />
                        </div>
                    </template>
                    <template #dropdown>
                        <ActionDropdown
                            :actions="caseRecord.actions"
                            @action-clicked="handleActionClicked"
                        />
                    </template>
                </DropdownList>
                <ActionColumn
                    v-else-if="caseRecord.actions.length === 1"
                    :actions="caseRecord.actions"
                    @action-clicked="handleActionClicked"
                />
            </div>
            <div class="my-2 p-2 bg-gray-100 rounded space-y-2">
                <div class="flex items-center justify-between">
                    <NoteStatusBadge :status="caseRecord.status" />
                    <p class="font-semibold text-complement text-xs flex items-center">
                        <span class="block py-2 px-1 italic truncate">{{ caseRecord.author }}</span>
                    </p>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <p>
                        AN : <span
                            class="text-complement font-semibold"
                            v-text="caseRecord.an"
                        />
                    </p><p>
                        Reason: <span
                            class="
                            text-complement
                            font-semibold
                            px-1"
                            v-text="caseRecord.reason"
                        />
                    </p>
                    <p>
                        On : <span
                            class="text-complement font-semibold"
                            v-text="caseRecord.admitted_at"
                        />
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- pagination -->
    <PaginationNav :links="caseRecords.links" />

    <CreateForm
        :service-endpoint="routes.admissionsShow"
        :admit-reasons="configs.admit_reasons"
        ref="createFormRef"
        @confirmed="createForm"
    />

    <ConfirmFormComposable
        ref="confirmForm"
        @confirmed="(reason) => confirmed(reason, callAction)"
    />
</template>

<script setup>
import {defineAsyncComponent, onMounted, reactive, ref} from 'vue';
import SearchIndex from '../../../Components/Controls/SearchIndex.vue';
import {useForm} from '@inertiajs/vue3';
import NoteStatusBadge from '../../../Components/Helpers/NoteStatusBadge.vue';
import ActionColumn from '../../../Components/Controls/ActionColumn.vue';
import {useConfirmForm} from '../../../functions/useConfirmForm.js';
import PaginationNav from '../../../Components/Helpers/PaginationNav.vue';
import ConfirmFormComposable from '../../../Components/Forms/ConfirmFormComposable.vue';
import DropdownList from '../../../Components/Helpers/DropdownList.vue';
import IconDoubleDown from '../../../Components/Helpers/Icons/IconDoubleDown.vue';
import ActionDropdown from '../../../Components/Controls/ActionDropdown.vue';
const CreateForm = defineAsyncComponent(() => import('../../../Partials/Wards/KidneyTransplantAdmission/CreateForm.vue'));

const props = defineProps({
    caseRecords: {type: Object, required: true},
    configs: {type: Object, required: true},
    filters: {type: Object, required: true},
    routes: {type: Object, required: true},
    can: {type: Object, required: true},
});

const createFormRef = ref(null);
const searchForm = reactive({
    search: props.filters.search,
    scope: props.filters.scope,
});
const searchInput = ref(null);

function createForm(form) {
    useForm(form).post(props.routes.store);
}

let action = null;
const handleActionClicked = (selectedAction) => {
    action = {...selectedAction};
    switch (action.name) {
    case 'destroy-case':
    case 'cancel-case':
        openConfirmForm(action.config);
        break;
    default:
        break;
    }
};

const callAction = (reason) => {
    switch (action.name) {
    case 'destroy-case':
    case 'cancel-case':
        useForm({
            reason: reason,
        }).delete(action.route, {
            onFinish: () => action = null,
        });
        break;
    default:
        break;
    }
};

const {confirmForm, openConfirmForm, confirmed} = useConfirmForm();
onMounted(() => searchInput.value.focus());

</script>

