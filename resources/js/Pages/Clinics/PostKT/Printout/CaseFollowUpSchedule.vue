<script setup>

import {onMounted} from 'vue';

defineProps({
    data: {type: Object, required: true}
});

const headers = [
    'Wk1', 'Wk2', 'Wk4 M1', 'Wk8 M2', 'Wk12 M3', 'Wk16 M4',
    'Wk20 M5', 'Wk24 M6', 'Wk36 M9', 'Wk48 Y1', 'Wk96 Y2', 'Wk144 Y3', 'Wk192 Y4', 'Wk240 Y5'
];

onMounted(() => {
    document.body.classList.remove('bg-primary');
    setTimeout(() => print(), 300);
});
</script>

<template>
    <div class="border-2 border-gray-950">
        <div class="grid grid-cols-2 gap-2 p-2">
            <div class="space-y-0.5">
                <p class="space-x-1.5 text-xs">
                    <label>Recipient</label>
                    <span class="w-full text-center">{{ data.recipient_label }}</span>
                </p>
                <p class="space-x-1.5 text-xs">
                    <label class="whitespace-nowrap">KT Date</label>
                    <span class="w-full text-center">{{ data.date_transplant }}</span>
                </p>
            </div>
            <div class="space-y-0.5">
                <p class="space-x-1.5 text-xs">
                    <label class="whitespace-nowrap">KT ID</label>
                    <span class="w-full text-center">{{ data.kt_id }}</span>
                </p>
                <p class="space-x-1.5 text-xs">
                    <label class="whitespace-nowrap">Medical Scheme</label>
                    <span class="w-full text-center">{{ data.medical_scheme }}</span>
                </p>
            </div>
        </div>
        <table class="mt-[1.5cm]">
            <thead>
                <tr>
                    <th
                        class="border border-gray-700 whitespace-nowrap w-[3.75cm]"
                        scope="col"
                    >
                        Lab
                    </th>
                    <th
                        v-for="(head, key) in headers"
                        :key
                        scope="col"
                        class="border border-gray-700 w-[1.25cm]"
                        v-text="head"
                    />
                </tr>
            </thead>
            <tbody>
                <!-- date -->
                <tr class="h-[3cm] relative">
                    <td class="border border-gray-700 text-center">
                        Date
                    </td>
                    <td
                        v-for="(fu, key) in data.fus"
                        :key
                        class="border border-gray-700 relative"
                    >
                        <div class="absolute -left-4 top-10 whitespace-nowrap -rotate-90">
                            {{ fu }}
                        </div>
                    </td>
                </tr>
                <!-- visit -->
                <tr>
                    <td class="border border-gray-700 text-center">
                        visit
                    </td>
                    <td
                        v-for="(fu, key) in data.fus"
                        :key
                        class="border border-gray-700 text-center"
                        v-text="key"
                    />
                </tr>
                <!-- CBC -->
                <tr>
                    <td class="border border-gray-700 text-center">
                        CBC
                    </td>
                    <td
                        v-for="(fu, key) in data.fus"
                        :key
                        class="border border-gray-700 text-center"
                        v-text="'X'"
                    />
                </tr>
                <!-- BUN/Cr -->
                <tr>
                    <td class="border border-gray-700 text-center">
                        BUN/Cr
                    </td>
                    <td
                        v-for="(fu, key) in data.fus"
                        :key
                        class="border border-gray-700 text-center"
                        v-text="'X'"
                    />
                </tr>
                <!-- K -->
                <tr>
                    <td class="border border-gray-700 text-center">
                        K
                    </td>
                    <td
                        v-for="(fu, key) in data.fus"
                        :key
                        class="border border-gray-700 text-center"
                        v-text="[2,4,7,9,10,11,12,13].includes(key) ? 'X' : ''"
                    />
                </tr>
                <!-- Albumin -->
                <tr>
                    <td class="border border-gray-700 text-center">
                        Albumin
                    </td>
                    <td
                        v-for="(fu, key) in data.fus"
                        :key
                        class="border border-gray-700 text-center"
                        v-text="[2,4,7,9,10,11,12,13].includes(key) ? 'X' : ''"
                    />
                </tr>
                <!-- Uric acid -->
                <tr>
                    <td class="border border-gray-700 text-center">
                        Uric acid
                    </td>
                    <td
                        v-for="(fu, key) in data.fus"
                        :key
                        class="border border-gray-700 text-center"
                        v-text="[].includes(key) ? 'X' : ''"
                    />
                </tr>
                <!-- LDL/TG -->
                <tr>
                    <td class="border border-gray-700 text-center">
                        LDL/TG
                    </td>
                    <td
                        v-for="(fu, key) in data.fus"
                        :key
                        class="border border-gray-700 text-center"
                        v-text="[2,4,7,9,10,11,12,13].includes(key) ? 'X' : ''"
                    />
                </tr>
                <!-- FBS -->
                <tr>
                    <td class="border border-gray-700 text-center">
                        FBS
                    </td>
                    <td
                        v-for="(fu, key) in data.fus"
                        :key
                        class="border border-gray-700 text-center"
                        v-text="[].includes(key) ? 'X' : ''"
                    />
                </tr>
                <!-- HbA1C -->
                <tr>
                    <td class="border border-gray-700 text-center">
                        HbA1C
                    </td>
                    <td
                        v-for="(fu, key) in data.fus"
                        :key
                        class="border border-gray-700 text-center"
                        v-text="[].includes(key) ? 'X' : ''"
                    />
                </tr>
                <!-- ALT -->
                <tr>
                    <td class="border border-gray-700 text-center">
                        ALT
                    </td>
                    <td
                        v-for="(fu, key) in data.fus"
                        :key
                        class="border border-gray-700 text-center"
                        v-text="[].includes(key) ? 'X' : ''"
                    />
                </tr>
                <!-- K/HCO2/Ca/PO/Mg -->
                <tr>
                    <td class="border border-gray-700 text-center">
                        K/HCO<sub>2</sub>/Ca/PO<sub>4</sub>/Mg
                    </td>
                    <td
                        v-for="(fu, key) in data.fus"
                        :key
                        class="border border-gray-700 text-center"
                        v-text="[0,1,2,3,4].includes(key) ? 'X' : ''"
                    />
                </tr>
                <!-- PTH -->
                <tr>
                    <td class="border border-gray-700 text-center">
                        PTH (กรณี > 60)
                    </td>
                    <td
                        v-for="(fu, key) in data.fus"
                        :key
                        class="border border-gray-700 text-center"
                        v-text="[].includes(key) ? 'X' : ''"
                    />
                </tr>
                <!-- UA -->
                <tr>
                    <td class="border border-gray-700 text-center">
                        UA
                    </td>
                    <td
                        class="border border-gray-700 text-center"
                        colspan="2"
                    >
                        X
                    </td>
                    <td
                        v-for="key in 12"
                        :key
                        class="border border-gray-700 text-center"
                        v-text="'X'"
                    />
                </tr>
                <!-- UPCr -->
                <tr>
                    <td class="border border-gray-700 text-center">
                        UPCr
                    </td>
                    <td
                        v-for="(fu, key) in data.fus"
                        :key
                        class="border border-gray-700 text-center"
                        v-text="[2,4,7,9,10,11,12,13].includes(key) ? 'X' : ''"
                    />
                </tr>
                <!-- DSA -->
                <tr>
                    <td class="border border-gray-700 text-center">
                        DSA
                    </td>
                    <td
                        v-for="(fu, key) in data.fus"
                        :key
                        class="border border-gray-700 text-center"
                        v-text="[2,4,7,9,10,11,12,13].includes(key) ? 'X' : ''"
                    />
                </tr>
                <!-- Stool -->
                <tr>
                    <td class="border border-gray-700 text-center">
                        Stool for parasite
                    </td>
                    <td
                        v-for="(fu, key) in data.fus"
                        :key
                        class="border border-gray-700 text-center"
                        v-text="[].includes(key) ? 'X' : ''"
                    />
                </tr>
                <!-- BK -->
                <tr>
                    <td class="border border-gray-700 text-center">
                        BK viral load
                    </td>
                    <td
                        v-for="(fu, key) in data.fus"
                        :key
                        class="border border-gray-700 text-center"
                        v-text="[].includes(key) ? 'X' : ''"
                    />
                </tr>
                <!-- Urine -->
                <tr>
                    <td class="border border-gray-700 text-center">
                        Urine
                    </td>
                    <td
                        v-for="(fu, key) in data.fus"
                        :key
                        class="border border-gray-700 text-center"
                        v-text="[2,3,4,7,9,10,11,12,13].includes(key) ? 'X' : ''"
                    />
                </tr>
                <!-- blood -->
                <tr>
                    <td class="border border-gray-700 text-center">
                        blood
                    </td>
                    <td
                        v-for="(fu, key) in data.fus"
                        :key
                        class="border border-gray-700 text-center"
                        v-text="[2,4,7,9,10,11,12,13].includes(key) ? 'X' : ''"
                    />
                </tr>
                <!-- PSA/PV/pap -->
                <tr>
                    <td class="border border-gray-700 text-center">
                        PSA/PV/pap
                    </td>
                    <td
                        v-for="(fu, key) in data.fus"
                        :key
                        class="border border-gray-700 text-center"
                        v-text="[9,10,11,12,13].includes(key) ? 'X' : ''"
                    />
                </tr>
                <!-- Mammogram -->
                <tr>
                    <td class="border border-gray-700 text-center">
                        Mammogram
                    </td>
                    <td
                        v-for="(fu, key) in data.fus"
                        :key
                        class="border border-gray-700 text-center"
                        v-text="[9,10,11,12,13].includes(key) ? 'X' : ''"
                    />
                </tr>
                <!-- Flu Vaccine -->
                <tr>
                    <td class="border border-gray-700 text-center">
                        Flu Vaccine
                    </td>
                    <td
                        v-for="(fu, key) in data.fus"
                        :key
                        class="border border-gray-700 text-center"
                        v-text="[9,10,11,12,13].includes(key) ? 'X' : ''"
                    />
                </tr>
                <!-- CMV -->
                <tr>
                    <td class="border border-gray-700 text-center">
                        CMV
                    </td>
                    <td
                        v-for="(fu, key) in data.fus"
                        :key
                        class="border border-gray-700 text-center"
                        v-text="[2,3,4,9,10,11,12,13].includes(key) ? 'X' : ''"
                    />
                </tr>
            </tbody>
        </table>
    </div>
</template>

<style scoped>
@page  {
    size: A4;
    font-size: 0.625rem !important;
    line-height: 0.85rem !important;
}

@media print {
    label {
        font-weight: 600;
    }
    div.text-xs {
        font-size: 0.625rem !important;
        line-height: 0.85rem !important;
    }
}
</style>
