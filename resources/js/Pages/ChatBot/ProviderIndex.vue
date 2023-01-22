<template>
    <div class="grid md:grid-cols-2 gap-4 md:gap-8">
        <!--left container-->
        <div>
            <button
                class="btn btn-accent w-full md:w-1/2 uppercase"
                @click="addProvider"
            >
                add provider
            </button>
            <!--providers-->
            <div
                v-for="provider in providers"
                :key="provider.id"
                class="mt-2 md:mt-4 flex items-center space-x-2 md:space-x-4 p-2 md:p-4 bg-white shadow"
                :class="{'border-r-4 border-complement': provider.id === selectedProvider.id}"
            >
                <span class="px-2 py-1 md:px-4 md:py-2 rounded-3xl bg-line-app text-white font-semibold uppercase">{{ provider.platform }}</span>
                <span>{{ provider.name }}</span>
                <button
                    class="underline"
                    @click="editProvider(provider)"
                >
                    edit
                </button>
                <button
                    class="underline"
                    @click="addBot(provider)"
                >
                    add bot
                </button>
            </div>
            <!--provider's bots-->
            <Transition
                name="slide-fade"
            >
                <div
                    class="mt-2 md:mt-4"
                    v-if="bots.length"
                >
                    <div
                        v-for="bot in bots"
                        :key="bot.id"
                        class="flex items-center space-x-2 md:space-x-4 p-2 md:p-4 bg-primary-darker shadow"
                        :class="{'border-r-4 border-accent': bot.id === selectedBot.id}"
                    >
                        <span>{{ bot.name }}</span>
                        <button
                            class="underline"
                            @click="editBot(bot)"
                        >
                            edit
                        </button>
                    </div>
                </div>
            </transition>
        </div>
        <!--right container-->
        <div>
            <!--provider form-->
            <Transition
                name="slide-fade"
            >
                <div
                    v-if="showProviderForm"
                    class="p-2 md:p-4 bg-primary-darker rounded-xl"
                >
                    <h3 class="uppercase text-complement font-semibold border-b-2 border-primary border-dashed pb-2 md:pb-4 mb-2 md:mb-4">
                        {{ formMode }} provider
                    </h3>
                    <FormInput
                        v-for="field in Object.keys(lineProviderForm)"
                        class="mt-2 md:mt-4"
                        :label="field.replaceAll('_', ' ')"
                        :key="field"
                        :name="field"
                        v-model="lineProviderForm[field]"
                    />
                    <div class="mt-2 md:mt-4 flex items-center space-x-2 md:space-x-4">
                        <div class="flex items-center">
                            <span class="form-label !mb-0">terms</span>
                            <CopyToClipboardButton :text="configs.routes.terms" />
                        </div>
                        <div class="flex items-center">
                            <span class="form-label !mb-0">detail</span>
                            <CopyToClipboardButton :text="`${lineProviderForm.name} ${configs.channel_detail}`" />
                        </div>
                        <div
                            class="flex items-center"
                            v-if="selectedProvider?.routes?.callbacks"
                        >
                            <span class="form-label !mb-0">callbacks</span>
                            <CopyToClipboardButton :text="selectedProvider.routes.callbacks" />
                        </div>
                    </div>
                    <SpinnerButton
                        :spin="spin"
                        class="mt-2 md:mt-4 btn btn-accent uppercase w-full"
                        @click="formProviderClick"
                    >
                        {{ formMode === 'edit' ? 'update' : 'add' }}
                    </SpinnerButton>
                </div>
            </Transition>
            <!--bot form-->
            <Transition
                name="slide-fade"
            >
                <div
                    v-if="showBotForm"
                    class="p-2 md:p-4 bg-primary-darker rounded-xl"
                >
                    <h3 class="uppercase text-complement font-semibold border-b-2 border-primary border-dashed pb-2 md:pb-4 mb-2 md:mb-4">
                        {{ formMode }} bot
                    </h3>
                    <FormInput
                        v-for="field in Object.keys(lineBotForm)"
                        class="mt-2 md:mt-4"
                        :label="field.replaceAll('_', ' ')"
                        :key="field"
                        :name="field"
                        v-model="lineBotForm[field]"
                    />
                    <div class="mt-2 md:mt-4 flex items-center space-x-2 md:space-x-4">
                        <div class="flex items-center">
                            <span class="form-label !mb-0">terms</span>
                            <CopyToClipboardButton :text="configs.routes.terms" />
                        </div>
                        <div class="flex items-center">
                            <span class="form-label !mb-0">detail</span>
                            <CopyToClipboardButton :text="`${lineBotForm.name} ${configs.channel_detail}`" />
                        </div>
                        <div class="flex items-center">
                            <span class="form-label !mb-0">webhook</span>
                            <CopyToClipboardButton :text="`${configs.routes.base_webhooks}${lineBotForm.secret}`" />
                        </div>
                    </div>
                    <SpinnerButton
                        :spin="spin"
                        class="mt-2 md:mt-4 btn btn-accent uppercase w-full"
                        @click="formBotClick"
                    >
                        {{ formMode === 'edit' ? 'update' : 'add' }}
                    </SpinnerButton>
                </div>
            </Transition>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref} from 'vue';
