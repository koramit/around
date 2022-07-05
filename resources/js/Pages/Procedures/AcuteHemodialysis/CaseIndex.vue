<template>
    <!-- search tool & create case -->
    <div class="flex flex-col-reverse md:flex-row justify-between items-center mb-4">
        <SearchIndex
            :scopes="['active', 'discharged', 'all']"
            :form="searchForm"
            @search-changed="(val) => searchForm.search = val"
            @scope-changed="(val) => searchForm.scope = val"
        />
        <button
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
                    v-for="column in ['HN', 'Name', 'Latest', 'Type', 'MD']"
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
                <td
                    class="px-6 py4 border-t"
                    v-for="field in ['hn', 'patient_name', 'date_note', 'dialysis_type', 'md']"
                    :key="field"
                    v-text="caseRecord[field]"
                />
                <td class="border-t">
                    <Link
                        class="px-4 py-2 flex items-center focus:text-primary-darker"
                        :href="caseRecord.routes.edit"
                    >
                        <div class="p-2 rounded-full bg-white hover:bg-primary transition-colors ease-in-out duration-200">
                            <IconDoubleRight class="w-4 h-4 text-accent" />
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
                    class="flex justfy-center items-center h-12"
                >
                    <p class="italic text-center w-full">
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
                        <p>
                            On : <span
                                class="text-complement font-semibold"
                                v-text="caseRecord.date_note"
                            />
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
                    as="button"
                    :disabled="link.active"
                >
                    <span v-html="link.label" />
                </Link>
            </template>
        </div>
    </div>

    <SlotRequest
        class="mt-4 md:mt-8"
        :requests="requests"
    />

    <div class="mt-4 md:mt-8">
        <FormDatetime
            label="required date"
            name="date_note"
            v-model="slot.date_note"
            :options="{inline: true}"
            ref="dateNoteInput"
        />
        <!-- :options="{ enable: configs.reserve_available_dates, onDayCreate: onDayCreate, inline: true }" -->
        <div class="md:grid grid-cols-2 gap-4">
            <Transition
                name="slide-fade"
                v-if="slot.hd_unit.length"
            >
                <DialysisSlot
                    :slots="slot.hd_unit"
                />
            </Transition>
            <Transition
                name="slide-fade"
                v-if="slot.ward.length"
            >
                <WardSlot
                    :slots="slot.ward"
                />
            </Transition>
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
import { defineAsyncComponent, reactive, ref, watch } from 'vue';
import { Link, useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import pickBy from 'lodash/pickBy';
import debounce from 'lodash/debounce';
import SearchIndex from '@/Components/Controls/SearchIndex.vue';
import IconDoubleRight from '@/Components/Helpers/Icons/IconDoubleRight.vue';
import IconUserMd from '@/Components/Helpers/Icons/IconUserMd.vue';
import SlotRequest from '../../../Partials/Procedures/AcuteHemodialysis/SlotRequest.vue';
import FormDatetime from '../../../Components/Controls/FormDatetime.vue';
import DialysisSlot from '../../../Partials/Procedures/AcuteHemodialysis/DialysisSlot.vue';
import WardSlot from '../../../Partials/Procedures/AcuteHemodialysis/WardSlot.vue';
const SearchAdmission = defineAsyncComponent(() => import('@/Components/Forms/SearchAdmission.vue'));
const props = defineProps({
    cases: { type: Object, required: true },
    filters: { type: Object, required: true },
    routes: { type: Object, required: true },
    requests: { type: Array, required: true },
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
watch(
    () => searchForm,
    debounce(function () {
        let filters = pickBy(searchForm);
        let query = Object.keys(filters)
            .filter(key => filters[key])
            .map(key => `${key}=${filters[key]}`)
            .join('&');
        query = '?' + (query ? query : 'remember=forget');
        Inertia.visit(props.routes.index + query, { preserveState: true });
    }, 500),
    {deep: true}
);

const confirmed = (admission) => {
    newCase.hn = admission.hn;
    newCase.an = admission.an;
    newCase.post(props.routes.store);
};

const slot = reactive({
    hd_unit: [],
    ward: [],
    date_note: null,
});

watch(
    () => slot.date_note,
    (val) => {
        window.axios
            .post(props.routes.slot, {date_note: val})
            .then(res => {
                if (!res.data) {
                    return;
                }
                slot.hd_unit = [...res.data.hd_unit];
                slot.ward = [...res.data.ward];
            });
    },
);
</script>