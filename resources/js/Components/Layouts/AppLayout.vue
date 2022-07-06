<template>
    <Head>
        <title>{{ $page.props.flash.title }}</title>
    </Head>
    <!-- main contailner, flex makes its childs extend full h -->
    <div class="md:h-screen md:flex md:flex-col">
        <!-- this is navbar, with no shrink (fixed width) -->
        <header class="md:flex md:shrink-0 sticky top-0 z-30">
            <!-- left navbar on desktop and full bar on mobile -->
            <div
                class="bg-complement-darker text-white md:shrink-0 px-4 py-2 flex items-center justify-between md:justify-center"
                :class="{
                    'md:w-56 xl:w-64': !zenMode,
                    'md:w-12': zenMode
                }"
            >
                <!-- zen mode switch -->
                <button
                    class="hidden md:inline-block font-bold text-lg md:text-2xl font-lobster"
                    @click="zenMode = !zenMode"
                >
                    <span v-if="!zenMode">@round</span>
                    <IconHamberger
                        v-else
                        class="w-6 h-6"
                    />
                </button>
                <!-- title display on mobile -->
                <div class="text-primary flex text-sm md:hidden">
                    <span>{{ $page.props.flash.title }}</span>
                    <CopyToClipboardButton
                        v-if="$page.props.flash.hn"
                        :text="$page.props.flash.hn"
                    />
                </div>
                <!-- hamberger menu on mobile -->
                <button
                    class="md:hidden transition-all duration-300 ease-out transform"
                    :class="{ 'scale-y-90 text-primary-darker' : mobileMenuVisible }"
                    @click="mobileMenuVisible = !mobileMenuVisible"
                >
                    <IconHamberger class="w-6 h-6" />
                </button>
            </div>
            <!-- right navbar on desktop -->
            <div
                class="hidden md:flex w-full font-semibold text-complement-darker bg-primary-darker border-b sticky top-0 z-30 p-4 md:py-0 justify-between items-center"
                :class="{
                    'md:px-12 lg:px-24': !zenMode,
                    'md:px-4 lg:px-8 xl:px-12': zenMode,
                }"
            >
                <!-- title display on desktop -->
                <div class="mr-4 w-full flex justify-between items-center">
                    <div class="flex">
                        <span>{{ $page.props.flash.title }}</span>
                        <CopyToClipboardButton
                            v-if="$page.props.flash.hn"
                            :text="$page.props.flash.hn"
                        />
                    </div>
                    <div class="text-complement">
                        <button
                            class="w-6 h-6 rounded-full transition-colors duration-200 ease-in hover:bg-white hover:text-accent-darker mr-2"
                            v-text="'a'"
                            @click="scaleFont('down')"
                        />
                        <button
                            class="w-6 h-6 rounded-full transition-colors duration-200 ease-in hover:bg-white hover:text-accent-darker font-semibold mr-2"
                            v-text="'A'"
                            @click="scaleFont('up')"
                        />
                    </div>
                </div>
                <!-- username and menu -->
                <DropdownList>
                    <template #default>
                        <div class="cursor-pointer select-none group">
                            <div class="flex items-center group-hover:text-accent-darker focus:text-accent-darker mr-1 whitespace-nowrap transition-colors duration-200 ease-out">
                                {{ $page.props.user.name }}
                                <IconChevronCircleDown class="w-4 h-4 ml-1" />
                            </div>
                        </div>
                    </template>
                    <template #dropdown>
                        <div class="mt-2 py-0 overflow-hidden shadow-xl bg-complement text-white cursor-pointer rounded text-sm">
                            <Link
                                class="block w-full text-left px-6 py-2 hover:bg-complement-darker hover:text-primary transition-colors duration-200 ease-out"
                                :href="$page.props.routePreferences"
                            >
                                {{ __('Preferences') }}
                            </Link>
                            <Link
                                class="block w-full text-left px-6 py-2 hover:bg-complement-darker hover:text-primary transition-colors duration-200 ease-out"
                                :href="$page.props.routeLogout"
                                method="delete"
                                as="button"
                                type="button"
                            >
                                {{ __('Logout') }}
                            </Link>
                        </div>
                    </template>
                </DropdownList>
            </div>
            <!-- menu on mobile -->
            <div
                class="h-4/5 mx-1 md:hidden block fixed bottom-0 inset-x-0 overflow-y-scroll text-primary bg-complement rounded-tl-xl rounded-tr-xl transition-transform  duration-300 ease-in-out"
                :class="{ 'translate-y-full': !mobileMenuVisible }"
            >
                <div class="p-4">
                    <!-- username and menu -->
                    <div
                        class="flex flex-col text-center justify-center"
                        @click="mobileMenuVisible = false"
                    >
                        <span class="inline-block py-1 text-white">{{ $page.props.user.name }}</span>
                        <Link
                            class="block py-1"
                            :href="$page.props.routePreferences"
                        >
                            {{ __('Preferences') }}
                        </Link>
                        <Link
                            class="block py-1"
                            :href="$page.props.routeLogout"
                            method="delete"
                            as="button"
                            type="button"
                        >
                            {{ __('Logout') }}
                        </Link>
                    </div>
                    <hr class="my-4">
                    <MainMenu @click="mobileMenuVisible = false" />
                    <ActionMenu @action-clicked="actionClicked" />
                </div>
            </div>
        </header>
        <!-- this is content -->
        <main class="md:flex md:flex-grow md:overflow-hidden">
            <!-- this is sidebar menu on desktop -->
            <aside
                class="hidden md:block bg-complement shrink-0 overflow-y-auto"
                :class="{
                    'w-56 xl:w-64 px-6 py-12': !zenMode,
                    'w-12 md:p-4': zenMode,
                }"
            >
                <MainMenu :zen-mode="zenMode" />
                <ActionMenu
                    :zen-mode="zenMode"
                    @action-clicked="actionClicked"
                />
            </aside>
            <!-- this is main page -->
            <div
                class="w-full p-4 md:overflow-y-auto sm:p-8"
                :class="{
                    'md:p-12 lg:px-24': !zenMode && !$page.props.flash.breadcrumbs.length,
                    'md:p-12 md:pt-0 md:mt-12 lg:px-24': !zenMode && $page.props.flash.breadcrumbs.length,
                    'md:p-4 lg:px-8 xl:px-12': zenMode && !$page.props.flash.breadcrumbs.length,
                    'md:p-4 md:pt-0 md:mt-4 lg:px-8 xl:px-12': zenMode && $page.props.flash.breadcrumbs.length,
                }"
                scroll-region
            >
                <!-- breadcrumbs -->
                <nav
                    class="flex mb-4 md:mb-0 py-2 md:pb-8 bg-primary z-10 sticky top-10 md:top-0"
                    v-if="$page.props.flash.breadcrumbs.length"
                >
                    <li
                        class="list-none text-accent text-sm md:text-base italic"
                        v-for="(link, key) in $page.props.flash.breadcrumbs"
                        :key="key"
                    >
                        <Link
                            :href="link.route"
                            class="whitespace-nowrap"
                        >
                            {{ link.label }}
                        </Link>
                        <span
                            v-if="key !== ($page.props.flash.breadcrumbs.length - 1)"
                            class="px-4 text-primary-darker font-semibold"
                        >/</span>
                    </li>
                </nav>
                <nav
                    v-else-if="$page.props.flash.navs.length"
                    class="md:w-1/2 text-accent form-label"
                >
                    <ul class="flex justify-between">
                        <li
                            v-for="link in $page.props.flash.navs"
                            :key="link.route"
                        >
                            <Link
                                :href="link.route"
                                class="block py-1 px-2 md:py-2 md:px-4 rounded-3xl bg-primary transition-colors duration-300"
                                preserve-state
                                :class="{
                                    'bg-accent text-white italic': isUrl(link.route),
                                    'hover:bg-primary-darker': !isUrl(link.route),
                                }"
                            >
                                {{ link.label }}
                            </Link>
                        </li>
                    </ul>
                </nav>

                <!-- form errors -->
                <div v-if="Object.keys($page.props.errors).length">
                    <AlertMessage
                        type="danger"
                        title="Error"
                        message="Please verify to proceed"
                    />
                    <div
                        v-if="Object.keys($page.props.errors).length"
                        class="border-2 border-red-400 rounded p-2 md:p-4 test-sm text-red-700 my-4 space-y-2"
                    >
                        <a
                            :href="`#${name}`"
                            class="block"
                            v-for="(name, key) in Object.keys($page.props.errors)"
                            :key="key"
                            @click.prevent="smoothScroll(`#${name}`)"
                        >{{ $page.props.errors[name] }}</a>
                    </div>
                </div>

                <AlertMessage
                    v-if="$page.props.flash.message"
                    :type="$page.props.flash.message.type"
                    :title="$page.props.flash.message.title"
                    :message="$page.props.flash.message.message"
                    class="mb-4 md:mb-8"
                />

                <slot v-if="$page.props.shouldTransitionPage" />

                <Transition
                    v-else
                    name="page-fade"
                    mode="out-in"
                    appear
                >
                    <div :key="$page.url">
                        <slot />
                    </div>
                </Transition>
            </div>
        </main>
    </div>
