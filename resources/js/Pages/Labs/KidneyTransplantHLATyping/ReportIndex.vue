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
                        class="px-6 py-4 border-t"
                        v-text="report[field]"
                    />
                </template>
                <td class="px-6 py-4 border-t">
                    <div class="flex">
                        <InertiaLink
                            :href="report.routes.edit"
                            class="action-icon"
                        >
                            <IconEdit class="w-4 h-4" />
                        </InertiaLink>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <CreateForm
        :service-endpoint="routes.patientShow"
        @confirmed="createReport"
        ref="createForm"
    />
</template>

<script setup>
import SearchIndex from '../../../Components/Controls/SearchIndex.vue';
import {onMounted, reactive, ref} from 'vue';
import {InertiaLink,useForm} from '@inertiajs/inertia-vue3';
import IconEdit from '../../../Components/Helpers/Icons/IconEdit.vue';
import CreateForm from '../../../Partials/Labs/KidneyTransplantHLATyping/CreateForm.vue';
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
        donor_hn: data.donor_hn ?? null,
    }).post(props.routes.reportStore);
};

onMounted(() => searchInput.value.focus());
</script>

<style scoped>

</style>
