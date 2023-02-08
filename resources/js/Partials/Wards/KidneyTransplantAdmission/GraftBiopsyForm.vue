<template>
    <div>
        <div
            v-for="(biopsy, key) in biopsies"
            :key="key"
            class="my-2 md:my-4 space-y-2 md:space-y-4"
        >
            <div class="grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8">
                <div class="space-y-2 md:space-y-4">
                    <FormDatetime
                        :label="`date of biopsy#${key+1}`"
                        :name="`graft_biopsies.${key}.date_biopsy`"
                        v-model="biopsy.date_biopsy"
                        :error="$page.props.errors['graft_biopsies.'+key+'.date_biopsy']"
                    />
                    <div>
                        <label class="form-label">result biopsy#{{ key + 1 }} :</label>
                        <small
                            class="form-error-block form-scroll-mt"
                            :id="`graft_biopsies.${key}.result`"
                        >
                            {{ $page.props.errors[`graft_biopsies.${key}.result`] }}
                        </small>
                        <div class="mt-2 md:mt-4 grid grid-cols-2 gap-2 md:gap-4">
                            <FormCheckbox
                                v-for="(field, index) in configs.biopsy_result_fields"
                                :key="index"
                                :label="field.label"
                                :name="`graft_biopsies.${key}.result.${field.name}`"
                                v-model="biopsy.result[field.name]"
                            />
                        </div>
                        <FormInput
                            class="mt-2 md:mt-4"
                            :name="`graft_biopsies.${key}.result.other_result`"
                            v-model="biopsy.result.other_result"
                            placeholder="other result"
                        />
                    </div>
                </div>
                <ImageUploader
                    :label="`attachment biopsy#${key+1}`"
                    :service-endpoints="configs.routes.upload"
                    :pathname="configs.attachment_upload_pathname"
                    v-model="biopsy.attachment"
                />
                <button
                    class="block"
                    @click="removeBiopsy(key)"
                >
                    <IconTrashXMark
                        class="w-4 h-4 text-red-400"
                    />
                </button>
            </div>
            <hr class="border border-dashed my-2 md:my-4 xl:my-8">
        </div>
        <button
            @click="biopsies.push({...configs.graft_biopsy})"
        >
            <IconFileCirclePlus
                class="w-4 h-4 text-accent"
            />
        </button>
    </div>
</template>

<script setup>
import IconFileCirclePlus from '../../../Components/Helpers/Icons/IconFileCirclePlus.vue';
import FormDatetime from '../../../Components/Controls/FormDatetime.vue';
import FormCheckbox from '../../../Components/Controls/FormCheckbox.vue';
import FormInput from '../../../Components/Controls/FormInput.vue';
import ImageUploader from '../../../Components/Controls/ImageUploader.vue';
import IconTrashXMark from '../../../Components/Helpers/Icons/IconTrashXMark.vue';
import {nextTick, ref, watch} from 'vue';

const props = defineProps({
    modelValue: {type: Array, required: true,},
    configs: {type: Object, required: true,},
});

const emits = defineEmits(['update:modelValue', 'autosave']);

const biopsies = ref([...props.modelValue]);

watch (
    () => biopsies.value,
    (value) => {
        emits('update:modelValue', [...value]);
        emits('autosave');
    },
    {deep: true}
);
function removeBiopsy(index) {
    let temp = [...biopsies.value];
    // @TODO - remove biopsy image
    temp.splice(index, 1);
    biopsies.value = [];
    nextTick(() => {
        biopsies.value = temp;
    });
}
</script>
