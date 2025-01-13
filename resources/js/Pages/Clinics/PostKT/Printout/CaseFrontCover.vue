<script setup>

import {onMounted, ref} from 'vue';

onMounted(() => {
    document.body.classList.remove('bg-primary');
});

const donorType = ref('LD');
</script>

<template>
    <div
        class="border-2 border-gray-950"
    >
        <div class="grid grid-cols-2 gap-2 p-2">
            <div class="space-y-0.5">
                <p class="flex space-x-1.5 text-xs">
                    <label>Name</label>
                    <span class="w-full text-center">xxx xxxxxx xxxxxxxxxx</span>
                </p>
                <p class="flex space-x-1 text-xs">
                    <label class="whitespace-nowrap">Cause of ESRD</label>
                    <span class="w-full text-center whitespace-nowrap truncate">xxxxxx xxxxxxxxxx xxxxxxxx xxx</span>
                    <label class="whitespace-nowrap">Native Bx</label>
                    <span class="mx-1.5 text-center">Y/N</span>
                </p>
                <p class="flex space-x-1 text-xs">
                    <label>Nephro</label>
                    <span class="w-full text-center whitespace-nowrap truncate">xxx xxxxxxx xxxxxxxx</span>
                    <label>Sx</label>
                    <span class="w-full text-center whitespace-nowrap truncate">xxx xxxxxxx xxxxxxxx</span>
                </p>
                <p class="flex space-x-1 text-xs">
                    <label class="whitespace-nowrap">Pre KT PRC</label>
                    <span class="w-full text-center whitespace-nowrap truncate">xxx unit</span>
                    <label>G</label>
                    <span class="w-full text-center whitespace-nowrap truncate">-</span>
                    <label>P</label>
                    <span class="w-full text-center whitespace-nowrap truncate">-</span>
                    <label>A</label>
                    <span class="w-full text-center whitespace-nowrap truncate">-</span>
                </p>
                <p class="flex space-x-1 text-xs">
                    <label class="whitespace-nowrap">Baseline Cr</label>
                    <span class="w-full text-center whitespace-nowrap truncate">xx.x</span>
                    <label class="whitespace-nowrap">Pre-KT Cr</label>
                    <span class="w-full text-center whitespace-nowrap truncate">xx.x</span>
                </p>
                <template v-if="donorType === 'CD'">
                    <p class="flex justify-between space-x-1 text-xs">
                        <label class="whitespace-nowrap">CD is</label>
                        <span class="whitespace-nowrap truncate">male 28Yo non-Trauma</span>
                        <label class="whitespace-nowrap">Cause of death</label>
                        <span class="whitespace-nowrap truncate">xxxx xxxxxxxxxx</span>
                    </p>
                    <p class="flex justify-between space-x-1 text-xs">
                        <label class="whitespace-nowrap">Clamp time</label>
                        <span class="whitespace-nowrap">xxxx-xx-xx xx:xx</span>
                        <label class="whitespace-nowrap">Graft function</label>
                        <span class="w-full text-center whitespace-nowrap truncate">xxx xxxx xxxxx</span>
                        <!--                        <label class="whitespace-nowrap">Co-hospital</label>
                        <span class="whitespace-nowrap truncate">xxxx xxxxxxx xxxxxxxx xxxxxxxxxxxxxx</span>-->
                    </p>
                    <p class="flex space-x-1 text-xs">
                        <label class="whitespace-nowrap">Co-hospital</label>
                        <span class="w-full text-center whitespace-nowrap truncate">xxxx xxxxxxx xxxxxxxx xxxxxxxxxxxxxx</span>
                    </p>
                </template>
                <template v-if="donorType === 'LD'">
                    <p class="flex space-x-1 text-xs">
                        <label class="whitespace-nowrap">LD is</label>
                        <span class="w-full text-center whitespace-nowrap truncate">หลานสาว female 33Yo</span>
                    </p>
                    <p class="flex space-x-1 text-xs">
                        <label class="whitespace-nowrap">Anastomosis time</label>
                        <span class="w-full text-center whitespace-nowrap truncate">xx min</span>
                        <label>WIT</label>
                        <span class="w-full text-center whitespace-nowrap truncate">xx min</span>
                        <label>CIT</label>
                        <span class="w-full text-center whitespace-nowrap truncate">x hr xx min</span>
                    </p>
                    <p class="flex space-x-1 text-xs">
                        <label class="whitespace-nowrap">Graft function</label>
                        <span class="w-full text-center whitespace-nowrap truncate">xxx xxxx xxxxx</span>
                    </p>
                </template>
                <p class="flex space-x-1.5 text-xs">
                    <label>CXM</label>
                    <span class="w-full text-center whitespace-nowrap truncate">xxx xxxxxx xxxxxxxxxx xxxxxxxx</span>
                </p>
                <p class="flex space-x-1 text-xs">
                    <label class="whitespace-nowrap">KT Date</label>
                    <span class="w-full text-center whitespace-nowrap truncate">xxxx-xx-xx</span>
                    <label class="whitespace-nowrap">this time is</label>
                    <span class="w-full text-center whitespace-nowrap truncate">nth KT</span>
                </p>
            </div>
            <div class="space-y-0.5">
                <p class="flex space-x-1.5 text-xs">
                    <label>HN</label>
                    <span class="w-full text-center">xxxxxxxx</span>
                    <label>DOB</label>
                    <span class="w-full text-center">xxxx-xx-xx</span>
                </p>
                <p class="flex space-x-1.5 text-xs">
                    <label class="whitespace-nowrap">First RRT date</label>
                    <span class="w-full text-center whitespace-nowrap truncate">xxxx-xx-xx</span>
                    <label>Mode</label>
                    <span class="w-full text-center">PD/HD</span>
                </p>
                <p class="flex justify-between space-x-1 text-xs">
                    <label class="whitespace-nowrap">Last PRA I</label>
                    <span class="whitespace-nowrap truncate">xx %</span>
                    <label>II</label>
                    <span class="whitespace-nowrap truncate">xx %</span>
                    <label>Date</label>
                    <span class="whitespace-nowrap truncate">xxxx-xx-xx/xxxx-xx-xx</span>
                </p>
                <p class="flex justify-between space-x-1 text-xs">
                    <label class="whitespace-nowrap">Peak PRA I</label>
                    <span class="whitespace-nowrap truncate">xx %</span>
                    <label>II</label>
                    <span class="whitespace-nowrap truncate">xx %</span>
                    <label>Date</label>
                    <span class="whitespace-nowrap truncate">xxxx-xx-xx/xxxx-xx-xx</span>
                </p>
                <p class="flex space-x-1 text-xs">
                    <label class="whitespace-nowrap">MM : A</label>
                    <span class="w-full text-center whitespace-nowrap truncate">xxx</span>
                    <label>B</label>
                    <span class="w-full text-center whitespace-nowrap truncate">xxx</span>
                    <label>DR</label>
                    <span class="w-full text-center whitespace-nowrap truncate">xxx</span>
                </p>
                <p class="flex space-x-1 text-xs">
                    <label class="whitespace-nowrap">MM : Cw</label>
                    <span class="w-full text-center whitespace-nowrap truncate">xxx</span>
                    <label class="whitespace-nowrap">DRB 3-4-5 (52-53-51)</label>
                    <span class="w-full text-center whitespace-nowrap truncate">xxx</span>
                </p>
                <p class="flex space-x-1 text-xs">
                    <label class="whitespace-nowrap">MM : DQB1</label>
                    <span class="w-full text-center whitespace-nowrap truncate">xxx</span>
                    <label>DPB1</label>
                    <span class="w-full text-center whitespace-nowrap truncate">xxx</span>
                    <label>MICA</label>
                    <span class="w-full text-center whitespace-nowrap truncate">xxx</span>
                </p>
                <p class="flex space-x-1 text-xs">
                    <label class="whitespace-nowrap">MM : DQA1</label>
                    <span class="w-full text-center whitespace-nowrap truncate">xxx</span>
                    <label>DPA1</label>
                    <span class="w-full text-center whitespace-nowrap truncate">xxx</span>
                </p>
                <p class="flex space-x-1 text-xs">
                    <label class="whitespace-nowrap">CMV IgG : Donor</label>
                    <span class="w-full text-center whitespace-nowrap truncate">neg</span>
                    <label>Recipient</label>
                    <span class="w-full text-center whitespace-nowrap truncate">neg</span>
                </p>
                <template v-if="donorType === 'LD'">
                    <p class="flex space-x-1 text-xs">
                        <label class="whitespace-nowrap">Transplant specification</label>
                        <span class="w-full text-center whitespace-nowrap truncate">ABO incompatible + Preemptive</span>
                    </p>
                </template>
                <template v-if="donorType === 'CD'">
                    <p class="flex space-x-1 text-xs">
                        <label class="whitespace-nowrap">Transplant specification</label>
                        <span class="w-full text-center whitespace-nowrap truncate">Dual kidneys</span>
                    </p>
                </template>
            </div>
        </div>
        <hr class="border-t-2 border-gray-950 mt-2">
        <div class="pt-0 text-xs">
            <div class="flex">
                <div class="w-1/6 border-r-2 border-gray-950 p-2">
                    <label>Date Dx</label>
                </div>
                <div class="w-5/6 p-2">
                    <label>Diagnosis & Management</label>
                </div>
            </div>
            <div
                class="flex"
                v-for="event in 8"
                :key="event"
            >
                <div class="w-1/6 border-r-2 border-t-2 border-gray-950 p-2 text-center">
                    xxxx-xx-xx
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                </div>
                <div class="w-5/6 border-t-2 border-gray-950 p-2">
                    <div>
                        detail....
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
