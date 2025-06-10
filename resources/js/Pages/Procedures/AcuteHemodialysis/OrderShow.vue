<template>
    <div>
        <Transition mode="out-in">
            <Suspense v-if="configs.covid.hn">
                <CovidInfo
                    class="mb-4"
                    :configs="configs.covid"
                />
                <template #fallback>
                    <FallbackSpinner />
                </template>
            </Suspense>
        </Transition>
    </div>
    <!-- serology -->
    <SerologyInfo
        class="mt-4 md:mt-8"
        :serology="configs.serology"
    />
    <!-- reservation -->
    <h2 class="mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement">
        Reservation data
    </h2>
    <hr class="my-4 border-b border-accent">
    <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4">
        <DisplayData
            v-for="(field, key) in Object.keys(content.reservation)"
            :key="key"
            :label="field"
            :data="content.reservation[field]"
        />
    </div>
    <!--special requests-->
    <h2
        class="mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement scroll-mt-16 md:scroll-mt-8"
        id="special-requests"
    >
        Special Requests
    </h2>
    <hr class="my-4 border-b border-accent">
    <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4">
        <DisplayData
            v-for="(field, key) in content.special_requests.filter(r => r.data)"
            :key="key"
            :label="field.label"
            :data="field.data"
        />
    </div>
    <!--Predialysis Evaluation-->
    <h2
        class="mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement scroll-mt-16 md:scroll-mt-8"
        id="predialysis-evaluation"
    >
        Predialysis Evaluation
    </h2>
    <hr class="my-4 border-b border-accent">
    <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4">
        <DisplayData
            v-for="(field, key) in content.predialysis_evaluation.filter(e => e.data)"
            :key="key"
            :label="field.label"
            :data="field.data"
        />
    </div>
    <!--Prescription-->
    <template
        v-for="type in ['hd', 'hf', 'pe', 'sledd']"
        :key="type"
    >
        <template v-if="content[type] !== undefined">
            <h2
                class="mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement scroll-mt-16 md:scroll-mt-8"
                id="prescription"
            >
                {{ type }} Prescription
            </h2>
            <hr class="my-4 border-b border-accent">
            <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4">
                <DisplayData
                    v-for="(field, key) in content[type]"
                    :key="key"
                    :label="field.label"
                    :data="field.data"
                />
            </div>
        </template>
    </template>
    <!--session data-->
    <h2
        class="mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement scroll-mt-16 md:scroll-mt-8"
        id="session-data"
    >
        session data
    </h2>
    <hr class="my-4 border-b border-accent">
    <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4">
        <div>
            <FormCheckbox
                :toggler="true"
                label="Dialysis at Chronic unit"
                name="dialysis_at_chronic_unit"
                v-model="form.dialysis_at_chronic_unit"
                v-if="configs.can.check_dialysis_at_chronic_unit"
            />
            <FormCheckbox
                class="mt-2 md:mt-4"
                :toggler="true"
                label="Extra slot"
                name="extra_slot"
                v-model="form.extra_slot"
                v-if="configs.can.change_session_data"
            />
            <FormDatetime
                class="mt-2 md:mt-4"
                mode="time"
                label="started at"
                v-model="form.started_at"
                name="started_at"
                :disabled="!configs.can.edit_timestamp"
            />
            <FormDatetime
                class="mt-2 md:mt-4"
                mode="time"
                label="finished at"
                v-model="form.finished_at"
                name="finished_at"
                :disabled="!configs.can.edit_timestamp"
            />
            <SpinnerButton
                class="mt-2 md:mt-4 btn btn-complement w-full"
                :spin="form.processing"
                @click="form.patch(configs.routes.update_session, {
                    preserveScroll: false,
                    preserveState: true,
                    onFinish: () => {form.processing = false;}
                })"
                v-if="configs.can.change_session_data"
            >
                UPDATE
            </SpinnerButton>
        </div>
        <div
            class="border-t-2 border-complement border-dashed md:border-t-0 pt-4 md:pt-0"
            v-if="configs.can.start_session || configs.can.finish_session"
        >
            <SpinnerButton
                class="btn btn-accent w-full"
                v-if="configs.can.start_session"
                @click="$inertia.post(configs.routes.start_session, {}, {
                    preserveScroll: true,
                    preserveState: true,
                    onProgress: () => {form.processing = true;},
                    onFinish: () => {form.processing = false;}
                })"
                :spin="form.processing"
            >
                Start Session
            </SpinnerButton>
            <SpinnerButton
                class="btn btn-accent w-full"
                v-else-if="configs.can.finish_session"
                @click="$inertia.post(configs.routes.finish_session, {_method: 'delete'}, {
                    preserveScroll: true,
                    preserveState: false,
                    onProgress: () => {form.processing = true;},
                    onFinish: () => {form.processing = false;}
                })"
                :spin="form.processing"
            >
                Finish Session
            </SpinnerButton>
        </div>
    </div>
    <!--discussion-->
    <h2
        class="mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement scroll-mt-16 md:scroll-mt-8"
        id="discussion"
    >
        discussion
    </h2>
    <hr class="my-4 border-b border-accent">
    <CommentSection :configs="configs.comment" />
</template>

<script setup>
import CovidInfo from '../../../Components/Helpers/CovidInfo.vue';
import FallbackSpinner from '../../../Components/Helpers/FallbackSpinner.vue';
import DisplayData from '../../../Components/Controls/DisplayData.vue';
import SpinnerButton from '../../../Components/Controls/SpinnerButton.vue';
import FormCheckbox from '../../../Components/Controls/FormCheckbox.vue';
import FormDatetime from '../../../Components/Controls/FormDatetime.vue';
import {useForm} from '@inertiajs/vue3';
import CommentSection from '../../../Components/Forms/CommentSection.vue';
import SerologyInfo from '../../../Components/Helpers/SerologyInfo.vue';

const props = defineProps({
    configs: {type: Object, required: true},
    content: {type: Object, required: true}
});

const form = useForm({
    dialysis_at_chronic_unit: props.configs.session.dialysis_at_chronic_unit,
    extra_slot: props.configs.session.extra_slot,
    started_at: props.configs.session.started_at,
    finished_at: props.configs.session.finished_at,
    hashed_key: props.configs.session.hashed_key,
});
</script>

