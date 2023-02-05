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
            @click="$refs.createForm.open()"
        >
            New Report
        </button>
    </div>

    <!-- table -->
    <div class="bg-white rounded shadow overflow-x-auto hidden md:block">
        <table class="w-full whitespace-nowrap">
            <tr class="text-left font-semibold text-complement">
                <th
                    class="px-6 pt-6 pb-4"
                    v-for="column in ['HN', 'Patient', 'Request', 'On', 'Status', 'Author']"
                    :key="column"
                    v-text="column"
                    :colspan="column === 'Author' ? 2:1"
                />
            </tr>
            <tr
                class="focus-within:bg-primary-darker"
                v-for="(report, key) in reports.data"
                :key="key"
            >
                <template
                    v-for="field in ['hn', 'patient_name', 'request', 'date_serum', 'status', 'author']"
                    :key="field"
                >
                    <td
                        v-if="field === 'status'"
                        class="px-6 py-4 border-t"
                    >
                        <NoteStatusBadge :status="report.status" />
                    </td>
                    <td
                        v-else
                        class="px-6 py-4 border-t"
                        v-text="report[field]"
                    />
                </template>
                <td class="border-t">
                    <div class="flex justify-between items-center">
                        <ActionColumn
                            :actions="report.actions"
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
            v-for="(report, key) in reports.data"
            :key="key"
        >
            <div class="flex justify-between items-center my-2 px-2">
                <div>
                    HN: {{ report.hn }} {{ report.patient_name }}
                </div>
                <DropdownList v-if="report.actions.length > 1">
                    <template #default>
                        <div class="bg-primary-darker p-2 rounded-full">
                            <IconDoubleDown class="w-4 h-4 text-accent" />
                        </div>
                    </template>
                    <template #dropdown>
                        <ActionDropdown
                            :actions="report.actions"
                            @action-clicked="handleActionClicked"
                        />
                    </template>
                </DropdownList>
                <ActionColumn
                    v-else-if="report.actions.length === 1"
                    :actions="report.actions"
                    @action-clicked="handleActionClicked"
                />
            </div>
            <div class="my-2 p-2 bg-gray-100 rounded space-y-2">
                <div class="flex items-center justify-between">
                    <NoteStatusBadge :status="report.status" />
                    <p class="font-semibold text-complement text-xs flex items-center">
                        <!--<IconUserMd class="h-3 w-3 mr-1" />-->
                        <span class="block py-2 px-1 italic truncate">{{ report.author }}</span>
                    </p>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <p>
                        Serum : <span
                            class="text-complement font-semibold"
                            v-text="report.date_serum"
                        />
                    </p>
                    <p>
                        Request: <span
                            class="text-complement font-semibold px-1"
                            v-text="report.request"
                        />
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- pagination -->
    <PaginationNav :links="reports.links" />

    <CreateForm
        :service-endpoint="routes.patientsShow"
        @confirmed="createReport"
        ref="createForm"
    />

    <ConfirmFormComposable
        ref="confirmForm"
        @confirmed="(reason) => confirmed(reason, callAction)"
    />
</template>

<script setup>
import {onMounted, reactive, ref} from 'vue';
import {useForm} from '@inertiajs/vue3';
import {useConfirmForm} from '../../../functions/useConfirmForm.js';
import ActionColumn from '../../../Components/Controls/ActionColumn.vue';
import ConfirmFormComposable from '../../../Components/Forms/ConfirmFormComposable.vue';
import CreateForm from '../../../Partials/Labs/KidneyTransplantHLATyping/CreateForm.vue';
import PaginationNav from '../../../Components/Helpers/PaginationNav.vue';
import SearchIndex from '../../../Components/Controls/SearchIndex.vue';
import DropdownList from '../../../Components/Helpers/DropdownList.vue';
import ActionDropdown from '../../../Components/Controls/ActionDropdown.vue';
import IconDoubleDown from '../../../Components/Helpers/Icons/IconDoubleDown.vue';
import NoteStatusBadge from '../../../Components/Helpers/NoteStatusBadge.vue';

const props = defineProps({
    reports: {type: Object, required: true},
    configs: {type: Object, required: true},
    filters: {type: Object, required: true},
    routes: {type: Object, required: true},
    can: {type: Object, required: true},
});

const searchForm = reactive({
    search: props.filters.search,
    scope: props.filters.scope,
});
const searchInput = ref(null);

const createForm = ref(null);
const createReport = (data) => {
    useForm({
        patient_type: data.patient_type,
        hn: data.hn,
        date_serum: data.date_serum,
        request_hla: data.request_hla ?? false,
        request_cxm: data.request_cxm ?? false,
        request_addition_tissue: data.request_addition_tissue ?? false,
        donor_hn: data.donor_hn ?? null,
    }).post(props.routes.reportsStore);
};

let action = null;
const handleActionClicked = (selectedAction) => {
    action = {...selectedAction};
    switch (action.name) {
    case 'destroy-report':
    case 'cancel-report':
        openConfirmForm(action.config);
        break;
    default:
        break;
    }
};

const callAction = (reason) => {
    switch (action.name) {
    case 'destroy-report':
    case 'cancel-report':
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
