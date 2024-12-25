<script setup>

import FormCheckbox from '../../../Components/Controls/FormCheckbox.vue';
import {nextTick, reactive, watch} from 'vue';
import FormInput from '../../../Components/Controls/FormInput.vue';
import AlertMessage from '../../../Components/Helpers/AlertMessage.vue';

const model = defineModel({type: Array, required: true});

const form = reactive({
    gl_101: Boolean(model.value.find(item => parseInt(item.code) === 101)),
    gl_102: Boolean(model.value.find(item => parseInt(item.code) === 102)),
    gl_103: Boolean(model.value.find(item => parseInt(item.code) === 103)),
    gl_104: Boolean(model.value.find(item => parseInt(item.code) === 104)),
    gl_105: Boolean(model.value.find(item => parseInt(item.code) === 105)),
    gl_106: Boolean(model.value.find(item => parseInt(item.code) === 106)),
    gl_109: Boolean(model.value.find(item => parseInt(item.code) === 109)),
    gl_200: Boolean(model.value.find(item => parseInt(item.code) === 200)),
    gl_200_specification: model.value.find(item => parseInt(item.code) === 200)?.specification,
    gl_300: Boolean(model.value.find(item => parseInt(item.code) === 300)),
    gl_401: Boolean(model.value.find(item => parseInt(item.code) === 401)),
    gl_402: Boolean(model.value.find(item => parseInt(item.code) === 402)),
    gl_403: Boolean(model.value.find(item => parseInt(item.code) === 403)),
    gl_404: Boolean(model.value.find(item => parseInt(item.code) === 404)),
    gl_405: Boolean(model.value.find(item => parseInt(item.code) === 405)),
    gl_406: Boolean(model.value.find(item => parseInt(item.code) === 406)),
    gl_407: Boolean(model.value.find(item => parseInt(item.code) === 407)),
    gl_408: Boolean(model.value.find(item => parseInt(item.code) === 408)),
    gl_408_specification: model.value.find(item => parseInt(item.code) === 408)?.specification,
    gl_409: Boolean(model.value.find(item => parseInt(item.code) === 409)),
    gl_501: Boolean(model.value.find(item => parseInt(item.code) === 501)),
    gl_502: Boolean(model.value.find(item => parseInt(item.code) === 502)),
    gl_503: Boolean(model.value.find(item => parseInt(item.code) === 503)),
    gl_504: Boolean(model.value.find(item => parseInt(item.code) === 504)),
    gl_505: Boolean(model.value.find(item => parseInt(item.code) === 505)),
    gl_601: Boolean(model.value.find(item => parseInt(item.code) === 601)),
    gl_602: Boolean(model.value.find(item => parseInt(item.code) === 602)),
    gl_701: Boolean(model.value.find(item => parseInt(item.code) === 701)),
    gl_702: Boolean(model.value.find(item => parseInt(item.code) === 702)),
    gl_801: Boolean(model.value.find(item => parseInt(item.code) === 801)),
    gl_802: Boolean(model.value.find(item => parseInt(item.code) === 802)),
    gl_802_specification: model.value.find(item => parseInt(item.code) === 802)?.specification,
    gl_901: Boolean(model.value.find(item => parseInt(item.code) === 901)),
    gl_902: Boolean(model.value.find(item => parseInt(item.code) === 902)),
    gl_903: Boolean(model.value.find(item => parseInt(item.code) === 903)),
    gl_904: Boolean(model.value.find(item => parseInt(item.code) === 904)),
    gl_904_specification: model.value.find(item => parseInt(item.code) === 904)?.specification,
    gl_905: Boolean(model.value.find(item => parseInt(item.code) === 905)),
    gl_906: Boolean(model.value.find(item => parseInt(item.code) === 906)),
    gl_907: Boolean(model.value.find(item => parseInt(item.code) === 907)),
    gl_999: Boolean(model.value.find(item => parseInt(item.code) === 999)),
});

