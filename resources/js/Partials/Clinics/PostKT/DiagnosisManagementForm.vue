<script setup>
import {nextTick, ref, watch} from 'vue';
import IconFileCirclePlus from '../../../Components/Helpers/Icons/IconFileCirclePlus.vue';
import IconTrashXMark from '../../../Components/Helpers/Icons/IconTrashXMark.vue';
import FormDatetime from '../../../Components/Controls/FormDatetime.vue';
import FormTextarea from '../../../Components/Controls/FormTextarea.vue';

const props = defineProps({
    modelValue: {type: Array, required: true},
    configs: {type: Object, required: true},
});

const emits = defineEmits(['update:modelValue', 'autosave']);

const managements = ref([...props.modelValue]);

watch (
    () => managements.value,
    (value) => {
        emits('update:modelValue', [...value]);
        emits('autosave');
    },
    {deep: true}
);

function removeManagement(index) {
    let temp = [...managements.value];
    temp.splice(index, 1);
    managements.value = [];
    nextTick(() => {
        managements.value = temp;
    });
}
</script>

<template>
    <div>
        <div
            v-for="(management, key) in managements"
            :key="key"
            class="my-2 md:my-4 space-y-2 md:space-y-4"
        >
            <div class="grid gap-2 md:gap-4 xl:gap-8">
                <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8">
                    <FormDatetime
                        :label="`date diagnosis #${key+1}`"
                        :name="`managements.${key}.date_diagnosis`"
                        v-model="management.date_diagnosis"
                        :error="$page.props.errors['managements.'+key+'.date_diagnosis']"
                    />
                </div>
                <FormTextarea
                    :label="`DX and MX #${key+1}`"
                    :name="`managements.${key}.management`"
                    v-model="management.management"
                    :error="$page.props.errors['managements.'+key+'.management']"
                />
            </div>
            <button
                class="block"
                @click="removeManagement(key)"
            >
                <IconTrashXMark
                    class="w-4 h-4 text-red-400"
                />
            </button>
            <hr class="border border-dashed my-2 md:my-4 xl:my-8">
        </div>
        <button
            @click="managements.push({...configs.template})"
        >
            <IconFileCirclePlus
                class="w-4 h-4 text-accent"
            />
        </button>
    </div>
</template>

<style scoped>

</style>
