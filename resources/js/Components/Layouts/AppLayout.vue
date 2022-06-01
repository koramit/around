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
                    <svg
                        v-else
                        class="w-6 h-6"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512"
                    ><path
                        fill="currentColor"
                        d="M464 256H48a48 48 0 0 0 0 96h416a48 48 0 0 0 0-96zm16 128H32a16 16 0 0 0-16 16v16a64 64 0 0 0 64 64h352a64 64 0 0 0 64-64v-16a16 16 0 0 0-16-16zM58.64 224h394.72c34.57 0 54.62-43.9 34.82-75.88C448 83.2 359.55 32.1 256 32c-103.54.1-192 51.2-232.18 116.11C4 180.09 24.07 224 58.64 224zM384 112a16 16 0 1 1-16 16 16 16 0 0 1 16-16zM256 80a16 16 0 1 1-16 16 16 16 0 0 1 16-16zm-128 32a16 16 0 1 1-16 16 16 16 0 0 1 16-16z"
                    /></svg>
                    <!-- {{ zenMode ? '🍔':'@round' }} -->
                </button>
                <!-- title display on mobile -->
                <div class="text-primary text-sm md:hidden">
                    {{ $page.props.flash.title }}
                </div>
                <!-- hamberger menu on mobile -->
                <button
                    class="md:hidden transition-all duration-300 ease-out transform"
                    :class="{ 'scale-y-90 text-primary-darker' : mobileMenuVisible }"
                    @click="mobileMenuVisible = !mobileMenuVisible"
                >
                    <svg
                        class="w-6 h-6"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512"
                    ><path
                        fill="currentColor"
                        d="M464 256H48a48 48 0 0 0 0 96h416a48 48 0 0 0 0-96zm16 128H32a16 16 0 0 0-16 16v16a64 64 0 0 0 64 64h352a64 64 0 0 0 64-64v-16a16 16 0 0 0-16-16zM58.64 224h394.72c34.57 0 54.62-43.9 34.82-75.88C448 83.2 359.55 32.1 256 32c-103.54.1-192 51.2-232.18 116.11C4 180.09 24.07 224 58.64 224zM384 112a16 16 0 1 1-16 16 16 16 0 0 1 16-16zM256 80a16 16 0 1 1-16 16 16 16 0 0 1 16-16zm-128 32a16 16 0 1 1-16 16 16 16 0 0 1 16-16z"
                    /></svg>
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
                    <div>{{ $page.props.flash.title }}</div>
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
                                <svg
                                    class="w-4 h-4 ml-1"
                                    viewBox="0 0 512 512"
                                ><path
                                    fill="currentColor"
                                    d="M504 256c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zM273 369.9l135.5-135.5c9.4-9.4 9.4-24.6 0-33.9l-17-17c-9.4-9.4-24.6-9.4-33.9 0L256 285.1 154.4 183.5c-9.4-9.4-24.6-9.4-33.9 0l-17 17c-9.4 9.4-9.4 24.6 0 33.9L239 369.9c9.4 9.4 24.6 9.4 34 0z"
                                /></svg>
                            </div>
                        </div>
                    </template>
                    <template #dropdown>
                        <div class="mt-2 py-2 shadow-xl bg-complement text-white cursor-pointer rounded text-sm">
                            <Link
                                class="block px-6 py-2 hover:bg-complement-darker hover:text-primary"
                                :href="$page.props.routePreferences"
                            >
                                {{ __('Preferences') }}
                            </Link>
                            <Link
                                class="w-full font-semibold text-left px-6 py-2 hover:bg-complement-darker hover:text-primary"
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
                    <MainMenu
                        @click="mobileMenuVisible = false"
                        scroll-mode="mobile"
                    />
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
                    'md:py-12 md:px-12 lg:px-24': !zenMode,
                    'md:p-4 lg:px-8 xl:px-12': zenMode
                }"
                scroll-region
            >
                <!-- <flash-messages /> -->
                <slot />
            </div>
        </main>
    </div>
</template>

<script setup>
import { Head, Link, usePage } from '@inertiajs/inertia-vue3';
import DropdownList from '../Helpers/DropdownList.vue';
import MainMenu from '../Helpers/MainMenu.vue';
import ActionMenu from '../Helpers/ActionMenu.vue';
import { pageRoutines } from '../../functions/pageRoutines';
import { nextTick, onMounted, ref } from 'vue';

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
</script>