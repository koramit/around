<template>
    <div>
        <div
            v-for="(followUp, key) in followUps"
            :key="key"
            class="my-2 md:my-4 space-y-2 md:space-y-4"
        >
            <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8">
                <FormDatetime
                    :label="`date of follow up #${key+1}`"
                    :name="`follow_ups.${key}.date_follow_up`"
                    v-model="followUp.date_follow_up"
                    :error="$page.props.errors['follow_ups.'+key+'.date_follow_up']"
                />
                <FormInput
                    :label="`place #${key+1}`"
                    :name="`follow_ups.${key}.place`"
                    v-model="followUp.place"
                    :error="$page.props.errors['follow_ups.'+key+'.place']"
                />
                <FormInput
                    :label="`for #${key+1}`"
                    :name="`follow_ups.${key}.for`"
                    v-model="followUp.for"
                    :error="$page.props.errors['follow_ups.'+key+'.for']"
                />
                <FormInput
                    :label="`MD #${key+1}`"
                    :name="`follow_ups.${key}.md`"
                    v-model="followUp.md"
                />
            </div>
            <button
                class="block"
                @click="removeFollowUp(key)"
            >
                <IconTrashXMark
                    class="w-4 h-4 text-red-400"
                />
            </button>
            <hr class="border border-dashed my-2 md:my-4 xl:my-8">
        </div>
        <button
            @click="followUps.push({...configs.follow_up})"
        >
            <IconFileCirclePlus
                class="w-4 h-4 text-accent"
            />
        </button>
    </div>
</template>
<script setup>

import {nextTick, ref, watch} from 'vue';
import IconTrashXMark from '../../../Components/Helpers/Icons/IconTrashXMark.vue';
import FormInput from '../../../Components/Controls/FormInput.vue';
import FormDatetime from '../../../Components/Controls/FormDatetime.vue';
import IconFileCirclePlus from '../../../Components/Helpers/Icons/IconFileCirclePlus.vue';

const props = defineProps({
    modelValue: {type: Array, required: true,},
    configs: {type: Object, required: true,},
});

const emits = defineEmits(['update:modelValue', 'autosave']);

const followUps = ref([...props.modelValue]);

watch (
    () => followUps.value,
    (value) => {
        emits('update:modelValue', [...value]);
        emits('autosave');
    },
    {deep: true}
);

function removeFollowUp(index) {
    let temp = [...followUps.value];
    // @TODO - remove biopsy image
    temp.splice(index, 1);
    followUps.value = [];
    nextTick(() => {
        followUps.value = temp;
    });
}
</script>
