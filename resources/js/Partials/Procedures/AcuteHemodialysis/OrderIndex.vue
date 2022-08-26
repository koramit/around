<template>
    <div
        v-if="orders.length"
        class="space-y-1"
    >
        <!-- table  -->
        <div class="bg-white rounded shadow overflow-x-auto hidden md:block">
            <table class="w-full whitespace-nowrap pb-48">
                <tr class="text-left font-semibold text-complement">
                    <th
                        class="px-6 pt-6 pb-4"
                        v-for="column in ['On', 'Type', 'At', 'Status', 'MD']"
                        :key="column"
                        v-text="column"
                        :colspan="column === 'MD' ? 2:1"
                    />
                </tr>
                <tr
                    class="focus-within:bg-primary-darker"
                    v-for="(order, key) in orders"
                    :key="key"
                >
                    <td
                        class="px-6 py-4 border-t"
                        v-for="field in ['date_note', 'dialysis_type', 'ward_name', 'status', 'md']"
                        :key="field"
                        v-text="order[field]"
                    />
                    <!--actions-->
                    <td class="border-t">
                        <ActionColumn
                            v-if="order.actions.length"
                            :actions="order.actions"
                            @action-clicked="handleActionClicked"
                        />
                    </td>
                </tr>
            </table>
        </div>
        <!-- card -->
        <div class="md:hidden space-y-2">
            <div
                class="bg-white rounded shadow my-4 p-4"
                v-for="(order, key) in orders"
                :key="key"
            >
                <div class="flex justify-between items-center my-2 px-2">
                    <div>
                        {{ order.ward_name }}
                    </div>
                    <DropdownList v-if="order.actions.length > 1">
                        <template #default>
                            <div class="bg-primary-darker p-2 rounded-full">
                                <IconDoubleDown class="w-4 h-4 text-accent" />
                            </div>
                        </template>
                        <template #dropdown>
                            <ActionDropdown
                                :actions="order.actions"
                                @action-clicked="handleActionClicked"
                            />
                        </template>
                    </DropdownList>
                    <ActionColumn
                        v-else-if="order.actions.length === 1"
                        :actions="order.actions"
                        @action-clicked="handleActionClicked"
                    />
                </div>
                <div class="my-2 p-2 bg-gray-100 rounded space-y-2">
                    <div class="flex items-center justify-between">
                        <p class="text-xs italic text-complement font-semibold">
                            {{ order.status }}
                        </p>
                        <p class="font-semibold text-complement text-xs flex items-center">
                            <IconUserMd class="h-3 w-3 mr-1" />
                            <span class="block italic truncate">{{ order.md }}</span>
                        </p>
                    </div>
                    <div class="flex items-center justify-between">
                        <p>
                            On : <span
                                class="text-complement font-semibold"
                                v-text="order.date_note"
                            />
                        </p>
                        <p>
                            Type : <span
                                class="text-complement font-semibold"
                                v-text="order.dialysis_type"
                            />
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <p
        v-else
        class="italic"
    >
        No order yet
    </p>
</template>

<script setup>
import {useForm, usePage} from '@inertiajs/inertia-vue3';
import { watch } from 'vue';
import IconUserMd from '../../../Components/Helpers/Icons/IconUserMd.vue';
import DropdownList from '../../../Components/Helpers/DropdownList.vue';
import IconDoubleDown from '../../../Components/Helpers/Icons/IconDoubleDown.vue';
import ActionColumn from '../../../Components/Controls/ActionColumn.vue';
import ActionDropdown from '../../../Components/Controls/ActionDropdown.vue';
defineProps({
    orders: { type: Array, required: true }
});

watch(
    () => usePage().props.value.event.fire,
    (event) => {
        if (! event) {
            return;
        }
        if (usePage().props.value.event.name === cancelOrderConfirmedEvent) {
            useForm({reason: usePage().props.value.event.payload})
                .delete(selectedEndpoint, {
                    preserveState: false,
                    onFinish: () => selectedEndpoint = null,
                });
        }
    }
);

const cancelOrderConfirmedEvent = 'cancel-acute-hd-order-confirmed';
let selectedEndpoint;
const handleActionClicked = (action) => {
    if (action.callback === 'cancel-order') {
        selectedEndpoint = action.href;
        usePage().props.value.event.name = 'confirmation-required';
        usePage().props.value.event.payload = { heading: usePage().props.value.flash.title, confirmText: action.confirm_text, confirmedEvent: cancelOrderConfirmedEvent, requireReason: true };
        usePage().props.value.event.fire = + new Date();
    }
};
</script>
