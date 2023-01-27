<template>
    <div class="flex flex-col justify-center items-center w-full min-h-screen">
        <div class="w-40 h-40 z-10 border-primary border-4 rounded-full floating-logo">
            <img
                src="../../../images/logo.png"
                alt="around logo"
            >
        </div>
        <div class="mt-4 px-4 py-8 w-80 bg-white rounded shadow -translate-y-20">
            <span class="block text-xl text-accent mt-12 text-center">around 🤲🏻 about 🙌🏻 arrange</span>
            <FormInput
                class="mt-8"
                :label="__('login')"
                name="login"
                v-model="form.login"
                :error="form.errors.login"
                ref="loginInput"
            />
            <FormInput
                class="mt-2"
                type="password"
                :label="__('password')"
                name="password"
                v-model="form.password"
                :error="form.errors.password"
                @keydown.enter="login"
            />
            <SpinnerButton
                :spin="form.processing"
                class="btn-accent w-full mt-8"
                @click="login"
            >
                {{ __('ENTER') }}
            </SpinnerButton>

            <a
                class="flex justify-center items-center gap-x-2 btn btn-accent bg-line w-full mt-8"
                :href="links.line_login"
                v-if="links.line_login"
            >
                <IconLine class="w-6 h-6 text-white" />
                LOGIN
            </a>
            <p class="text-sm text-red-400 mt-2">
                {{ $page.props.errors.notice }}
            </p>
            <!-- <a href="/locale/en">ENG</a>
            <a href="/locale/th">ไทย</a> -->
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import {nextTick, onMounted, onUnmounted, ref} from 'vue';
import FormInput from '../../Components/Controls/FormInput.vue';
import SpinnerButton from '../../Components/Controls/SpinnerButton.vue';
import IconLine from '../../Components/Helpers/Icons/IconLine.vue';

const props = defineProps({
    // eslint-disable-next-line vue/require-default-prop
    layout: null,
    links: { type: Object, required: true }
});
const loginInput = ref(null);

let heartbeat;
const sessionLifetimeMilliseconds = parseInt(document.querySelector('meta[name=session-lifetime-seconds]').content) * 1000;
onMounted(() => {
    nextTick(() => {
        loginInput.value.focus();
    });

    heartbeat = setInterval(function () {
        window.axios
            .post(props.links.login_store, {foo: 'bar'})
            .catch(function (error) {
                if (error.response.status === 419) {
                    window.location.reload();
                }
            });
    }, sessionLifetimeMilliseconds * 0.95);
});

onUnmounted(() => clearInterval(heartbeat));
const form = useForm({
    login: null,
    password: null,
    remember: true
});

const login = () => {
    form.transform(data => ({
        login: data.login.toLowerCase(),
        password: data.password,
        remember: data.remember ? 'on' : '',
    })).post(props.links.login_store, {
        replace: true,
        onFinish: () => {
            form.processing = false;
        },
    });
};
</script>

<style scoped>
    .bg-line {
        background-color: #00b900;
    }
    .bg-telegram {
        background-color: #54a9eb;
    }
    .floating-logo {
        transform: translatey(0px);
        animation: float 6s ease-in-out infinite;
    }
    @keyframes float {
        0% {
            transform: translatey(0px);
        }
        50% {
            transform: translatey(-20px);
        }
        100% {
            transform: translatey(0px);
        }
    }
</style>