</template>

<script setup>
import { Head, Link, usePage } from '@inertiajs/inertia-vue3';
import DropdownList from '@/Components/Helpers/DropdownList.vue';
import MainMenu from '@/Components/Helpers/MainMenu.vue';
import ActionMenu from '@/Components/Helpers/ActionMenu.vue';
import { pageRoutines } from '@/functions/pageRoutines.js';
import { nextTick, onMounted, ref } from 'vue';
import IconHamberger from '@/Components/Helpers/Icons/IconHamberger.vue';
import IconChevronCircleDown from '@/Components/Helpers/Icons/IconChevronCircleDown.vue';
import { useInPageLinkHelpers } from '../../functions/useInPageLinkHelpers.js';
import AlertMessage from '../Helpers/AlertMessage.vue';
import CopyToClipboardButton from '../Controls/CopyToClipboardButton.vue';

pageRoutines();
const mobileMenuVisible = ref(false);
const zenMode = ref(usePage().props.value.user.configs.appearance?.zenMode ?? false);

const actionClicked = (action) => {
    mobileMenuVisible.value = false;
    nextTick(() => {
        setTimeout(() => {
            usePage().props.value.event.payload = action;
            usePage().props.value.event.name = 'action-clicked';
            usePage().props.value.event.fire = + new Date();
        }, 300); // equal to animate duration
    });
};

let fontScaleIndex = 3;
let fontScales = [67, 80, 90, 100];
const scaleFont = (mode) => {
    fontScaleIndex = mode === 'up' ? (fontScaleIndex+1) : (fontScaleIndex-1);
    if (fontScaleIndex > (fontScales.length - 1)) {
        fontScaleIndex = fontScales.length - 1;
    } else if (fontScaleIndex < 0) {
        fontScaleIndex = 0;
    }

    document.querySelector('html').style.fontSize = fontScales[fontScaleIndex] + '%';
};
onMounted(() => {
    let vw = Math.max(document.documentElement.clientWidth || 0, window.innerWidth || 0);
    if (vw >= 768) { // md breakpoint
        document.querySelector('html').style.fontSize = fontScales[fontScaleIndex] + '%';
    }
});

const { isUrl, smoothScroll } = useInPageLinkHelpers();
</script>