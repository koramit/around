<template>
    <nav
        v-if="$page.props.flash.mainMenuLinks.length"
        class="mb-4"
    >
        <template
            v-for="(link, key) in $page.props.flash.mainMenuLinks.filter(l => l.can)"
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
import { Link } from '@inertiajs/vue3';
import IconVector from '../../Components/Helpers/IconVector.vue';
import { useInPageLinkHelpers } from '../../functions/useInPageLinkHelpers.js';
defineProps({
    zenMode: { type: Boolean }
});
const { isUrl, smoothScroll } = useInPageLinkHelpers();
</script>
