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

    <div
        v-for="(caseRecord, key) in cases.data"
        :key="key"
        class="bg-white rounded shadow my-2 md:my-4 p-2 md:p-4"
    >
        {{ caseRecord.title }}
        <Link
            :href="caseRecord.route"
            class="btn btn-complement"
        >
            edit
        </Link>
    </div>

    <!-- create new case -->
    <CreateForm
        :service-endpoint="routes.admissionsShow"
        :admit-reasons="configs.admit_reasons"
        ref="createFormRef"
        @confirmed="createForm"
    />
</template>

<script setup>
import {defineAsyncComponent, reactive, ref} from 'vue';
import SearchIndex from '../../../Components/Controls/SearchIndex.vue';
import {Link, useForm} from '@inertiajs/vue3';
const CreateForm = defineAsyncComponent(() => import('../../../Partials/KidneyTransplantAdmission/CreateForm.vue'));

const props = defineProps({
    cases: {type: Object, required: true},
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

function createForm(form) {
    useForm(form).post(props.routes.store);
}

</script>

