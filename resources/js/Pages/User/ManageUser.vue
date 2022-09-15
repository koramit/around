<template>
    <SearchIndex
        :scopes="[]"
        :form="searchForm"
        @search-changed="(val) => {
            searchForm.search = val;
            form.show = false;
        }"
        @scope-changed="(val) => searchForm.scope = val"
        ref="searchInput"
    />
    <div class="mt-2 md:mt-4 flex flex-col-reverse md:flex-row md:space-x-8">
        <div class="md:w-1/2">
            <div
                class="p-2 bg-white rounded shadow mb-2 flex justify-between items-center"
                v-for="user in users.data"
                :key="user.id"
            >
                <p>{{ user.name }}</p>
                <button
                    class="flex items-center"
                    @click="getUser(user.get_route)"
                >
                    <span class="md:hidden action-icon-mobile">
                        <IconDoubleRight class=" w-4 h-4" />
                    </span>
                    <span class="hidden md:inline-block action-icon">
                        <IconDoubleRight class=" w-4 h-4" />
                    </span>
                </button>
            </div>
        </div>
        <div
            class="scroll-mt-16 md:scroll-mt-8 md:w-1/2 mb-4"
            id="user-form"
        >
            <transition name="slide-fade">
                <div
                    class="p-2 md:p-4 rounded border-complement shadow space-y-2 md:space-y-4"
                    v-if="form.show"
                >
                    <DisplayData
                        v-for="field in ['name', 'position', 'division', 'remark']"
                        :key="field"
                        :label="field"
                        :data="form[field]"
                    />
                    <hr class="border-t-2 border-complement border-dashed">
                    <FormCheckbox
                        :toggler="true"
                        v-for="role in form.roles"
                        :key="role.name"
                        :label="role.name"
                        v-model="role.has_role"
                    />
                    <SpinnerButton
                        :spin="form.busy"
                        class="w-full btn btn-accent"
                        :disabled="formClean"
                        @click="update"
                    >
                        UPDATE
                    </SpinnerButton>
                </div>
            </transition>
        </div>
    </div>
    <PaginationNav :links="users.links" />
</template>

<script setup>
import {nextTick, reactive, ref, watch} from 'vue';
import DisplayData from '../../Components/Controls/DisplayData.vue';
import FormCheckbox from '../../Components/Controls/FormCheckbox.vue';
import {useInPageLinkHelpers} from '../../functions/useInPageLinkHelpers.js';
import IconDoubleRight from '../../Components/Helpers/Icons/IconDoubleRight.vue';
import SpinnerButton from '../../Components/Controls/SpinnerButton.vue';
import {isEqual} from 'lodash';
import PaginationNav from '../../Components/Helpers/PaginationNav.vue';
import SearchIndex from '../../Components/Controls/SearchIndex.vue';

const props = defineProps({
    users: {type: Object, required: true},
    filters: {type: Object, required: true},
});

const form = reactive({
    show: false,
    name: null,
    position: null,
    division: null,
    remark: null,
    roles: [],
    route: null,
});

const searchForm = reactive({
    search: props.filters.search,
    scope: props.filters.scope,
});

let formOrg = {roles: []};

const getUser = (route) => {
    form.show = false;
    window.axios
        .get(route)
        .then(res => {
            form.name = res.data.name;
            form.position = res.data.position;
            form.division = res.data.division;
            form.remark = res.data.remark;
            form.roles = [...res.data.roles];
            form.route = res.data.update_route;
            form.show = true;
            formOrg = {roles: JSON.parse(JSON.stringify(res.data.roles))}; // deep clone or formClean won't work
            nextTick(() => smoothScroll('#user-form'));
        });
};

const formClean = ref(true);

watch(
    () => form.roles,
    () => {
        formClean.value = isEqual(formOrg.roles, form.roles);
    },
    {deep: true}
);

const update = () => {
    window.axios
        .patch(form.route, {roles: form.roles})
        .then(() => form.show = false);
};
const { smoothScroll } = useInPageLinkHelpers();
</script>
