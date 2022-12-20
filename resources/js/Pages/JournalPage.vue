<template>
    <FormInput
        label="search"
        name="search"
        v-model="search"
    />
    <ul class="mt-6 space-y-2">
        <li
            v-for="file in [...files].filter(f => f.toLowerCase().indexOf(search.toLocaleLowerCase()) !== -1).sort()"
            :key="file"
            class="py-1 px-2 bg-white rounded"
        >
            <span
                v-html="file.replace('f/j/NephSAP/', '').replace(new RegExp(search, 'i'), `<span class='bg-complement text-white'>${search}</span>`)"
            />
            {{ }}

            <a
                class="ml-4 text-blue-500"
                :href="`/journal/show?key=${file}`"
            >
                download
            </a>
        </li>
    </ul>
</template>

<script setup>
import FormInput from '../Components/Controls/FormInput.vue';
import {ref} from 'vue';

defineProps({
    files: {
        type: Array,
        required: true,
    },
});

const search = ref('');
</script>
