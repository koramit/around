<template>
    <div>
        <!-- table -->
        <div class="bg-white rounded shadow overflow-x-auto hidden md:block">
            <table class="w-full whitespace-nowrap">
                <tr class="text-left font-semibold text-complement">
                    <th
                        class="px-6 pt-6 pb-4"
                        v-for="column in ['HN', 'Name', 'Request', 'By']"
                        :key="column"
                        v-text="column"
                        :colspan="column === 'MD' ? 2:1"
                    />
                </tr>
                <tr
                    class="focus-within:bg-primary-darker"
                    v-for="(request, key) in requests.data"
                    :key="key"
                >
                    <td
                        class="px-6 py4 border-t"
                        v-for="field in ['hn', 'patient_name', 'request', 'requester']"
                        :key="field"
                        v-text="request[field]"
                    />
                    <!--actions -->
                    <td class="border-t">
                        <ActionColumn
                            v-if="request.actions.length"
                            :actions="request.actions"
                            @action-clicked="handleButtonActionClicked"
                        />
                        <div
                            v-else
                            class="p-2 flex justify-center items-center"
                        >
                            <span v-html="request.status" />
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- card -->
        <div class="md:hidden">
            <div
                class="bg-white rounded shadow my-4 p-4"
                v-for="(request, key) in requests.data"
                :key="key"
            >
                <div class="flex justify-between items-center my-2 px-2">
                    <div>
                        HN: {{ request.hn }} {{ request.patient_name }}
                    </div>
                    <DropdownList v-if="request.actions.length > 1">
                        <template #default>
                            <div class="bg-primary-darker p-2 rounded-full">
                                <IconDoubleDown class="w-4 h-4 text-accent" />
                            </div>
                        </template>
                        <template #dropdown>
                            <ActionDropdown
                                :actions="request.actions"
                                @action-clicked="handleButtonActionClicked"
                            />
                        </template>
                    </DropdownList>
                    <ActionColumn
                        v-else-if="request.actions.length === 1"
                        :actions="request.actions"
                        @action-clicked="handleButtonActionClicked"
                    />
                </div>
                <div class="my-2 p-2 bg-gray-100 rounded space-y-2">
                    <div class="flex justify-between items-center">
                        <span v-html="request.status" />
                        <p class="font-semibold text-complement text-xs flex items-center">
                            <IconUserMd class="h-3 w-3 mr-1" />
                            <span class="block italic truncate">{{ request.requester }}</span>
                        </p>
                    </div>
                    <div class="flex justify-center items-center">
                        <p class="italic text-center w-full">
                            {{ request.request }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!--pagination-->
        <PaginationNav :links="requests.links" />

        <ConfirmFormComposable
            ref="confirmForm"
            @confirmed="(reason) => confirmed(reason, handleConfirmedAction)"
        />
    </div>
</template>

<script setup>
import {useForm} from '@inertiajs/inertia-vue3';
import {defineAsyncComponent} from 'vue';
import {useConfirmForm} from '../../../functions/useConfirmForm.js';
import ActionColumn from '../../../Components/Controls/ActionColumn.vue';
import ActionDropdown from '../../../Components/Controls/ActionDropdown.vue';
import DropdownList from '../../../Components/Helpers/DropdownList.vue';
import IconDoubleDown from '../../../Components/Helpers/Icons/IconDoubleDown.vue';
import IconUserMd from '../../../Components/Helpers/Icons/IconUserMd.vue';
import PaginationNav from '../../../Components/Helpers/PaginationNav.vue';
const ConfirmFormComposable = defineAsyncComponent(() => import('../../../Components/Forms/ConfirmFormComposable.vue'));

defineProps({
    requests: { type: Object, required: true },
    configs: { type: Object, required: true },
    endpoints: { type: Object, required: true },
});

const { confirmForm, openConfirmForm, confirmed } = useConfirmForm();
let selectedAction = null;
const handleButtonActionClicked = (action) => {
    switch (action.name) {
    case 'approve-request':
        useForm({approve: true}).patch(action.route);
        break;
    case 'disapprove-request':
    case 'cancel-request':
        openConfirmForm(action.config);
        selectedAction = {...action};
        break;
    default:
        return;
    }
};
const handleConfirmedAction = (reason) => {
    switch (selectedAction.name) {
    case 'disapprove-request':
        useForm({approve: false, reason: reason})
            .patch(selectedAction.route, {
                preserveState: false,
                onFinish: () => selectedAction = null,
            });
        break;
    case 'cancel-request':
        useForm({reason: reason})
            .delete(selectedAction.route, {
                preserveState: false,
                onFinish: () => selectedAction = null,
            });
        break;
    default:
        return;
    }
};
</script>
