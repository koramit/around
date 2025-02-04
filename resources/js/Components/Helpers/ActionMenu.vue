<template>
    <div
        v-if="$page.props.flash.actionMenu.length"
        class="mb-4"
    >
        <template
            v-for="(action, key) in $page.props.flash.actionMenu.filter(a => a !== null && a.can)"
            :key="key"
        >
            <button
                v-if="action.as === 'button'"
                class="flex items-center group py-2 text-primary"
                @click="buttonClicked(action)"
            >
                <IconVector
                    :name="action.icon"
                    class="w-4 h-4 group-hover:text-accent transition-colors duration-200 ease-in-out"
                />
                <span
                    class="ml-2 group-hover:text-accent transition-colors duration-200 ease-in-out truncate"
                    v-if="!zenMode"
                >
                    {{ action.label }}
                </span>
            </button>
            <Link
                v-else-if="action.as === 'link'"
                class="flex items-center group py-2 outline-none truncate text-primary"
                :href="action.route"
                @click="$emit('hide-mobile-menu')"
            >
                <IconVector
                    :name="action.icon"
                    class="w-4 h-4 group-hover:text-accent transition-colors duration-200 ease-in-out"
                />
                <div
                    class="ml-2 group-hover:text-accent transition-colors duration-200 ease-in-out"
                    v-if="!zenMode"
                >
                    {{ action.label }}
                </div>
            </Link>
            <a
                v-else-if="action.as === 'a'"
                class="flex items-center group py-2 outline-none truncate text-primary"
                :href="action.route"
                @click="$emit('hide-mobile-menu')"
            >
                <IconVector
                    :name="action.icon"
                    class="w-4 h-4 group-hover:text-accent transition-colors duration-200 ease-in-out"
                />
                <div
                    class="ml-2 group-hover:text-accent transition-colors duration-200 ease-in-out"
                    v-if="!zenMode"
                >
                    {{ action.label }}
                </div>
            </a>
            <a
                v-else-if="action.as === 'tab'"
                class="flex items-center group py-2 outline-none truncate text-primary"
                :href="action.route"
                target="_blank"
                @click="$emit('hide-mobile-menu')"
            >
                <IconVector
                    :name="action.icon"
                    class="w-4 h-4 group-hover:text-accent transition-colors duration-200 ease-in-out"
                />
                <div
                    class="ml-2 group-hover:text-accent transition-colors duration-200 ease-in-out"
                    v-if="!zenMode"
                >
                    {{ action.label }}
                </div>
            </a>
        </template>
    </div>
</template>
<script setup>
import {Link, usePage} from '@inertiajs/vue3';
import {useActionStore} from '../../functions/useActionStore.js';
import IconVector from './IconVector.vue';

const emits = defineEmits(['hide-mobile-menu']);
defineProps({
    zenMode: { type: Boolean }
});
const preDefinedActions = ['subscribe-clicked', 'set-home-page-clicked', 'bookmark-clicked'];
const {setActionStore, resetActionStore} = useActionStore();

const buttonClicked = (action) => {
    if (! preDefinedActions.includes(action.name)) {
        resetActionStore();
        setActionStore(action);
        emits('hide-mobile-menu');
        return;
    }

    switch (action.name) {
    case 'subscribe-clicked':
        window.axios
            .post(action.config.route, action.config)
            .then(res => {
                let index = usePage().props.flash.actionMenu.findIndex(a => a.name === 'subscribe-clicked');
                usePage().props.flash.actionMenu[index].label = res.data.label;
                usePage().props.flash.actionMenu[index].icon = res.data.icon;
                usePage().props.flash.actionMenu[index].config.subscribed = !usePage().props.flash.actionMenu[index].config.subscribed;
            });
        break;
    case 'set-home-page-clicked':
        window.axios
            .patch(action.config.route, {home_page: action.config.route_name})
            .then(() => {
                let index = usePage().props.flash.actionMenu.findIndex(a => a.name === 'set-home-page-clicked');
                usePage().props.flash.actionMenu.splice(index, 1);
            });
        break;
    /*case 'bookmark-clicked':
        emits['bookmark-clicked']();
        break;*/
    default:
        return;
    }
};
</script>
