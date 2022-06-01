<template>
    <nav
        v-if="$page.props.flash.mainMenuLinks.length"
        class="mb-4"
    >
        <template
            v-for="(link, key) in $page.props.flash.mainMenuLinks"
            :key="key"
        >
            <a
                v-if="link.type === '#'"
                :href="link.route"
                class="flex items-center group py-2 outline-none truncate"
                @click.prevent="smoothScroll(link.route)"
            >
                <IconVector
                    :name="link.icon"
                    class="w-4 h-4 transition-colors duration-200 ease-linear text-primary group-hover:text-accent"
                />
                <div
                    class="ml-2 duration-200 ease-linear text-primary group-hover:text-accent"
                    v-if="!zenMode"
                >
                    {{ link.label }}
                </div>
            </a>
            <a
                v-else-if="link.type === 'a'"
                class="flex items-center group py-2 outline-none truncate"
                :href="link.route"
            >
                <IconVector
                    :name="link.icon"
                    class="w-4 h-4 transition-colors duration-200 ease-linear"
                    :class="isUrl(link.route) ? 'text-white' : 'text-primary group-hover:text-accent'"
                />
                <div
                    class="ml-2 transition-colors duration-200 ease-linear"
                    :class="isUrl(link.route) ? 'text-white border-b-2' : 'text-primary group-hover:text-accent'"
                    v-if="!zenMode"
                >
                    {{ link.label }}
                </div>
            </a>
            <Link
                class="flex items-center group py-2 outline-none truncate"
                :href="link.route"
                v-else
            >
                <IconVector
                    :name="link.icon"
                    class="w-4 h-4 transition-colors duration-200 ease-linear"
                    :class="isUrl(link.route) ? 'text-white' : 'text-primary group-hover:text-accent'"
                />
                <div
                    class="ml-2 transition-colors duration-200 ease-linear"
                    :class="isUrl(link.route) ? 'text-white border-b-2' : 'text-primary group-hover:text-accent'"
                    v-if="!zenMode"
                >
                    {{ link.label }}
                </div>
            </Link>
        </template>
    </nav>
</template>

<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import IconVector from './IconVector.vue';
const props = defineProps({
    scrollMode: {type:String, default: 'desktop'},
    zenMode: { type: Boolean }
});
const isUrl = (url) => {
    return (location.origin + location.pathname) === url;
};
const smoothScroll = (href) => {
    const target = document.querySelector(href);
    if (target === undefined) {
        return;
    }
    if (props.scrollMode === 'mobile') {
        window.scroll({
            top: target.getBoundingClientRect().top -
                 document.querySelector('header').offsetHeight * 2,
            left: 0,
            behavior: 'smooth'
        });
    } else {
        document.querySelector(href).scrollIntoView({
            behavior: 'smooth'
        });
    }
};
</script>