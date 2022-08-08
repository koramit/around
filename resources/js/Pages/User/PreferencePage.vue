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
        v-else-if="configs.routes.link_line"
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
</template>

<script setup>
import {onMounted, reactive, ref} from 'vue';
import FormRadio from '../../Components/Controls/FormRadio.vue';
import IconLine from '../../Components/Helpers/Icons/IconLine.vue';

const props = defineProps({
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

onMounted(() => {
    if (props.configs.can.add_line && props.configs.routes.add_line && props.configs.mounted_actions.line_add_friend) {
        lineAddButton.value.click();
    }
});
</script>

<style scoped>
.bg-line {
    background-color: #00b900;
}
.bg-telegram {
    background-color: #54a9eb;
}
</style>
