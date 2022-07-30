<template>
    <div>
        <div
            v-for="comment in comments"
            :key="comment.id"
            class="mb-4 md:mb-8"
        >
            <div
                class="p-2 pb-4 md:p-4 md:pb-8 bg-primary-darker text-complement rounded"
                v-html="comment.body"
            />
            <div class="flex justify-end text-sm">
                <span class="italic text-complement">{{ comment.at }} by</span>
                <span class="ml-1 text-accent">{{ comment.commentator }}</span>
            </div>
        </div>
        <FormTextarea
            name="body"
            v-model="form.body"
        />
        <SpinnerButton
            :spin="form.processing"
            class="btn btn-accent mt-4 w-full"
            @click="form.post(configs.routes.store, {
                preserveScroll: true,
                preserveState: false,
                onFinish: () => {form.processing = false;}
            })"
            :disabled="!form.body"
        >
            POST
        </SpinnerButton>
    </div>
</template>

<script setup>
import FormTextarea from '../Controls/FormTextarea.vue';
import {useForm} from '@inertiajs/inertia-vue3';
import SpinnerButton from '../Controls/SpinnerButton.vue';
import {defineProps, ref} from 'vue';

const props = defineProps({
    configs: {type: Object, required: true}
});

const comments = ref([]);

window.axios
    .post(props.configs.routes.index, {
        commentable_type: props.configs.commentable_type,
        commentable_id: props.configs.commentable_id,
    }).then(res => comments.value = res.data)
    .catch(error => console.log(error));

const form = useForm({
    commentable_type: props.configs.commentable_type,
    commentable_id: props.configs.commentable_id,
    body: null,
});
</script>
