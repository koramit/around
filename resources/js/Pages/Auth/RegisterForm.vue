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
                name="name"
                :label="__('display name')"
                v-model="form.name"
                :error="form.errors.name"
                :placeholder="__('nickname, alias anything you want')"
                ref="name_input"
            />
            <FormInput
                class="mt-2"
                name="full_name"
                :label="__('full name')"
                :placeholder="__('title first name last name')"
                v-model="form.full_name"
                :error="form.errors.full_name"
                :readonly="profile.org_id !== undefined"
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
            <FormInput
                v-if="profile.is_md"
                class="mt-2"
                type="tel"
                name="pln"
                :label="__('license number')"
                v-model="form.pln"
                :error="form.errors.pln"
                placeholder="เลข ว."
            />
            <FormCheckbox
                class="mt-2"
                v-model="form.agreement_accepted"
                :label="__('Accept Terms and Policies')"
                :toggler="true"
            />
            <a
                :href="routes.terms"
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

<script setup>
import { useForm } from '@inertiajs/vue3';
import FormCheckbox from '../../Components/Controls/FormCheckbox.vue';
import FormInput from '../../Components/Controls/FormInput.vue';
import SpinnerButton from '../../Components/Controls/SpinnerButton.vue';
import { computed, nextTick, onMounted, ref } from 'vue';

const props = defineProps({
    // eslint-disable-next-line vue/require-default-prop
    layout: null,
    profile: { type: Object, required: true },
    routes: { type: Object, required: true },
});


const name_input = ref();
onMounted(() => {
    nextTick(() => name_input.value.focus());
});

const form = useForm({
    login: props.profile.login,
    full_name: props.profile.name,
    org_id: props.profile.org_id,
    division: props.profile.division_name,
    position: props.profile.position_name,
    password_expires_in_days: props.profile.password_expires_in_days,
    remark: props.profile.remark,
    name: null,
    tel_no: null,
    pln: null,
    is_md: props.profile.is_md,
    agreement_accepted: false,
    remember: true
});

const formComplete = computed(() =>
    form.agreement_accepted &&
    form.name &&
    form.full_name &&
    form.tel_no
);

const register = () => {
    form.transform(data => ({
        ...data,
        remember: data.remember ? 'on' : '',
    })).post(props.routes.registerStore, {
        onFinish: () => form.processing = false,
    });
};
</script>
