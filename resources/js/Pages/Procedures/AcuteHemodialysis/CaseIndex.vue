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
            @click="$refs.searchAdmission.open()"
        >
            Create New Case
        </button>
    </div>

    <!-- table -->
    <div class="bg-white rounded shadow overflow-x-auto hidden md:block">
        <table class="w-full whitespace-nowrap">
            <tr class="text-left font-semibold text-complement">
                <th
                    class="px-6 pt-6 pb-4"
                    v-for="column in ['HN', 'Name', 'Status', 'On', 'Type', 'Order', 'MD']"
                    :key="column"
                    v-text="column"
                    :colspan="column === 'MD' ? 2:1"
                />
            </tr>
            <tr
                class="focus-within:bg-primary-darker"
                v-for="(caseRecord, key) in cases.data"
                :key="key"
            >
                <template
                    v-for="field in ['hn', 'patient_name', 'case_status', 'date_note', 'dialysis_type', 'status', 'md']"
                    :key="field"
                >
                    <td
                        v-if="field === 'date_note'"
                        class="px-6 py-4 border-t"
                    >
                        <span
                            class="inline-flex h-6 w-6 mr-2 rounded-full items-center justify-center text-sm italic"
                            :class="{
                                'text-complement bg-primary-darker': caseRecord.dialysis_at === 'in',
                                'text-primary bg-complement': caseRecord.dialysis_at === 'out',
                            }"
                        >
                            {{ caseRecord.dialysis_at }}
                        </span>
                        <span class="">{{ caseRecord.date_note }}</span>
                    </td>
                    <td
                        v-else-if="field === 'status'"
                        class="px-6 py-4 border-t"
                    >
                        <div class="flex items-center justify-between">
                            <template v-if="caseRecord.can.edit_order">
                                <span v-html="caseRecord[field]" />
                                <Link
                                    :href="caseRecord.routes.edit_order"
                                    class="action-icon"
                                >
                                    <IconEdit class="w-4 h-4" />
                                </Link>
                            </template>
                            <template v-else-if="caseRecord.can.view_order">
                                <span v-html="caseRecord[field]" />
                                <Link
                                    :href="caseRecord.routes.view_order"
                                    class="action-icon"
                                >
                                    <IconReadme class="w-4 h-4" />
                                </Link>
                            </template>
                            <Link
                                v-else-if="caseRecord.can.create_order"
                                :href="caseRecord.routes.create_order"
                                class="action-icon"
                            >
                                <IconCalendarPlus class="w-4 h-4" />
                            </Link>
                            <span
                                v-else
                                v-html="caseRecord[field]"
                            />
                        </div>
                    </td>
                    <td
                        v-else
                        class="px-6 py-4 border-t"
                        v-text="caseRecord[field]"
                    />
                </template>
                <td class="border-t">
                    <Link
                        class="px-4 py-2 flex items-center"
                        :href="caseRecord.routes.edit"
                    >
                        <span class="action-icon">
                            <IconDoubleRight class=" w-4 h-4" />
                        </span>
                    </Link>
                </td>
            </tr>
        </table>
    </div>

    <!-- card -->
    <div class="md:hidden">
        <div
            class="bg-white rounded shadow my-4 p-4"
            v-for="(caseRecord, key) in cases.data"
            :key="key"
        >
            <div class="flex justify-between items-center my-2 px-2">
                <div>
                    HN: {{ caseRecord.hn }} {{ caseRecord.patient_name }}
                </div>
                <Link
                    :href="caseRecord.routes.edit"
                    class="action-icon-mobile"
                >
                    <IconDoubleRight class="w-4 h-4" />
                </Link>
            </div>
            <div class="my-2 p-2 bg-gray-100 rounded space-y-2">
                <template v-if="caseRecord.case_status !== 'active'">
                    <div
                        class="flex justify-end"
                        v-if="caseRecord.md"
                    >
                        <p class="font-semibold text-complement text-xs flex items-center">
                            <IconUserMd class="h-3 w-3 mr-1" />
                            <span class="block italic truncate">{{ caseRecord.md }}</span>
                        </p>
                    </div>
                    <p
                        class="text-center"
                        :class="{'pb-4': caseRecord.md}"
                    >
                        {{ caseRecord.case_status }}
                    </p>
                </template>
                <div
                    v-else-if="!caseRecord.date_note"
                    class="flex justify-center items-center h-12"
                >
                    <Link
                        v-if="caseRecord.can.create_order"
                        :href="caseRecord.routes.create_order"
                        class="p-2 rounded-full bg-primary-darker text-accent"
                    >
                        <IconCalendarPlus class="w-4 h-4" />
                    </Link>
                    <span v-else>{{ caseRecord.case_status }}</span>
                </div>
                <template v-else>
                    <div class="flex items-center justify-between">
                        <span v-html="caseRecord.status" />
                        <p class="font-semibold text-complement text-xs flex items-center">
                            <IconUserMd class="h-3 w-3 mr-1" />
                            <span class="block italic truncate">{{ caseRecord.md }}</span>
                        </p>
                    </div>
                    <div class="flex items-center justify-between">
                        <p>
                            On : <span
                                class="text-complement font-semibold"
                                v-text="caseRecord.date_note"
                            />
                            <span
                                class="text-sm italic ml-1"
                                :class="{
                                    'text-accent': caseRecord.dialysis_at === 'in',
                                    'text-complement': caseRecord.dialysis_at === 'out',
                                }"
                            >{{ caseRecord.dialysis_at }}</span>
                        </p>
                        <p class="flex items-center">
                            Type : <span
                                class="text-complement font-semibold"
                                v-text="caseRecord.dialysis_type"
                            />
                            <Link
                                v-if="caseRecord.can.edit_order"
                                :href="caseRecord.routes.edit_order"
                                class="ml-2 p-2 rounded-full bg-primary-darker text-accent"
                            >
                                <IconEdit class="w-4 h-4" />
                            </Link>
                            <Link
                                v-else-if="caseRecord.can.view_order"
                                :href="caseRecord.routes.view_order"
                                class="ml-2 p-2 rounded-full bg-primary-darker text-accent"
                            >
                                <IconReadme class="w-4 h-4" />
                            </Link>
                        </p>
                    </div>
                    <template />
                </template>
            </div>
        </div>
    </div>

    <!-- pagination -->
    <PaginationNav :links="cases.links" />

    <!-- create new case -->
    <SearchAdmission
        ref="searchAdmission"
        @confirmed="confirmed"
        mode="hn"
        :service-endpoint="routes.serviceEndpoint"
    />
