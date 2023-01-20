<template>
    <h1 class="text-lg text-center font-semibold text-complement mb-5">
        {{ episode.title }}
    </h1>
    <div
        class="text-center text-blue-400 mb-4"
        v-if="episode.attach !== undefined"
    >
        <a
            :href="encodeURI(`${baseUrl}${episode.attach}`)"
            target="_blank"
            rel="noopener noreferrer"
        >Download Slide</a>
    </div>

    <video
        id="player"
        playsinline
        controls
        poster="../../../images/nephflix-cover.png"
    >
        <source
            :src="episode.asset"
            type="video/mp4"
        >
    </video>
    <p class="mt-5">
        <span class="italic text-sm">{{ episode.speakers }}</span>
        <span
            v-show="episode.date"
            class="ml-2 px-4 py-2 rounded-xl bg-white text-sm font-semibold text-complement"
        >{{ episode.date }}</span>
    </p>
</template>

<script setup>
import Plyr from 'plyr';
import 'plyr/dist/plyr.css';
import {onMounted, onUnmounted} from 'vue';

defineProps({
    baseUrl: { type: String, required: true },
    episode: { type: Object, required: true },
});

onMounted(() => {
    new Plyr('#player');
});

const extendSessionFunction = setInterval(() => {
    window.axios.put('/extends-session');
}, 1000 * 60 * 5);

onUnmounted(() => {
    clearInterval(extendSessionFunction);
});
</script>
