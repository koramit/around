<script setup>

import FormCheckbox from '../../../Components/Controls/FormCheckbox.vue';
import {reactive} from 'vue';
import FormInput from '../../../Components/Controls/FormInput.vue';
import AlertMessage from '../../../Components/Helpers/AlertMessage.vue';

const form = reactive({
    gl_101: false,
    gl_102: false,
    gl_103: false,
    gl_104: false,
    gl_105: false,
    gl_106: false,
    gl_109: false,
    gl_200: false,
    gl_200_specification: null,
    gl_300: false,
    gl_401: false,
    gl_402: false,
    gl_403: false,
    gl_404: false,
    gl_405: false,
    gl_406: false,
    gl_407: false,
    gl_408: false,
    gl_408_specification: null,
    gl_409: false,
    gl_501: false,
    gl_502: false,
    gl_503: false,
    gl_504: false,
    gl_505: false,
    gl_601: false,
    gl_602: false,
    gl_701: false,
    gl_702: false,
    gl_801: false,
    gl_802: false,
    gl_802_specification: null,
    gl_901: false,
    gl_902: false,
    gl_903: false,
    gl_904: false,
    gl_904_specification: null,
    gl_999: false,
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
                />
            </div>
            <div class="space-y-2 md:space-y-4 xl:space-y-6">
                <FormCheckbox
                    label="300 - CNI toxicity"
                    name="gl_300"
                    v-model="form.gl_300"
                />
            </div>
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
                    name="gl_200_specification"
                    v-model="form.gl_200_specification"
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
                />
            </div>
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