import FormInput from '../../Components/Controls/FormInput.vue';
import SpinnerButton from '../../Components/Controls/SpinnerButton.vue';
import { router } from '@inertiajs/vue3';
import CopyToClipboardButton from '../../Components/Controls/CopyToClipboardButton.vue';

const props = defineProps({
    providers: {type: Array, required: true},
    configs: {type: Object, required: true},
});

const showProviderForm = ref(false);
const lineProviderForm = reactive({
    name: null,
    provider_id: null,
    channel_id: null,
    channel_secret: null,
    access_token_url: 'https://api.line.me/oauth2/v2.1/token',
    auth_url: 'https://access.line.me/oauth2/v2.1/authorize?response_type=code',
    profile_url: 'https://api.line.me/v2/profile',
});

const showBotForm = ref(false);
const lineBotForm = reactive({
    provider: null,
    name: null,
    channel_id: null,
    secret: null,
    basic_id: null,
    token: null,
    add_friend_base_url: 'https://line.me/R/ti/p/',
});

const addProvider = () => {
    formMode.value = 'add';
    showProviderForm.value = false;
    showBotForm.value = false;
    bots.value = [];
    selectedProvider.id = null;
    lineProviderForm.name = null;
    lineProviderForm.provider_id = null;
    lineProviderForm.channel_id = null;
    lineProviderForm.channel_secret = null;
    lineProviderForm.access_token_url = 'https://api.line.me/oauth2/v2.1/token';
    lineProviderForm.auth_url = 'https://access.line.me/oauth2/v2.1/authorize?response_type=code';
    lineProviderForm.profile_url = 'https://api.line.me/v2/profile';
    setTimeout(() => showProviderForm.value = true, 300);
};

const bots = ref([]);
const selectedProvider = reactive({});
const editProvider = (provider) => {
    Object.keys(provider).map(k => selectedProvider[k] = provider[k]);
    formMode.value = 'edit';
    showProviderForm.value = false;
    showBotForm.value = false;
    window.axios
        .get(provider.routes.show)
        .then(res => {
            lineProviderForm.name = res.data.name;
            lineProviderForm.provider_id = res.data.configs.provider_id;
            lineProviderForm.channel_id = res.data.configs.channel_id;
            lineProviderForm.channel_secret = res.data.configs.channel_secret;
            lineProviderForm.access_token_url = res.data.configs.access_token_url;
            lineProviderForm.auth_url = res.data.configs.auth_url;
            lineProviderForm.profile_url = res.data.configs.profile_url;
            selectedProvider.routes.callbacks = res.data.routes.callbacks;
            bots.value = [...res.data.bots];

            setTimeout(() => showProviderForm.value = true, 300);
        })
        .catch(error => console.log(error));
};

const formProviderClick = () => {
    spin.value = true;
    if (formMode.value === 'add') {
        router.post(props.configs.routes.providers_store, lineProviderForm, {
            onFinish: () => { spin.value = false; },
            onSuccess: () => {
                formMode.value = 'edit';
            },
        });
    } else if (formMode.value === 'edit') {
        router.patch(selectedProvider.routes.update, lineProviderForm, {
            preserveState: true,
            onFinish: () => { spin.value = false; },
        });
    } else {
        spin.value = false;
    }
};

const addBot = (provider) => {
    Object.keys(provider).map(k => selectedProvider[k] = provider[k]);
    formMode.value = 'add';
    showProviderForm.value = false;
    showBotForm.value = false;
    lineBotForm.provider = provider.name;
    lineBotForm.name = 'Around Bot NS0';
    lineBotForm.channel_id = null;
    lineBotForm.secret = null;
    lineBotForm.basic_id = null;
    lineBotForm.token = null;
    lineBotForm.add_friend_base_url = 'https://line.me/R/ti/p/';
    setTimeout(() => showBotForm.value = true, 300);
};

const selectedBot = reactive({});
const editBot = (bot) => {
    formMode.value = 'edit';
    showProviderForm.value = false;
    showBotForm.value = false;
    window.axios
        .get(bot.routes.show)
        .then(res => {
            lineBotForm.provider = selectedProvider.name;
            lineBotForm.name = res.data.name;
            lineBotForm.channel_id = res.data.channel_id;
            lineBotForm.secret = res.data.secret;
            lineBotForm.basic_id = res.data.basic_id;
            lineBotForm.token = res.data.token;
            lineBotForm.add_friend_base_url = res.data.add_friend_base_url;
            Object.keys(res.data).map(k => selectedBot[k] = res.data[k]);

            setTimeout(() => showBotForm.value = true, 300);
        })
        .catch(error => {
            console.log(error);
        });
};

const formMode = ref('edit');
const spin = ref(false);
const formBotClick = () => {
    spin.value = true;
    if (formMode.value === 'add') {
        router.post(selectedProvider.routes.bots_store, lineBotForm, {
            onFinish: () => { spin.value = false; },
            onSuccess: () => {
                formMode.value = 'edit';
            },
        });
    } else if (formMode.value === 'edit') {
        router.patch(selectedBot.routes.update, lineBotForm, {
            preserveState: true,
            onFinish: () => { spin.value = false; },
        });
    } else {
        spin.value = false;
    }
};
</script>
