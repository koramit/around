<template>
    <div>
        <ul class="mb-4 md:mb-8">
            <TransitionGroup name="flip-list">
                <li
                    class="mt-2 md:mt-4 scroll-mt-28 md:scroll-mt-14"
                    :class="{'animate-bounce': focusedCommentId === comment.id}"
                    v-for="comment in comments"
                    :key="comment.id"
                    :id="`comment-id-${comment.id}`"
                >
                    <div
                        v-if="comment.parent_body"
                        class="cursor-pointer"
                        @click="gotoParent(comment.parent_id)"
                    >
                        <p
                            class="px-4 py-1 md:px-8 md:py-2 text-xs md:text-sm bg-complement/50 text-white rounded-t italic"
                            v-html="comment.parent_body"
                        />
                    </div>
                    <CommentBody
                        @reply="reply(comment)"
                        :replying="comment.id === form.parent_id"
                        :comment="comment"
                    />
                </li>
            </TransitionGroup>
        </ul>
        <transition name="slide-fade">
            <div
                class="flex justify-between items-start space-x-2 md:space-x-4 p-2 md:p-4 rounded-t-lg bg-complement/50 text-white"
                v-if="parent.body"
            >
                <p
                    v-html="parent.body"
                    class="cursor-pointer"
                    @click="gotoParent(form.parent_id)"
                />
                <button @click="cancelReply">
                    <IconTimesCircle class="w-4 h-4" />
                </button>
            </div>
        </transition>
        <FormTextarea
            name="comment_body"
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
            {{ form.parent_id ? 'REPLY':'POST' }}
        </SpinnerButton>
    </div>
</template>

<script setup>
import CommentBody from '../Helpers/CommentBody.vue';
import {reactive, ref} from 'vue';
import FormTextarea from '../Controls/FormTextarea.vue';
import FormCheckbox from '../Controls/FormCheckbox.vue';
import SpinnerButton from '../Controls/SpinnerButton.vue';
import {useInPageLinkHelpers} from '../../functions/useInPageLinkHelpers.js';
import IconTimesCircle from '../Helpers/Icons/IconTimesCircle.vue';

const props = defineProps({
    configs: {type: Object, required: true}
});

const comments = ref([]);

window.axios
    .get(props.configs.routes.timeline_index, {
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
    parent_id: null,
    processing: false,
});

const commentInput = ref(null);
const postComment = () => {
    form.processing = true;
    window.axios
        .post(props.configs.routes.timeline_store, form)
        .then(res => {
            comments.value.push(res.data);
            form.body = null;
            form.notify_op = false;
            form.parent_id = null;
            parent.body = null;
        })
        .catch(error => console.log(error))
        .finally(() => form.processing = false);
};

const parent = reactive({
    body: null,
});
const reply = (comment) => {
    if (form.parent_id === comment.id) {
        parent.body = null;
        form.parent_id = null;
        form.body = null;
        return;
    }
    parent.body = comment.body;
    form.parent_id = comment.id;
    smoothScroll('#comment_body');
    setTimeout(() => commentInput.value.focus(), 300);
};
const cancelReply = () => {
    parent.body = null;
    form.parent_id = null;
};

const focusedCommentId = ref(null);
const gotoParent = (parentId) => {
    focusedCommentId.value = parentId;
    smoothScroll(`#comment-id-${parentId}`);
    setTimeout(() => focusedCommentId.value = null, 2000);
};

const { smoothScroll } = useInPageLinkHelpers();
</script>
