<script setup>

import {ref} from 'vue';
import CreateForm from '../../../Partials/Clinics/PostKT/CreateForm.vue';
import {useForm} from '@inertiajs/vue3';

const props = defineProps({
    configs: {type: Object, required: true},
});

const createFormRef = ref(null);

function createCase(data) {
    useForm({...data})
        .post(props.configs.routes.store);
}
</script>

<template>
    <div>
        <div class="flex flex-col-reverse md:flex-row justify-between items-center mb-4">
            <button
                v-if="configs.can.create"
                class="btn btn-accent w-full mb-4 md:w-auto md:px-4 md:mb-0"
                @click="createFormRef.open()"
            >
                New Case
            </button>
        </div>

        <CreateForm
            :service-endpoint="configs.routes.admissionsShow"
            ref="createFormRef"
            @confirmed="createCase"
        />
    </div>
</template>

<style scoped>

</style>
