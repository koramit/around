<script setup>

import SpinnerButton from '../../Components/Controls/SpinnerButton.vue';
import FormInput from '../../Components/Controls/FormInput.vue';
import FormCheckbox from '../../Components/Controls/FormCheckbox.vue';
import {useForm} from '@inertiajs/vue3';
import {computed, nextTick, onMounted, ref} from 'vue';

const form = useForm({
    email: null,
    password: null,
    password_confirmation: null,
    full_name: null,
    name: null,
    tel_no: null,
    agreement_accepted: false,
    remember: true
});

const formComplete = computed(() =>
    form.agreement_accepted &&
    form.email &&
    form.password &&
    form.password_confirmation &&
    form.name &&
    form.full_name &&
    form.tel_no
);

const register = () => {
    form.transform(data => ({
        ...data,
        remember: data.remember ? 'on' : '',
    })).post('/register-with-email', {
        onFinish: () => form.processing = false,
    });
};

const email_input = ref();
onMounted(() => {
    nextTick(() => email_input.value.focus());
});
</script>

<template>
    <div class="flex flex-col justify-center items-center w-full min-h-screen my-6">
        <div class="w-40 h-40 z-10 border-primary border-4 rounded-full">
            <img
                src="../../../images/logo.png"
                alt="around logo"
            >
        </div>
        <div class="mt-4 px-4 py-8 w-80 lg:w-96 bg-white rounded shadow -translate-y-20">
            <span class="block font-semibold text-xl text-accent-darker mt-12 text-center">{{ __('Register') }}</span>
            <div
                class="mt-4"
            />
            <FormInput
                class="mt-2"
                name="email"
                label="email"
                v-model="form.email"
                :error="form.errors.email"
                ref="email_input"
            />
            <FormInput
                class="mt-2"
                type="password"
                name="password"
                :label="__('password')"
                v-model="form.password"
                :error="form.errors.password"
            />
            <FormInput
                class="mt-2"
                type="password"
                name="password_confirmation"
                :label="__('confirm password')"
                v-model="form.password_confirmation"
                :error="form.errors.password_confirmation"
            />
            <FormInput
                class="mt-2"
                name="name"
                :label="__('display name')"
                v-model="form.name"
                :error="form.errors.name"
                :placeholder="__('nickname, alias anything you want')"
            />
            <FormInput
                class="mt-2"
                name="full_name"
                :label="`${__('full name')} (in Thai)`"
                :placeholder="__('title first name last name')"
                v-model="form.full_name"
                :error="form.errors.full_name"
            />
            <FormInput
                class="mt-2"
                type="tel"
                name="tel_no"
                :label="__('tel no')"
                v-model="form.tel_no"
                :error="form.errors.tel_no"
                :placeholder="__('for emergency case only')"
            />
            <!--            <FormInput
                v-if="profile.is_md"
                class="mt-2"
                type="tel"
                name="pln"
                :label="__('license number')"
                v-model="form.pln"
                :error="form.errors.pln"
                placeholder="เลข ว."
            />-->
            <FormCheckbox
                class="mt-2"
                v-model="form.agreement_accepted"
                :label="__('Accept Terms and Policies')"
                :toggler="true"
            />
            <a
                href="/terms-and-policies"
                class="mt-2 block text-accent-darker underline"
                target="_blank"
            >{{ __('Terms and Policies') }}</a>
            <SpinnerButton
                :spin="form.processing"
                class="btn-accent w-full mt-4"
                @click="register"
                :disabled="!formComplete"
            >
                {{ __('REGISTER') }}
            </SpinnerButton>
        </div>
    </div>
</template>

<style scoped>

</style>
