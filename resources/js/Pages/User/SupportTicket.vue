<template>
    <FormTextarea
        name="form.feedback"
        label="feedback"
        placeholder="Please feel free to leave an anonymous feedback 🙃"
        v-model="form.feedback"
    />
    <button
        class="mt-4 md:mt8 btn btn-accent w-full md:w-1/2"
        @click="form.post(configs.store_endpoint, { preserveState: false })"
        :disabled="!form.feedback || form.processing"
    >
        SEND
    </button>

    <div class="my-4 md:my-8 grid gap-2 md:gap-4">
        <div
            v-for="(comment, key) in feedback.data"
            :key="key"
            class="bg-primary-darker rounded-xl shadow-sm p-2 md:p-4"
        >
            <pre
                class="font-sarabun"
                v-text="comment.feedback"
            />
            <small
                class="mt-2 md:mt-4 block w-full text-right text-xs italic text-complement"
                v-text="comment.when"
            />
        </div>
    </div>

    <!-- pagination -->
    <PaginationNav :links="feedback.links" />
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import FormTextarea from '../../Components/Controls/FormTextarea.vue';
import PaginationNav from '../../Components/Helpers/PaginationNav.vue';

defineProps({
    feedback: { type: Object, required: true },
    configs: { type: Object, required: true },
});
const form = useForm({ feedback: null });
</script>
