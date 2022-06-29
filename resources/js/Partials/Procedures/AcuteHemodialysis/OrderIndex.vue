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
                    <td class="border-t">
                        <DropdownList v-if="order.actions.length">
                            <template #default>
                                <div class="p-2 rounded-full bg-white hover:bg-primary transition-colors ease-in-out duration-200">
                                    <IconDoubleDown class="w-4 h-4 text-accent" />
                                </div>
                            </template>
                            <template #dropdown>
                                <div class="mt-2 py-0 overflow-hidden shadow-xl bg-complement text-white cursor-pointer rounded text-sm">
                                    <Link
                                        v-for="(action, action_key) in order.actions"
                                        :key="action_key"
                                        :href="action.href"
                                        :as="action.as"
                                        :type="action.type"
                                        :method="action.method"
                                        :preserve-state="action.preserveState"
                                        class="block w-full text-left px-6 py-2 hover:bg-complement-darker hover:text-primary transition-colors duration-200 ease-out"
                                    >
                                        {{ action.label }}
                                    </Link>
                                </div>
                            </template>
                        </DropdownList>
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
                    <DropdownList v-if="order.actions.length">
                        <template #default>
                            <div class="bg-primary-darker p-2 rounded-full">
                                <IconDoubleDown class="w-4 h-4 text-accent" />
                            </div>
                        </template>
                        <template #dropdown>
                            <div class="mt-2 py-0 overflow-hidden shadow-xl bg-complement text-white cursor-pointer rounded text-sm whitespace-nowrap">
                                <Link
                                    v-for="(action, action_key) in order.actions"
                                    :key="action_key"
                                    :href="action.href"
                                    :as="action.as"
                                    :type="action.type"
                                    :method="action.method"
                                    :preserve-state="action.preserveState"
                                    class="block w-full text-left p-2"
                                >
                                    {{ action.label }}
                                </Link>
                            </div>
                        </template>
                    </DropdownList>
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
import { Link } from '@inertiajs/inertia-vue3';
import IconUserMd from '@/Components/Helpers/Icons/IconUserMd.vue';
import DropdownList from '@/Components/Helpers/DropdownList.vue';
import IconDoubleDown from '@/Components/Helpers/Icons/IconDoubleDown.vue';
defineProps({
    orders: { type: Array, required: true }
});
</script>