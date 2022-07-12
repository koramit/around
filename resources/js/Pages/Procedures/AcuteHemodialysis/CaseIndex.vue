<template>
    <!-- search tool & create case -->
    <div class="flex flex-col-reverse md:flex-row justify-between items-center mb-4">
        <SearchIndex
            :scopes="['active', 'discharged', 'all']"
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
                    v-for="column in ['HN', 'Name', 'Latest', 'Type', 'status', 'MD']"
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
                    v-for="field in ['hn', 'patient_name', 'date_note', 'dialysis_type', 'status', 'md']"
                    :key="field"
                >
                    <td
                        v-if="field !== 'status'"
                        class="px-6 py4 border-t"
                        v-text="caseRecord[field]"
                    />
                    <td
                        v-else
                        class="px-6 py4 border-t"
                    >
                        <div class="flex items-center justify-center">
                            <span v-html="caseRecord[field]" />
                            <Link
                                v-if="caseRecord.can.edit_order"
                                :href="caseRecord.routes.edit_order"
                                class="ml-2 action-icon"
                            >
                                <IconEdit class="w-4 h-4" />
                            </Link>
                            <Link
                                v-else-if="caseRecord.can.create_order"
                                :href="caseRecord.routes.create_order"
                                class="action-icon"
                            >
                                <IconCalendarPlus class="w-4 h-4" />
                            </Link>
                        </div>
                    </td>
                </template>
                <td class="border-t">
                    <Link
                        class="px-4 py-2 flex items-center focus:text-primary-darker"
                        :href="caseRecord.routes.edit"
                    >
                        <div
                            class="action-icon"
                        >
                            <IconDoubleRight class="w-4 h-4" />
                        </div>
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
                    class="bg-primary-darker p-2 rounded-full"
                >
                    <IconDoubleRight class="w-4 h-4 text-accent" />
                </Link>
            </div>
            <div class="my-2 p-2 bg-gray-100 rounded space-y-2">
                <div
                    v-if="!caseRecord.md"
                    class="flex justify-center items-center h-12"
                >
                    <Link
                        v-if="caseRecord.can.create_order"
                        :href="caseRecord.routes.create_order"
                        class="p-2 rounded-full bg-primary-darker text-accent"
                    >
                        <IconCalendarPlus class="w-4 h-4" />
                    </Link>
                    <p
                        v-else
                        class="italic text-center w-full"
                    >
                        No order yet
                    </p>
                </div>
                <template v-else>
                    <div class="flex items-center justify-between">
                        <p class="text-xs italic text-complement font-semibold">
                            Latest order
                        </p>
                        <p class="font-semibold text-complement text-xs flex items-center">
                            <IconUserMd class="h-3 w-3 mr-1" />
                            <span class="block italic truncate">{{ caseRecord.md }}</span>
                        </p>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="flex items-center">
                            On : <span
                                class="text-complement font-semibold"
                                v-text="caseRecord.date_note"
                            />
                            <Link
                                v-if="caseRecord.can.edit_order"
                                :href="caseRecord.routes.edit_order"
                                class="ml-2 p-2 rounded-full bg-primary-darker text-accent"
                            >
                                <IconEdit class="w-4 h-4" />
                            </Link>
                        </p>
                        <p>
                            Type : <span
                                class="text-complement font-semibold"
                                v-text="caseRecord.dialysis_type"
                            />
                        </p>
                    </div>
                    <template />
                </template>
            </div>
        </div>
    </div>

    <!-- pagination -->
    <div v-if="cases.links.length > 3">
        <div class="flex flex-wrap -mb-1 mt-4">
            <template v-for="(link, key) in cases.links">
                <div
                    v-if="link.url === null"
                    :key="key"
                    class="mr-1 mb-1 px-4 py-3 text-sm leading-4 bg-gray-200 text-gray-400 border rounded cursor-not-allowed"
                    v-html="link.label"
                />
                <Link
                    v-else
                    :key="key+'theLink'"
                    class="mr-1 mb-1 px-4 py-3 text-sm text-complement-darker leading-4 border border-primary-darker rounded hover:bg-white focus:border-complement-darker focus:text-complement-darker transition-colors"
                    :class="{ 'bg-primary-darker cursor-not-allowed hover:bg-primary-darker': link.active }"
                    :href="link.url"
                    :as="'button'"
                    :disabled="link.active"
                >
                    <span v-html="link.label" />
                </Link>
            </template>
        </div>
    </div>

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

const SearchAdmission = defineAsyncComponent(() => import('../../../Components/Forms/SearchAdmission.vue'));
const props = defineProps({
    cases: {type: Object, required: true},
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