watch(
    () => form,
    val => {
        const keys = Object.keys(form);
        let codes = [];
        keys.forEach((key) => {
            if (val[key] === true) {
                const code = parseInt(key.replace('gl_', ''));
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
    gl_200: null,
    gl_408: null,
    gl_802: null,
    gl_904: null,
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
                    label="101 - Antibody mediated rejection (Bx)"
                    name="gl_101"
                    v-model="form.gl_101"
                />
                <FormCheckbox
                    label="102 - Acute cellular rejection (Bx)"
                    name="gl_102"
                    v-model="form.gl_102"
                />
                <FormCheckbox
                    label="103 - Acute vascular rejection (Bx)"
                    name="gl_103"
                    v-model="form.gl_103"
                />
                <FormCheckbox
                    label="104 - Mixed ABMR + ACR (Bx)"
                    name="gl_104"
                    v-model="form.gl_104"
                />
                <FormCheckbox
                    label="105 - Chronic antibody mediated rejection (Bx)"
                    name="gl_105"
                    v-model="form.gl_105"
                />
                <FormCheckbox
                    label="106 - Hyperacute rejection"
                    name="gl_106"
                    v-model="form.gl_106"
                />
                <FormCheckbox
                    label="109 - Rejection, non-specific type"
                    name="gl_109"
                    v-model="form.gl_109"
                />
            </div>
            <hr class="border border-dashed my-2 md:my-4 xl:my-8">
            <div class="space-y-2 md:space-y-4 xl:space-y-6">
                <FormCheckbox
                    label="200 - Severe IFTA, unknown cause"
                    name="gl_200"
                    v-model="form.gl_200"
                />
                <FormInput
                    placeholder="IFTA จากอย่างอื่นลงเหตุนั้นๆ"
                    name="gl_200_specification"
                    v-model="form.gl_200_specification"
                    :disabled="!form.gl_200"
                    :ref="ref => specificationInputRefs.gl_200 = ref"
                />
            </div>
            <hr class="border border-dashed my-2 md:my-4 xl:my-8">
            <div class="space-y-2 md:space-y-4 xl:space-y-6">
                <FormCheckbox
                    label="300 - CNI toxicity"
                    name="gl_300"
                    v-model="form.gl_300"
                />
            </div>
            <hr class="border border-dashed my-2 md:my-4 xl:my-8">
            <div class="space-y-2 md:space-y-4 xl:space-y-6">
                <FormCheckbox
                    label="401 - MPGN type 1 (Bx)"
                    name="gl_401"
                    v-model="form.gl_401"
                />
                <FormCheckbox
                    label="402 - MPGN type 2 (Bx)"
                    name="gl_402"
                    v-model="form.gl_402"
                />
                <FormCheckbox
                    label="403 - FSGS (Bx)"
                    name="gl_403"
                    v-model="form.gl_403"
                />
                <FormCheckbox
                    label="404 - Membranous nephropathy (Bx)"
                    name="gl_404"
                    v-model="form.gl_404"
                />
                <FormCheckbox
                    label="405 - 1gA nephropathy (Bx)"
                    name="gl_405"
                    v-model="form.gl_405"
                />
                <FormCheckbox
                    label="406 - Anti GBM (Bx)"
                    name="gl_406"
                    v-model="form.gl_406"
                />
                <FormCheckbox
                    label="407 - ANCA associated glomerulonephritis (Bx)"
                    name="gl_407"
                    v-model="form.gl_407"
                />
                <FormCheckbox
                    label="408 - Other glomerular disease (Bx proven)"
                    name="gl_408"
                    v-model="form.gl_408"
                />
                <FormInput
                    placeholder="ex. diabetic, nephropathy, lupus nephritis etc..."
                    name="gl_408_specification"
                    v-model="form.gl_408_specification"
                    :disabled="!form.gl_408"
                    :ref="ref => specificationInputRefs.gl_408 = ref"
                />
                <FormCheckbox
                    label="409 - Suspected glomerular disease, unknown (no biopsy)"
                    name="gl_409"
                    v-model="form.gl_409"
                />
            </div>
        </div>
        <div class="space-y-4 md:space-y-6 xl:space-y-8">
            <div class="space-y-2 md:space-y-4 xl:space-y-6">
                <FormCheckbox
                    label="501 - TRAS"
                    name="gl_501"
                    v-model="form.gl_501"
                />
                <FormCheckbox
                    label="502 - Transplanted renal artery thrombosis, early post op"
                    name="gl_502"
                    v-model="form.gl_502"
                />
                <FormCheckbox
                    label="503 - Transplanted renal vein thrombosis"
                    name="gl_503"
                    v-model="form.gl_503"
                />
                <FormCheckbox
                    label="504 - Ureteric obstruction"
                    name="gl_504"
                    v-model="form.gl_504"
                />
                <FormCheckbox
                    label="505 - Urine leak"
                    name="gl_505"
                    v-model="form.gl_505"
                />
            </div>
            <hr class="border border-dashed my-2 md:my-4 xl:my-8">
            <div class="space-y-2 md:space-y-4 xl:space-y-6">
                <FormCheckbox
                    label="601 - BKVAN"
                    name="gl_601"
                    v-model="form.gl_601"
                />
                <FormCheckbox
                    label="602 - Adenovirus infection"
                    name="gl_602"
                    v-model="form.gl_602"
                />
            </div>
            <hr class="border border-dashed my-2 md:my-4 xl:my-8">
            <div class="space-y-2 md:space-y-4 xl:space-y-6">
                <FormCheckbox
                    label="701 - TMA, with rejection"
                    name="gl_701"
                    v-model="form.gl_701"
                />
                <FormCheckbox
                    label="702 - TMA, no rejection"
                    name="gl_702"
                    v-model="form.gl_702"
                />
            </div>
            <hr class="border border-dashed my-2 md:my-4 xl:my-8">
            <div class="space-y-2 md:space-y-4 xl:space-y-6">
                <FormCheckbox
                    label="801 - AKI ontop then graft loss (sepsis)"
                    name="gl_801"
                    v-model="form.gl_801"
                />
                <FormCheckbox
                    label="802 - AKI ontop then graft loss"
                    name="gl_802"
                    v-model="form.gl_802"
                />
                <FormInput
                    placeholder="any other causes"
                    name="gl_802_specification"
                    v-model="form.gl_802_specification"
                    :disabled="!form.gl_802"
                    :ref="ref => specificationInputRefs.gl_802 = ref"
                />
            </div>
            <hr class="border border-dashed my-2 md:my-4 xl:my-8">
            <div class="space-y-2 md:space-y-4 xl:space-y-6">
                <FormCheckbox
                    label="901 - Primary non-functioning"
                    name="gl_901"
                    v-model="form.gl_901"
                />
                <AlertMessage
                    title="Code 901 guideline"
                    message="nephrectomy or at 3 months after Tx / ไม่เคย Off dialysis ได้มากกว่า 1 เดือน"
                />
                <FormCheckbox
                    label="902 - Malignancy at transplanted kidney"
                    name="gl_902"
                    v-model="form.gl_902"
                />
                <FormCheckbox
                    label="905 - Malignancy invade graft"
                    name="gl_905"
                    v-model="form.gl_905"
                />
                <FormCheckbox
                    label="906 - non-compliance"
                    name="gl_906"
                    v-model="form.gl_906"
                />
                <FormCheckbox
                    label="907 - loss follow up > 6 mo"
                    name="gl_907"
                    v-model="form.gl_907"
                />
                <FormCheckbox
                    label="903 - Unknown"
                    name="gl_903"
                    v-model="form.gl_903"
                />
                <FormCheckbox
                    label="904 - Other causes"
                    name="gl_904"
                    v-model="form.gl_904"
                />
                <FormInput
                    placeholder="specify other causes"
                    name="gl_904_specification"
                    v-model="form.gl_904_specification"
                    :disabled="!form.gl_904"
                    :ref="ref => specificationInputRefs.gl_904 = ref"
                />
                <FormCheckbox
                    label="999 - Dead with functioning"
                    name="gl_999"
                    v-model="form.gl_999"
                />
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
