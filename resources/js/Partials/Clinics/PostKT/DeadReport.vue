<script setup>

import FormCheckbox from '../../../Components/Controls/FormCheckbox.vue';
import {nextTick, reactive, watch} from 'vue';
import FormInput from '../../../Components/Controls/FormInput.vue';

const model = defineModel({type: Array, required: true});

const form = reactive({
    dead_101: Boolean(model.value.find(item => parseInt(item.code) === 101)),
    dead_102: Boolean(model.value.find(item => parseInt(item.code) === 102)),
    dead_103: Boolean(model.value.find(item => parseInt(item.code) === 103)),
    dead_104: Boolean(model.value.find(item => parseInt(item.code) === 104)),
    dead_105: Boolean(model.value.find(item => parseInt(item.code) === 105)),
    dead_106: Boolean(model.value.find(item => parseInt(item.code) === 106)),
    dead_107: Boolean(model.value.find(item => parseInt(item.code) === 107)),
    dead_107_specification: model.value.find(item => parseInt(item.code) === 107)?.specification,
    dead_108: Boolean(model.value.find(item => parseInt(item.code) === 108)),
    dead_201: Boolean(model.value.find(item => parseInt(item.code) === 201)),
    dead_202: Boolean(model.value.find(item => parseInt(item.code) === 202)),
    dead_203: Boolean(model.value.find(item => parseInt(item.code) === 203)),
    dead_204: Boolean(model.value.find(item => parseInt(item.code) === 204)),
    dead_205: Boolean(model.value.find(item => parseInt(item.code) === 205)),
    dead_206: Boolean(model.value.find(item => parseInt(item.code) === 206)),
    dead_207: Boolean(model.value.find(item => parseInt(item.code) === 207)),
    dead_208: Boolean(model.value.find(item => parseInt(item.code) === 208)),
    dead_208_specification: model.value.find(item => parseInt(item.code) === 208)?.specification,
    dead_301: Boolean(model.value.find(item => parseInt(item.code) === 301)),
    dead_301_specification: model.value.find(item => parseInt(item.code) === 301)?.specification,
    dead_302: Boolean(model.value.find(item => parseInt(item.code) === 302)),
    dead_303: Boolean(model.value.find(item => parseInt(item.code) === 303)),
    dead_304: Boolean(model.value.find(item => parseInt(item.code) === 304)),
    dead_305: Boolean(model.value.find(item => parseInt(item.code) === 305)),
    dead_306: Boolean(model.value.find(item => parseInt(item.code) === 306)),
    dead_306_specification: model.value.find(item => parseInt(item.code) === 306)?.specification,
    dead_307: Boolean(model.value.find(item => parseInt(item.code) === 307)),
    dead_308: Boolean(model.value.find(item => parseInt(item.code) === 308)),
    dead_309: Boolean(model.value.find(item => parseInt(item.code) === 309)),
    dead_309_specification: model.value.find(item => parseInt(item.code) === 309)?.specification,
    dead_310: Boolean(model.value.find(item => parseInt(item.code) === 310)),
    dead_311: Boolean(model.value.find(item => parseInt(item.code) === 311)),
    dead_400: Boolean(model.value.find(item => parseInt(item.code) === 400)),
    dead_501: Boolean(model.value.find(item => parseInt(item.code) === 501)),
    dead_502: Boolean(model.value.find(item => parseInt(item.code) === 502)),
    dead_503: Boolean(model.value.find(item => parseInt(item.code) === 503)),
    dead_504: Boolean(model.value.find(item => parseInt(item.code) === 504)),
    dead_504_specification: model.value.find(item => parseInt(item.code) === 504)?.specification,
    dead_600: Boolean(model.value.find(item => parseInt(item.code) === 600)),
    dead_600_specification: model.value.find(item => parseInt(item.code) === 600)?.specification,
    dead_700: Boolean(model.value.find(item => parseInt(item.code) === 700)),
    dead_700_specification: model.value.find(item => parseInt(item.code) === 700)?.specification,
    dead_800: Boolean(model.value.find(item => parseInt(item.code) === 800)),
    dead_800_specification: model.value.find(item => parseInt(item.code) === 800)?.specification,
    dead_900: Boolean(model.value.find(item => parseInt(item.code) === 900)),
});

watch(
    () => form,
    val => {
        const keys = Object.keys(form);
        let codes = [];
        keys.forEach((key) => {
            if (val[key] === true) {
                const code = parseInt(key.replace('dead_', ''));
                let specification = null;
                if (val[`${key}_specification`] !== undefined && val[`${key}_specification`]) {
                    specification = val[`${key}_specification`];
                }
                codes.push({code: code, specification: specification});
            }
        });
        model.value = [...codes];
    },
    {deep: true}
);

