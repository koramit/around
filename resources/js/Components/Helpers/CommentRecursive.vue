<template>
    <li class="mt-2 md:mt-4">
        <CommentBody
            :comment="comment"
            :show-replies="showReplies"
            :replying="showReplyForm"
            @show-replies="fetchReplies"
            @reply="replying"
        />
        <transition name="slide-fade">
            <div
                v-if="showReplyForm"
                class="ml-4 mt-4"
            >
                <FormTextarea
                    name="body"
                    v-model="form.body"
                    ref="replyInput"
                />
                <FormCheckbox
                    :toggler="true"
                    label="Notify OP"
                    v-model="form.notify_op"
                    class="my-2 md:mt-4"
                />
                <SpinnerButton
                    :spin="form.processing"
                    class="btn btn-complement mt-4 w-full"
                    @click="postReply"
                    :disabled="!form.body"
                >
                    REPLY
                </SpinnerButton>
            </div>
        </transition>
        <transition name="slide-fade">
            <ul
                class="border-accent border-l"
                v-show="replies.length && showReplies"
            >
                <CommentRecursive
                    v-for="reply in replies"
                    :key="reply.id"
                    :prop-comment="reply"
                    class="ml-4"
                    @show-replies="fetchReplies"
                />
            </ul>
        </transition>
    </li>
</template>

<script setup>
import CommentBody from './CommentBody.vue';
import {nextTick, reactive, ref, watch} from 'vue';
import FormTextarea from '../Controls/FormTextarea.vue';
import FormCheckbox from '../Controls/FormCheckbox.vue';
import SpinnerButton from '../Controls/SpinnerButton.vue';
import {usePage} from '@inertiajs/inertia-vue3';

const props = defineProps({
    propComment: {type: Object, required: true},
});
const comment = reactive({...props.propComment});

const replyInput = ref(null);
const showReplyForm = ref(false);
const replying = () => {
    showReplyForm.value = !showReplyForm.value;
    if (!showReplyForm.value) {
        return;
    }

    nextTick(() => {
        setTimeout(() => {
            usePage().props.value.event.payload = comment.id;
            usePage().props.value.event.name = 'comment-recursive-reply-active';
            usePage().props.value.event.fire = + new Date();
        }, 300); // equal to animate duration
        replyInput.value.focus();
    });
};
watch(
    () => usePage().props.value.event.fire,
    (event) => {
        if (! event) {
            return;
        }
        if (usePage().props.value.event.name === 'comment-recursive-reply-active') {
            if (
                showReplyForm.value
                && comment.id !== usePage().props.value.event.payload
            ) {
                showReplyForm.value = false;
            }
        }
    }
);

const form = reactive({
    body: null,
    notify_op: false,
});
const postReply = () => {
    window.axios
        .post(comment.routes.reply, form)
        .then((res) => {
            form.body = null;
            form.notify_op = false;
            replies.value = [...res.data];
            comment.replies_count = replies.value.length;
            nextTick(() => {
                showReplyForm.value = false;
                if (! showReplies.value) {
                    showReplies.value = true;
                }
            });
        })
        .catch(error => console.log(error));
};

const showReplies = ref(false);
const replies = ref([]);
const fetchReplies = () => {
    if (showReplies.value) {
        showReplies.value = false;
        return;
    }
    if (replies.value.length) {
        showReplies.value = true;
        return;
    }
    window.axios
        .get(comment.routes.show)
        .then(res => {
            replies.value = [...res.data];
            showReplies.value = true;
        })
        .catch(error => console.log(error));
};
</script>
