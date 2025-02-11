<script setup>

import {onMounted} from 'vue';

defineProps({
    data: {type: Object, required: true}
});

onMounted(() => {
    document.body.classList.remove('bg-primary');
    setTimeout(() => print(), 300);
});
</script>

<template>
    <div
        class="border-2 border-gray-950"
    >
        <div class="grid grid-cols-2 gap-2 p-2">
            <div class="space-y-0.5">
                <p class="flex space-x-1.5 text-xs">
                    <label>Name</label>
                    <span class="w-full text-center">{{ data.patient_name }}</span>
                </p>
                <p class="flex space-x-1 text-xs justify-between">
                    <label class="whitespace-nowrap">Cause of ESRD</label>
                    <span class="text-center whitespace-nowrap truncate w-full">{{ data.cause_of_esrd }}</span>
                    <label class="whitespace-nowrap">Native Bx</label>
                    <span class="text-center whitespace-nowrap truncate w-[1cm]">{{ data.native_biopsy_report }}</span>
                </p>
                <p class="flex space-x-1 text-xs">
                    <label>Nephro</label>
                    <span class="w-full text-center whitespace-nowrap truncate">{{ data.nephrologist }}</span>
                    <label>Sx</label>
                    <span class="w-full text-center whitespace-nowrap truncate">{{ data.surgeon }}</span>
                </p>
                <p class="flex space-x-1 text-xs">
                    <label class="whitespace-nowrap">Pre KT PRC</label>
                    <span class="w-full text-center whitespace-nowrap truncate">{{ data.pre_kt_prc_unit }} unit</span>
                    <label>G</label>
                    <span class="w-full text-center whitespace-nowrap truncate">{{ data.gestation_g }}</span>
                    <label>P</label>
                    <span class="w-full text-center whitespace-nowrap truncate">{{ data.gestation_p }}</span>
                    <label>A</label>
                    <span class="w-full text-center whitespace-nowrap truncate">{{ data.gestation_a }}</span>
                </p>
                <p class="flex space-x-1 text-xs">
                    <label class="whitespace-nowrap">Baseline Cr</label>
                    <span class="w-full text-center whitespace-nowrap truncate">{{ data.baseline_cr }}</span>
                    <label class="whitespace-nowrap">Pre-KT Cr</label>
                    <span class="w-full text-center whitespace-nowrap truncate">{{ data.pre_kt_cr }}</span>
                </p>
                <template v-if="data.donor_type === 'CD'">
                    <p class="flex justify-between space-x-1 text-xs">
                        <label class="whitespace-nowrap">CD is</label>
                        <span class="whitespace-nowrap truncate w-[16.5cm]">{{ data.donor_is }}</span>
                        <label class="whitespace-nowrap">Cause of death</label>
                        <span class="whitespace-nowrap truncate w-full">{{ data.donor_cause_of_death }}</span>
                    </p>
                    <p class="flex justify-between space-x-1 text-xs">
                        <label class="whitespace-nowrap">Clamp time</label>
                        <span class="whitespace-nowrap w-[11cm]">{{ data.clamp_time }}</span>
                        <label class="whitespace-nowrap">Graft function</label>
                        <span class="text-center whitespace-nowrap truncate w-full">{{ data.graft_function }}</span>
                    </p>
                    <p class="flex space-x-1 text-xs">
                        <label class="whitespace-nowrap">Co-hospital</label>
                        <span class="w-full text-center whitespace-nowrap truncate">{{ data.co_recipient_hospital }}</span>
                    </p>
                </template>
                <template v-if="data.donor_type === 'LD'">
                    <p class="flex space-x-1 text-xs">
                        <label class="whitespace-nowrap">LD is</label>
                        <span class="w-full text-center whitespace-nowrap truncate">{{ data.donor_is }}</span>
                    </p>
                    <p class="flex justify-between text-xs">
                        <label class="whitespace-nowrap">Anastomosis time</label>
                        <span class="text-center whitespace-nowrap truncate">{{ data.anastomosis_time_minutes }} min</span>
                        <label>WIT</label>
                        <span class="text-center whitespace-nowrap truncate">{{ data.warm_ischemic_time_minutes }} min</span>
                        <label>CIT</label>
                        <span class="text-center whitespace-nowrap truncate">
                            <span
                                v-if="!data.cold_ischemic_time_hours"
                                class="ml-[0.25cm]"
                            />
                            {{ data.cold_ischemic_time_hours }} hr
                            <span
                                v-if="!data.cold_ischemic_time_minutes"
                                class="ml-[0.25cm]"
                            />
                            {{ data.cold_ischemic_time_minutes }} min
                        </span>
                    </p>
                    <p class="flex space-x-1 text-xs">
                        <label class="whitespace-nowrap">Graft function</label>
                        <span class="w-full text-center whitespace-nowrap truncate">{{ data.graft_function }}</span>
                    </p>
                    <p class="flex space-x-1.5 text-xs">
                        <label>CXM</label>
                        <span class="text-center whitespace-nowrap truncate">{{ data.cxm }}</span>
                    </p>
                </template>
                <p
                    v-else
                    class="flex space-x-1.5 text-xs"
                >
                    <label>CXM</label>
                    <span class="text-center whitespace-nowrap truncate w-[4.75cm]">{{ data.cxm }}</span>
                    <template
                        v-if="data.donor_type === 'CD'"
                    >
                        <label>CIT</label>
                        <span class="text-center whitespace-nowrap truncate">
                            <span
                                v-if="!data.cold_ischemic_time_hours"
                                class="ml-[0.25cm]"
                            />
                            {{ data.cold_ischemic_time_hours }} hr
                            <span
                                v-if="!data.cold_ischemic_time_minutes"
                                class="ml-[0.25cm]"
                            />
                            {{ data.cold_ischemic_time_minutes }} min
                        </span>
                    </template>
                </p>
                <p class="flex space-x-1 text-xs">
                    <label class="whitespace-nowrap">KT Date</label>
                    <span class="w-full text-center whitespace-nowrap truncate">{{ data.date_transplant }}</span>
                    <label class="whitespace-nowrap">this time is</label>
                    <span class="w-full text-center whitespace-nowrap truncate">{{ data.kt_times }} KT</span>
                </p>
            </div>
            <div class="space-y-0.5">
                <p class="flex space-x-1.5 text-xs">
                    <label>HN</label>
                    <span class="w-full text-center">{{ data.hn }}</span>
                    <label>DOB</label>
                    <span class="w-full text-center">{{ data.dob }}</span>
                </p>
                <p class="flex space-x-1.5 text-xs">
                    <label class="whitespace-nowrap">First RRT date</label>
                    <span class="w-full text-center whitespace-nowrap truncate">{{ data.date_first_rrt }}</span>
                    <label>Mode</label>
                    <span class="w-full text-center">{{ data.rrt_mode }}</span>
                </p>
                <p class="flex justify-between space-x-1 text-xs">
                    <label class="whitespace-nowrap">Last PRA I</label>
                    <span class="whitespace-nowrap w-[2cm]">{{ data.last_pra_class_i_percent }}</span>
                    <label>II</label>
                    <span class="whitespace-nowrap w-[2cm]">{{ data.last_pra_class_ii_percent }}</span>
                    <label>Date</label>
                    <span class="whitespace-nowrap truncate w-full">{{ data.date_last_pra }}</span>
                </p>
                <p class="flex justify-between space-x-1 text-xs">
                    <label class="whitespace-nowrap">Peak PRA I</label>
                    <span class="whitespace-nowrap w-[2cm]">{{ data.peak_pra_class_i_percent }}</span>
                    <label>II</label>
                    <span class="whitespace-nowrap w-[2cm]">{{ data.peak_pra_class_ii_percent }}</span>
                    <label>Date</label>
                    <span class="whitespace-nowrap truncate w-full">{{ data.date_peak_pra }}</span>
                </p>
                <p class="flex space-x-1 text-xs">
                    <label class="whitespace-nowrap">MM : A</label>
                    <span class="w-full text-center whitespace-nowrap truncate">{{ data.mismatch_a }}</span>
                    <label>B</label>
                    <span class="w-full text-center whitespace-nowrap truncate">{{ data.mismatch_b }}</span>
                    <label>DR</label>
                    <span class="w-full text-center whitespace-nowrap truncate">{{ data.mismatch_dr }}</span>
                </p>
                <p class="flex space-x-1 text-xs">
                    <label class="whitespace-nowrap">MM : Cw</label>
                    <span class="w-full text-center whitespace-nowrap truncate">{{ data.mismatch_cw }}</span>
                    <label class="whitespace-nowrap">DRB 3-4-5 (52-53-51)</label>
                    <span class="w-full text-center whitespace-nowrap truncate">{{ data.mismatch_drb }}</span>
                </p>
                <p class="flex space-x-1 text-xs">
                    <label class="whitespace-nowrap">MM : DQB1</label>
                    <span class="w-full text-center whitespace-nowrap truncate">{{ data.mismatch_dqb1 }}</span>
                    <label>DPB1</label>
                    <span class="w-full text-center whitespace-nowrap truncate">{{ data.mismatch_dpb1 }}</span>
                    <label>MICA</label>
                    <span class="w-full text-center whitespace-nowrap truncate">{{ data.mismatch_mica }}</span>
                </p>
                <p class="flex space-x-1 text-xs">
                    <label class="whitespace-nowrap">MM : DQA1</label>
                    <span class="w-full text-center whitespace-nowrap truncate">{{ data.mismatch_dqa1 }}</span>
                    <label>DPA1</label>
                    <span class="w-full text-center whitespace-nowrap truncate">{{ data.mismatch_dpa1 }}</span>
                </p>
                <p class="flex space-x-1 text-xs">
                    <label class="whitespace-nowrap">CMV IgG : Donor</label>
                    <span class="w-full text-center whitespace-nowrap truncate">{{ data.donor_cmv_igg }}</span>
                    <label>Recipient</label>
                    <span class="w-full text-center whitespace-nowrap truncate">{{ data.recipient_cmv_igg }}</span>
                </p>
                <p class="flex space-x-1 text-xs">
                    <label class="whitespace-nowrap">Transplant specification</label>
                    <span class="w-full text-center whitespace-nowrap truncate">{{ data.transplant_specification }}</span>
                </p>
            </div>
        </div>
        <hr class="border-t-2 border-gray-950 mt-2">
        <div class="pt-0 text-xs">
            <div class="flex border-b-2 border-gray-950">
                <div class="w-1/6 border-r-2 border-gray-950 p-2">
                    <label>Date Dx</label>
                </div>
                <div class="w-5/6 p-2">
                    <label>Diagnosis & Management</label>
                </div>
            </div>
            <div class="h-[21cm]">
                <div
                    class="flex"
                    v-for="event in data.managements"
                    :key="event"
                >
                    <div class="w-1/6 p-2 text-center">
                        <p>{{ event.date_diagnosis }}</p>
                    </div>
                    <div class="w-5/6 p-2">
                        <div
                            v-html="event.management.replaceAll('\n', '<br>')"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
@page  {
    size: A4;
    margin-left: 1in;
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
