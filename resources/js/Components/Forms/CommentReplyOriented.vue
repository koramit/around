<template>
    <div>
        <ul class="mb-4 md:mb-8">
            <TransitionGroup name="flip-list">
                <CommentRecursive
                    v-for="comment in comments"
                    :key="comment.id"
                    :prop-comment="comment"
                />
            </TransitionGroup>
        </ul>
        <FormTextarea
            name="body"
            v-model="form.body"
            ref="commentInput"
        />
        <FormCheckbox
            :toggler="true"
            label="Notify OP"
            v-model="form.notify_op"
            class="my-2 md:mt-4"
        />
        <SpinnerButton
            :spin="form.processing"
            class="btn btn-accent mt-4 w-full"
            @click="postComment"
            :disabled="!form.body"
        >
            POST
        </SpinnerButton>
    </div>
</template>

<script setup>
import FormTextarea from '../Controls/FormTextarea.vue';
import SpinnerButton from '../Controls/SpinnerButton.vue';
import {reactive, ref} from 'vue';
import FormCheckbox from '../Controls/FormCheckbox.vue';
import CommentRecursive from '../Helpers/CommentRecursive.vue';

const props = defineProps({
    configs: {type: Object, required: true}
});

const commentInput = ref(null);
const comments = ref([]);

window.axios
    .get(props.configs.routes.reply_index, {
        params: {
            commentable_type: props.configs.commentable_type,
            commentable_id: props.configs.commentable_id
        },
    }).then(res => comments.value = res.data)
    .catch(error => console.log(error));

const form = reactive({
    commentable_type: props.configs.commentable_type,
    commentable_id: props.configs.commentable_id,
    body: null,
    notify_op: false,
    processing: false,
});

const postComment = () => {
    form.processing = true;
    window.axios
        .post(props.configs.routes.reply_store, form)
        .then(res => {
            comments.value.push(res.data);
            form.body = null;
            form.notify_op = false;
        })
        .catch(error => console.log(error))
        .finally(() => form.processing = false);
};
</script>
