<template>
    <FormInput
        label="search"
        name="search"
        v-model="search"
    />
    <ul class="mt-6 space-y-2">
        <li
            v-for="ep in [...episodes].filter(ep => ep.title.toLowerCase().indexOf(search.toLocaleLowerCase()) !== -1).sort()"
            :key="ep"
            class="py-1 px-2 bg-white rounded"
        >
            <span
                v-html="ep.title.replace('f/j/NephSAP/', '').replace(new RegExp(search, 'i'), `<span class='bg-complement text-white'>${search}</span>`)"
            />
            <a
                class="ml-4 text-blue-500"
                :href="ep.route"
                target="_blank"
            >
                play
            </a>
        </li>
    </ul>
</template>

<script setup>
import FormInput from '../../Components/Controls/FormInput.vue';
import {ref} from 'vue';

defineProps({
    episodes: {type: Array, required: true}
});

const search = ref('');
</script>