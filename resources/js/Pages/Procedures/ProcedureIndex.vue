<template>
    <div>
        <h1 class="font-lobster text-2xl text-complement">
            Procedures 🔪
        </h1>
        <hr class="my-4 border-b-2 border-accent">
        <div class="bg-white rounded shadow md:w-1/2">
            <table class="w-full whitespace-nowrap">
                <tr class="text-left font-semibold">
                    <th class="px-6 pt-6 pb-4">
                        Name
                    </th>
                </tr>
                <tr class="hover:bg-primary-darker focus-within:text-primary-darker">
                    <td class="border-t">
                        <Link
                            class="px-6 py-4 flex items-center focus:text-accent"
                            :href="routes['acute-hemodialysis']"
                        >
                            Acute Hemodialysis
                        </Link>
                    </td>
                </tr>
                <tr class="hover:bg-primary-darker focus-within:bg-primary-darker">
                    <td class="border-t">
                        <Link
                            class="px-6 py-4 flex items-center focus:text-accent"
                            href="#"
                        >
                            Kidney Biopsy
                        </Link>
                    </td>
                </tr>
                <tr class="hover:bg-primary-darker focus-within:bg-primary-darker">
                    <td class="border-t">
                        <Link
                            class="px-6 py-4 flex items-center focus:text-accent"
                            href="#"
                        >
                            Vascular Access
                        </Link>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/inertia-vue3';
defineProps({
    routes: { type: Object, required: true }
});
</script>