</template>

<script setup>
import {defineAsyncComponent, onMounted, reactive, ref} from 'vue';
import {Link, useForm} from '@inertiajs/inertia-vue3';
import SearchIndex from '../../../Components/Controls/SearchIndex.vue';
import IconDoubleRight from '../../../Components/Helpers/Icons/IconDoubleRight.vue';
import IconUserMd from '../../../Components/Helpers/Icons/IconUserMd.vue';
import IconEdit from '../../../Components/Helpers/Icons/IconEdit.vue';
import IconCalendarPlus from '../../../Components/Helpers/Icons/IconCalendarPlus.vue';
import PaginationNav from '../../../Components/Helpers/PaginationNav.vue';
import IconReadme from '../../../Components/Helpers/Icons/IconReadme.vue';

const SearchAdmission = defineAsyncComponent(() => import('../../../Components/Forms/SearchAdmission.vue'));
const props = defineProps({
    cases: {type: Object, required: true},
    configs: {type: Object, required: true},
    filters: {type: Object, required: true},
    routes: {type: Object, required: true},
    can: {type: Object, required: true},
});
const searchAdmission = ref(null);
const newCase = useForm({
    hn: null,
    an: null
});
const searchForm = reactive({
    search: props.filters.search,
    scope: props.filters.scope,
});

const confirmed = (admission) => {
    newCase.hn = admission.hn;
    newCase.an = admission.an;
    newCase.post(props.routes.store);
};

const searchInput = ref(null);

onMounted(() => searchInput.value.focus());
</script>
