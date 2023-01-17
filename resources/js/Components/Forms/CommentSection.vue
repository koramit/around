<template>
    <div>
        <div class="text-xs md:text-sm text-accent">
            <IconAdjustments class="mr-1 w-3 h-4 inline" />
            <button
                v-if="mode === 'reply'"
                @click="mode = 'timeline'"
            >
                Timeline oriented
            </button>
            <button
                v-else-if="mode === 'timeline'"
                @click="mode = 'reply'"
            >
                Reply oriented
            </button>
        </div>

        <transition
            name="slide-fade"
            mode="in-out"
        >
            <CommentReplyOriented
                v-if="mode === 'reply'"
                :configs="configs"
            />
            <CommentTimelineOriented
                v-else-if="mode === 'timeline'"
                :configs="configs"
            />
        </transition>
    </div>
</template>

<script setup>
import {usePage} from '@inertiajs/vue3';
import {ref} from 'vue';
import CommentReplyOriented from './CommentReplyOriented.vue';
import CommentTimelineOriented from './CommentTimelineOriented.vue';
import IconAdjustments from '../Helpers/Icons/IconAdjustments.vue';

defineProps({
    configs: {type: Object, required: true}
});

const mode = ref(LinkusePage().props.user.preferences.discussion_mode);
</script>
