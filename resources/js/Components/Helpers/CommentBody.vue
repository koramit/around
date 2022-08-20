<template>
    <div
        class="p-2 pb-1 md:p-4 md:pb-2 bg-primary-darker text-complement rounded"
        :class="{
            'rounded-b rounded-t-none': comment.parent_body !== undefined && comment.parent_body
        }"
    >
        <p v-html="comment.body" />
        <div class="mt-4 md:mt-8 flex justify-between text-xs">
            <div>
                <button
                    class="inline-flex items-center text-accent space-x-1 md:space-x-2"
                    @click="$emit('reply')"
                >
                    <IconTimesCircle
                        v-if="replying"
                        class="w-3 h-3"
                    />
                    <IconReply
                        v-else
                        class="w-3 h-3"
                    />
                    <span>reply</span>
                </button>
                <button
                    v-if="comment.replies_count"
                    class="inline-flex items-center text-accent ml-2 md:ml-4 space-x-1 md:space-x-2"
                    @click="$emit('show-replies')"
                >
                    <IconEyesSlash
                        v-if="showReplies"
                        class="h-3 w-3"
                    />
                    <IconEyes
                        v-else
                        class="h-3 w-3"
                    />
                    <span>{{ comment.replies_count }} {{ comment.replies_count > 1 ? 'replies' : 'reply' }}</span>
                </button>
            </div>
            <div>
                <span class="italic text-complement">{{ comment.at }} by</span>
                <span class="ml-1 text-accent">{{ comment.commentator }}</span>
            </div>
        </div>
    </div>
</template>

<script setup>
import IconReply from './Icons/IconReply.vue';
import IconEyes from './Icons/IconEyes.vue';
import IconEyesSlash from './Icons/IconEyesSlash.vue';
import IconTimesCircle from './Icons/IconTimesCircle.vue';

defineEmits(['reply', 'show-replies']);
defineProps({
    comment: {type: Object, required: true},
    showReplies: {type: Boolean},
    replying: {type: Boolean},
});
</script>
