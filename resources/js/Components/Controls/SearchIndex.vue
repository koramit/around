<template>
    <div class="flex items-center w-full md:w-auto">
        <input
            class="form-input md:w-auto border-r-0 rounded-r-none"
            type="text"
            name="search"
            @input="$emit('searchChanged', $event.target.value)"
            placeholder="search..."
            autocomplete="off"
        >
        <div class="flex justify-end form-input md:w-auto border-l-0 rounded-l-none">
            <DropdownList>
                <template #default>
                    <div class="flex items-center cursor-pointer select-none group">
                        <div>Scope : </div>
                        <div class="group-hover:text-accent-darker focus:text-accent-darker mr-1 whitespace-no-wrap">
                            <span class="text-complement group-hover:text-accent-darker focus:text-accent-darker">{{ form.scope }}</span>
                        </div>
                        <svg
                            class="w-4 h-4 text-complement group-hover:text-accent-darker focus:text-accent-darker"
                            viewBox="0 0 320 512"
                        ><path
                            fill="currentColor"
                            d="M143 256.3L7 120.3c-9.4-9.4-9.4-24.6 0-33.9l22.6-22.6c9.4-9.4 24.6-9.4 33.9 0l96.4 96.4 96.4-96.4c9.4-9.4 24.6-9.4 33.9 0L313 86.3c9.4 9.4 9.4 24.6 0 33.9l-136 136c-9.4 9.5-24.6 9.5-34 .1zm34 192l136-136c9.4-9.4 9.4-24.6 0-33.9l-22.6-22.6c-9.4-9.4-24.6-9.4-33.9 0L160 352.1l-96.4-96.4c-9.4-9.4-24.6-9.4-33.9 0L7 278.3c-9.4 9.4-9.4 24.6 0 33.9l136 136c9.4 9.5 24.6 9.5 34 .1z"
                        /></svg>
                    </div>
                </template>
                <template #dropdown>
                    <div class="mt-2 py-2 shadow-xl bg-white text-complement cursor-pointer rounded text-sm">
                        <button
                            class="block w-full text-left font-semibold px-6 py-2 transition-colors duration-200 ease-out hover:bg-primary hover:text-accent-darker"
                            v-for="(scope, key) in scopes"
                            :key="key"
                            @click="$emit('scopeChanged', scope)"
                        >
                            {{ scope }}
                        </button>
                    </div>
                </template>
            </DropdownList>
        </div>
    </div>
</template>

<script setup>
import DropdownList from '../Helpers/DropdownList.vue';
import _pickBy from 'lodash/pickBy';
import _throttle from 'lodash/throttle';
import { watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';

defineEmits(['searchChanged','scopeChanged']);

const props = defineProps({
    scopes: { type: Array, default: () => [] },
    form: { type: Object, required: true },
});

watch (
    () => props.form,
    _throttle(function (val) {
        let queryParams = _pickBy(val);
        queryParams = Object.keys(queryParams).length ? queryParams : { remember: 'forget' };
        Inertia.get(location.pathname, queryParams, { preserveState: true });
    }, 800),
    {deep:true}
);
</script>