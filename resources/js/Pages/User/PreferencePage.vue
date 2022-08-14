<template>
    <h2 class="form-label">
        Preferences
    </h2>

    <!-- LINE -->
    <h2
        class="mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement"
        id="line-application"
    >
        line application
    </h2>
    <hr class="my-4 border-b border-accent">
    <template v-if="configs.can.link_line && configs.routes.link_line">
        <p class="font-medium italic text-complement">
            Collect email address from LINE account
        </p>
        <p class="mt-2 md:mt-4">
            So we can send you less importance notifications to this email, totally optional.
        </p>
        <FormRadio
            class="mt-4 md:mt-8 md:w-1/2 md:grid grid-cols-2 gap-x-4"
            name="line_email_consent"
            v-model="form.line_email_consent"
            :options="formConfigs.lineEmailConsentOptions"
        />
        <a
            class="flex justify-center items-center gap-x-2 btn btn-accent bg-line w-full md:w-1/2 mt-4 md:mt-8"
            :href="configs.routes.link_line + '?email_consent=' + form.line_email_consent"
        >
            <IconLine class="w-6 h-6 text-white" />
            LINK
        </a>
    </template>
    <span
        v-else-if="configs.routes.link_line && ! configs.friends.line"
        class="px-2 py-1 md:px-4 md:py-2 bg-accent text-white rounded-3xl italic"
    >LINKED</span>
    <a
        class="flex justify-center items-center gap-x-2 btn btn-accent bg-line w-full md:w-1/2 mt-4 md:mt-8"
        :href="configs.routes.add_line"
        target="_blank"
        v-if="configs.can.add_line && configs.routes.add_line"
        ref="lineAddButton"
    >
        <IconLine class="w-6 h-6 text-white" />
        ADD FRIEND
    </a>
    <span
        v-else-if="configs.friends.line"
        class="px-2 py-1 md:px-4 md:py-2 bg-accent text-white rounded-3xl italic"
    >FRIENDED</span>

    <hr class="my-4 border-b border-dashed">

    <!--Notification-->
    <h2
        class="mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement"
        id="notifications"
    >
        notifications
    </h2>
    <hr class="my-4 border-b border-accent">
    <FormCheckbox
        label="Mute"
        name="mute"
        class="my-2 md:my-4"
        :toggler="true"
        v-model="notification.mute"
    />
    <Transition name="slide-fade">
        <div v-if="! notification.mute">
            <h3 class="mt-4 mb-2 md:mt-8 md:mb-4 font-medium text-complement">
                Events
            </h3>
            <section class="p-2 md:p-4 border-l-2 border-accent space-y-2 md:space-y-4">
                <div
                    v-for="group in Object.keys(eventBasedNotifications)"
                    :key="group"
                >
                    <label class="italic">{{ group }} :</label>
                    <FormCheckbox
                        v-for="event in eventBasedNotifications[group]"
                        :key="event.id"
                        :label="event.label"
                        :toggler="true"
                        v-model="event.subscribed"
                        class="mt-2 md:mt-4"
                    />
                </div>
            </section>
            <h3 class="mt-4 mb-2 md:mt-8 md:mb-4 font-medium text-complement">
                Channels
            </h3>
            <FormCheckbox
                class="my-2 md:my-4"
                label="Auto subscribe when I create channel"
                name="auto_subscribe_to_channel"
                v-model="notification.auto_subscribe_to_channel"
                :toggler="true"
            />
            <FormCheckbox
                class="my-2 md:my-4"
                label="Auto unsubscribe when channel inactive"
                name="auto_unsubscribe_to_channel"
                v-model="notification.auto_unsubscribe_to_channel"
                :toggler="true"
            />
            <section
                class="p-2 md:p-4 border-l-2 border-accent space-y-2 md:space-y-4"
                v-if="Object.keys(channelBasedNotifications).length"
            >
                <div
                    v-for="group in Object.keys(channelBasedNotifications)"
                    :key="group"
                >
                    <label class="italic">{{ group }} :</label>
                    <FormCheckbox
                        v-for="event in channelBasedNotifications[group]"
                        :key="event.id"
                        :label="event.label"
                        :toggler="true"
                        v-model="event.subscribed"
                        class="mt-2 md:mt-4"
                    />
                </div>
            </section>
        </div>
    </Transition>
</template>

<script setup>
import debounce from 'lodash/debounce';
import {reactive, ref, watch} from 'vue';
import FormRadio from '../../Components/Controls/FormRadio.vue';
import IconLine from '../../Components/Helpers/Icons/IconLine.vue';
import FormCheckbox from '../../Components/Controls/FormCheckbox.vue';

const props = defineProps({
    preferences: {type: Object, required: true},
    configs: {type: Object, required: true}
});

const form = reactive({
    line_email_consent: 'accepted',
});

const formConfigs = reactive({
    lineEmailConsentOptions: [
        {value: 'accepted', label: 'Yes please'},
        {value: 'declined', label: 'NO, I\'m good'},
    ]
});

const lineAddButton = ref(null);

const notification = reactive({...props.preferences.notification});

const eventBasedNotifications = reactive({...props.configs.event_based_notifications});

const channelBasedNotifications = reactive({...props.configs.subscribed_channels});

watch(
    [() => eventBasedNotifications, () => channelBasedNotifications],
    () => autosaveSubscriptions(),
    {deep: true}
);

const autosaveSubscriptions = debounce(() => {
    let events = Object.keys(eventBasedNotifications).map(key => eventBasedNotifications[key].filter(n => n.subscribed).map(event => event.id))[0] ?? [];
    let channels = Object.keys(channelBasedNotifications).map(key => channelBasedNotifications[key].filter(n => n.subscribed).map(event => event.id))[0] ?? [];

    window.axios
        .patch(props.configs.routes.update, {subscriptions: events.concat(channels)})
        .catch(error => console.log(error));
}, 1500);

watch(
    () => notification,
    () => autosaveNotification(),
    {deep: true}
);

const autosaveNotification = debounce(() => {
    window.axios
        .patch(props.configs.routes.update, {notification: {...notification}})
        .catch(error => console.log(error));
}, 1500);
</script>

<style scoped>
.bg-line {
    background-color: #00b900;
}
.bg-telegram {
    background-color: #54a9eb;
}
</style>
