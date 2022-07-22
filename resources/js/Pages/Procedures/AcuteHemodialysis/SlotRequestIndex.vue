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
                    v-for="(request, key) in requests"
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
                            @action-clicked="handleActionClicked"
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
                v-for="(request, key) in requests"
                :key="key"
            >
                <div class="flex justify-between items-center my-2 px-2">
                    <div>
                        HN: {{ request.hn }} {{ request.patient_name }}
                    </div>
                    <!--<ActionDropdown-->
                    <!--    v-if="request.actions.length > 1"-->
                    <!--    :actions="request.actions"-->
                    <!--    @action-clicked="handleActionClicked"-->
                    <!--/>-->
                    <DropdownList v-if="request.actions.length > 1">
                        <template #default>
                            <div class="bg-primary-darker p-2 rounded-full">
                                <IconDoubleDown class="w-4 h-4 text-accent" />
                            </div>
                        </template>
                        <template #dropdown>
                            <ActionDropdown
                                :actions="request.actions"
                                @action-clicked="handleActionClicked"
                            />
                        </template>
                    </DropdownList>
                    <ActionColumn
                        v-else-if="request.actions.length === 1"
                        :actions="request.actions"
                        @action-clicked="handleActionClicked"
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

        <!-- today slot  -->
        <h2 class="mt-4 md:mt-8 form-label text-lg italic text-complement scroll-mt-16 md:scroll-mt-8">
            Today Slot
        </h2>
        <hr class="my-4 border-b border-accent">
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

        <!-- request form  -->
        <h2 class="mt-4 md:mt-8 form-label text-lg italic text-complement scroll-mt-16 md:scroll-mt-8">
            request today extra slot
        </h2>
        <hr class="my-4 border-b border-accent">
        <div class="grid md:grid-cols-2 gap-2 lg:gap-6">
            <FormAutocompleteValue
                label="case"
                name="case"
                v-model="order.case"
                :endpoint="endpoints.cases"
                :error="order.errors.case"
                :length-to-start="1"
            />
            <FormAutocompleteValue
                label="dialysis at"
                name="dialysis_at"
                v-model="order.dialysis_at"
                :endpoint="endpoints.resources_api_wards"
                :error="order.errors.dialysis_at"
                :length-to-start="1"
            />
            <FormAutocompleteValue
                label="attending"
                name="attending_staff"
                v-model="order.attending_staff"
                :endpoint="endpoints.resources_api_staffs"
                :params="configs.staffs_scope_params"
                :error="order.errors.attending_staff"
                :length-to-start="1"
            />
            <FormSelect
                label="dialysis type"
                name="order_dialysis_type"
                v-model="order.dialysis_type"
                :options="order.dialysis_at && order.dialysis_at.search('Hemo') !== -1 ? configs.in_unit_dialysis_types : configs.out_unit_dialysis_types"
                :disabled="!order.dialysis_at"
            />
            <div>
                <label class="form-label">patient type</label>
                <FormRadio
                    class="grid grid-cols-2 gap-x-2"
                    name="patient_type"
                    v-model="order.patient_type"
                    :options="configs.patient_types"
                    ref="patientTypeInput"
                />
            </div>
        </div>
        <div class="mt-2 lg:mt-0 md:pt-4">
            <SpinnerButton
                class="block w-full text-center btn btn-accent"
                :spin="order.processing"
                :disabled="true"
            >
                REQUEST RESERVE
            </SpinnerButton>
        </div>
    </div>
</template>

<script setup>
import {useForm, usePage} from '@inertiajs/inertia-vue3';
import DialysisSlot from '../../../Partials/Procedures/AcuteHemodialysis/DialysisSlot.vue';
import WardSlot from '../../../Partials/Procedures/AcuteHemodialysis/WardSlot.vue';
import FormAutocompleteValue from '../../../Components/Controls/FormAutocomplete.vue';
import FormSelect from '../../../Components/Controls/FormSelect.vue';
import FormRadio from '../../../Components/Controls/FormRadio.vue';
import SpinnerButton from '../../../Components/Controls/SpinnerButton.vue';
import ActionColumn from '../../../Components/Controls/ActionColumn.vue';
import {watch} from 'vue';
import IconUserMd from '../../../Components/Helpers/Icons/IconUserMd.vue';
import ActionDropdown from '../../../Components/Controls/ActionDropdown.vue';
import DropdownList from '../../../Components/Helpers/DropdownList.vue';
import IconDoubleDown from '../../../Components/Helpers/Icons/IconDoubleDown.vue';
defineProps({
    requests: { type: Array, required: true },
    slot: { type: Object, required: true },
    configs: { type: Object, required: true },
    endpoints: { type: Object, required: true },
});

// HD orders
const order = useForm({
    case: null,
    dialysis_type: null,
    dialysis_at: null,
    attending_staff: null,
    date_note: null,
    patient_type: null,
});

const showConfirm = (payload) => {
    usePage().props.value.event.name = 'confirmation-required';
    usePage().props.value.event.payload = payload;
    usePage().props.value.event.fire = + new Date();
};

const handleActionClicked = (action) => {
    if (action.callback === 'approve-request') {
        useForm({approve: true}).patch(action.href);
    } else if (action.callback === 'disapprove-request') {
        selectedAction = {...action};
        showConfirm({heading: action.confirm_heading, confirmText: action.confirm_text, confirmedEvent: disapproveRequestConfirmedEvent, requireReason: true});
    } else if (action.callback === 'cancel-request') {
        selectedAction = {...action};
        showConfirm({heading: action.confirm_heading, confirmText: action.confirm_text, confirmedEvent: cancelRequestConfirmedEvent, requireReason: true});
    }
};

const disapproveRequestConfirmedEvent = 'disapprove-acute-hemodialysis-slot-request';
const cancelRequestConfirmedEvent = 'cancel-acute-hemodialysis-slot-request';
let selectedAction;
watch(
    () => usePage().props.value.event.fire,
    (event) => {
        if (! event) {
            return;
        }
        if (usePage().props.value.event.name === disapproveRequestConfirmedEvent) {
            useForm({approve: false, reason: usePage().props.value.event.payload})
                .patch(selectedAction.href, {
                    preserveState: false,
                    onFinish: () => selectedAction = null,
                });
        } else if (usePage().props.value.event.name === cancelRequestConfirmedEvent) {
            useForm({reason: usePage().props.value.event.payload})
                .delete(selectedAction.href, {
                    preserveState: false,
                    onFinish: () => selectedAction = null,
                });
        }
    }
);
</script>
