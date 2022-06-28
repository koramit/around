<template>
    <div
        v-if="orders.length"
        class="space-y-1"
    >
        <!-- table  -->
        <div class="bg-white rounded shadow overflow-x-auto hidden md:block">
            <table class="w-full whitespace-nowrap">
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
                        class="px-6 py4 border-t"
                        v-for="field in ['date_note', 'dialysis_type', 'ward_name', 'status', 'md']"
                        :key="field"
                        v-text="order[field]"
                    />
                    <td class="border-t">
                        <Link
                            class="px-4 py-2 flex items-center focus:text-primary-darker"
                            :href="order.edit_route"
                        >
                            <div class="p-2 rounded-full bg-white hover:bg-primary transition-colors ease-in-out duration-200">
                                <IconDoubleRight
                                    class="w-4 h-4 text-accent"
                                />
                            </div>
                        </Link>
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
                    <Link
                        :href="order.edit_route"
                        class="bg-primary-darker p-2 rounded-full"
                    >
                        <IconDoubleRight class="w-4 h-4 text-accent" />
                    </Link>
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
import IconDoubleRight from '@/Components/Helpers/Icons/IconDoubleRight';
import IconUserMd from '@/Components/Helpers/Icons/IconUserMd';
defineProps({
    orders: { type: Array, required: true }
});
</script>