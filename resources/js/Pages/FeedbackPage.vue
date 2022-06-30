<template>
    <FormTextarea
        name="form.feedback"
        label="feeback"
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
    <div v-if="feedback.links.length > 3">
        <div class="flex flex-wrap -mb-1 mt-4">
            <template v-for="(link, key) in feedback.links">
                <div
                    v-if="link.url === null"
                    :key="key"
                    class="mr-1 mb-1 px-4 py-3 text-sm leading-4 bg-gray-200 text-gray-400 border rounded cursor-not-allowed"
                    v-html="link.label"
                />
                <Link
                    v-else
                    :key="key+'theLink'"
                    class="mr-1 mb-1 px-4 py-3 text-sm text-complement-darker leading-4 border border-primary-darker rounded hover:bg-white focus:border-complement-darker focus:text-complement-darker transition-colors"
                    :class="{ 'bg-primary-darker cursor-not-allowed hover:bg-primary-darker': link.active }"
                    :href="link.url"
                    as="button"
                    :disabled="link.active"
                >
                    <span v-html="link.label" />
                </Link>
            </template>
        </div>
    </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/inertia-vue3';
import FormTextarea from '../Components/Controls/FormTextarea.vue';

defineProps({
    feedback: { type: Object, required: true },
    configs: { type: Object, required: true },
});
const form = useForm({ feedback: null });
</script>