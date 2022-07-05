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
                    <td class="border-t">
                        <Link
                            class="px-4 py-2 flex items-center focus:text-primary-darker"
                            href="#"
                        >
                            <div class="p-2 rounded-full bg-white hover:bg-primary transition-colors ease-in-out duration-200">
                                <IconDoubleRight class="w-4 h-4 text-accent" />
                            </div>
                        </Link>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import IconDoubleRight from '../../../Components/Helpers/Icons/IconDoubleRight.vue';
defineProps({
    requests: { type: Array, required: true }
});
</script>