const specificationInputRefs = reactive({
    dead_107: null,
    dead_208: null,
    dead_301: null,
    dead_306: null,
    dead_309: null,
    dead_504: null,
    dead_600: null,
    dead_700: null,
    dead_800: null,
});
function toggleSpecification(val, code) {
    if (val) {
        nextTick(() => specificationInputRefs[code].focus());
    } else {
        form[`${code}_specification`] = null;
    }
}
[...Object.keys(specificationInputRefs)].forEach(code => {
    watch(
        () => form[code],
        (val) => toggleSpecification(val, code)
    );
});
</script>

<template>
    <div class="grid grid-cols-1 gap-2 md:grid-cols-2 md:gap-4 xl:gap-6">
        <div class="space-y-4 md:space-y-6 xl:space-y-8">
            <div class="space-y-2 md:space-y-4 xl:space-y-6">
                <FormCheckbox
                    label="101 - Infection from CMV"
                    name="dead_101"
                    v-model="form.dead_101"
                />
                <FormCheckbox
                    label="102 - Infection from TB"
                    name="dead_102"
                    v-model="form.dead_102"
                />
                <FormCheckbox
                    label="103 - Infection from PCP"
                    name="dead_103"
                    v-model="form.dead_103"
                />
                <FormCheckbox
                    label="104 - Infection from strongyloid"
                    name="dead_104"
                    v-model="form.dead_104"
                />
                <FormCheckbox
                    label="105 - Infection from pneumonia"
                    name="dead_105"
                    v-model="form.dead_105"
                />
                <FormCheckbox
                    label="106 - Infection from UTI"
                    name="dead_106"
                    v-model="form.dead_106"
                />
                <FormCheckbox
                    label="107 - Other Infection"
                    name="dead_107"
                    v-model="form.dead_107"
                />
                <FormInput
                    placeholder="specific other infection"
                    name="dead_107_specification"
                    v-model="form.dead_107_specification"
                    :disabled="!form.dead_107"
                    :ref="ref => specificationInputRefs.dead_107 = ref"
                />
                <FormCheckbox
                    label="108 - sepsis (ไม่รู้ source)"
                    name="dead_108"
                    v-model="form.dead_108"
                />
            </div>
            <hr class="border border-dashed my-2 md:my-4 xl:my-8">
            <div class="space-y-2 md:space-y-4 xl:space-y-6">
                <FormCheckbox
                    label="201 - Coronary artery disease"
                    name="dead_201"
                    v-model="form.dead_201"
                />
                <FormCheckbox
                    label="202 - Cardiomyopathy, heart failure"
                    name="dead_202"
                    v-model="form.dead_202"
                />
                <FormCheckbox
                    label="203 - Arrhythmia"
                    name="dead_203"
                    v-model="form.dead_203"
                />
                <FormCheckbox
                    label="204 - Cerebrovascular disease (CVA)"
                    name="dead_204"
                    v-model="form.dead_204"
                />
                <FormCheckbox
                    label="205 - Peripheral arterial disease"
                    name="dead_205"
                    v-model="form.dead_205"
                />
                <FormCheckbox
                    label="206 - Pulmonary embolism"
                    name="dead_206"
                    v-model="form.dead_206"
                />
                <FormCheckbox
                    label="207 - Sudden cardiac death"
                    name="dead_207"
                    v-model="form.dead_207"
                />
                <FormCheckbox
                    label="208 - Other"
                    name="dead_208"
                    v-model="form.dead_208"
                />
                <FormInput
                    placeholder="cardiac tamponade, valvular disease, pericarditis etc"
                    name="dead_208_specification"
                    v-model="form.dead_208_specification"
                    :disabled="!form.dead_208"
                    :ref="ref => specificationInputRefs.dead_208 = ref"
                />
            </div>
            <hr class="border border-dashed my-2 md:my-4 xl:my-8">
            <div class="space-y-2 md:space-y-4 xl:space-y-6">
                <FormCheckbox
                    label="301 - KUB malignancy"
                    name="dead_301"
                    v-model="form.dead_301"
                />
                <FormInput
                    placeholder="specific KUB malignancy"
                    name="dead_301_specification"
                    v-model="form.dead_301_specification"
                    :disabled="!form.dead_301"
                    :ref="ref => specificationInputRefs.dead_301 = ref"
                />
                <FormCheckbox
                    label="302 - Hepatocellular (HCC)"
                    name="dead_302"
                    v-model="form.dead_302"
                />
                <FormCheckbox
                    label="303 - Lung cancer"
                    name="dead_303"
                    v-model="form.dead_303"
                />
                <FormCheckbox
                    label="304 - Gl tract cancer"
                    name="dead_304"
                    v-model="form.dead_304"
                />
                <FormCheckbox
                    label="305 - Breast cancer"
                    name="dead_305"
                    v-model="form.dead_305"
                />
                <FormCheckbox
                    label="306 - Other solid malignancies"
                    name="dead_306"
                    v-model="form.dead_306"
                />
                <FormInput
                    placeholder="specific Other solid malignancies"
                    name="dead_306_specification"
                    v-model="form.dead_306_specification"
                    :disabled="!form.dead_306"
                    :ref="ref => specificationInputRefs.dead_306 = ref"
                />
                <FormCheckbox
                    label="307 - Lymphoma"
                    name="dead_307"
                    v-model="form.dead_307"
                />
                <FormCheckbox
                    label="308 - Leukemia"
                    name="dead_308"
                    v-model="form.dead_308"
                />
                <FormCheckbox
                    label="309 - Other hematologic malignancies"
                    name="dead_309"
                    v-model="form.dead_309"
                />
                <FormInput
                    placeholder="specific Other hematologic malignancies"
                    name="dead_309_specification"
                    v-model="form.dead_309_specification"
                    :disabled="!form.dead_309"
                    :ref="ref => specificationInputRefs.dead_309 = ref"
                />
                <FormCheckbox
                    label="310 - sarcoma"
                    name="dead_310"
                    v-model="form.dead_310"
                />
                <FormCheckbox
                    label="311 - malignancy of unknown origin"
                    name="dead_311"
                    v-model="form.dead_311"
                />
            </div>
        </div>
        <div class="space-y-4 md:space-y-6 xl:space-y-8">
            <div class="space-y-2 md:space-y-4 xl:space-y-6">
                <FormCheckbox
                    label="400 - Renal failure, no RRT"
                    name="dead_400"
                    v-model="form.dead_400"
                />
            </div>
            <hr class="border border-dashed my-2 md:my-4 xl:my-8">
            <div class="space-y-2 md:space-y-4 xl:space-y-6">
                <FormCheckbox
                    label="501 - Liver failure from viral infection"
                    name="dead_501"
                    v-model="form.dead_501"
                />
                <FormCheckbox
                    label="502 - Liver failure from drugs"
                    name="dead_502"
                    v-model="form.dead_502"
                />
                <FormCheckbox
                    label="503 - Liver failure from alcohol"
                    name="dead_503"
                    v-model="form.dead_503"
                />
                <FormCheckbox
                    label="504 - Other liver failure."
                    name="dead_504"
                    v-model="form.dead_504"
                />
                <FormInput
                    placeholder="specific Other liver failure"
                    name="dead_504_specification"
                    v-model="form.dead_504_specification"
                    :disabled="!form.dead_504"
                    :ref="ref => specificationInputRefs.dead_504 = ref"
                />
            </div>
            <hr class="border border-dashed my-2 md:my-4 xl:my-8">
            <div class="space-y-2 md:space-y-4 xl:space-y-6">
                <FormCheckbox
                    label="600 - Accident"
                    name="dead_600"
                    v-model="form.dead_600"
                />
                <FormInput
                    placeholder="specific Accident"
                    name="dead_600_specification"
                    v-model="form.dead_600_specification"
                    :disabled="!form.dead_600"
                    :ref="ref => specificationInputRefs.dead_600 = ref"
                />
            </div>
            <hr class="border border-dashed my-2 md:my-4 xl:my-8">
            <div class="space-y-2 md:space-y-4 xl:space-y-6">
                <FormCheckbox
                    label="700 - Suicide"
                    name="dead_700"
                    v-model="form.dead_700"
                />
                <FormInput
                    placeholder="specific Suicide"
                    name="dead_700_specification"
                    v-model="form.dead_700_specification"
                    :disabled="!form.dead_700"
                    :ref="ref => specificationInputRefs.dead_700 = ref"
                />
            </div>
            <hr class="border border-dashed my-2 md:my-4 xl:my-8">
            <div class="space-y-2 md:space-y-4 xl:space-y-6">
                <FormCheckbox
                    label="800 - Other cause"
                    name="dead_800"
                    v-model="form.dead_800"
                />
                <FormInput
                    placeholder="specific Other cause"
                    name="dead_800_specification"
                    v-model="form.dead_800_specification"
                    :disabled="!form.dead_800"
                    :ref="ref => specificationInputRefs.dead_800 = ref"
                />
            </div>
            <hr class="border border-dashed my-2 md:my-4 xl:my-8">
            <div class="space-y-2 md:space-y-4 xl:space-y-6">
                <FormCheckbox
                    label="900 - unknown cause"
                    name="dead_900"
                    v-model="form.dead_900"
                />
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
