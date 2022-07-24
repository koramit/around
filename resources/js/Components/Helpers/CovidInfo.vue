<template>
    <div
        role="alert"
        class="border-t-4 rounded-b p-4 shadow-sm bg-white border-yellow-400 "
    >
        <div class="flex items-center text-yellow-400">
            <div class="w-1/6 flex justify-center">
                <IconInfoCircle class="w-6 h-6 md:w-10 md:h-10 lg:w-16 lg:h-16"/>
            </div>
            <div class="w-5/6 lg:text-lg">
                <p class="font-semibold">
                    COVID-19 Information
                </p>
            </div>
        </div>
        <div class="mt-4 md:mt-5 bg-gray-100 rounded shadow p-2 md:p-4">
            <div
                class="flex items-center justify-between pb-2 md:pb-4 xl:pb-8"
                :class="{'border-b border-accent': labs.labs?.length}"
            >
                <label class="form-label !mb-0">
                    siriraj test :
                    <span class="italic" v-if="!labs.ok">ERROR</span>
                    <span class="italic" v-else-if="!labs.found">No test</span>
                </label>
                <label class="text-sm italic text-complement">{{ labs.when }}</label>
            </div>
            <div
                v-if="labs.labs?.length"
                v-for="(lab, key) in labs.labs"
                :key="key"
                class="py-4 odd:border-t odd:border-b border-dashed last:!border-b-0">
                <div class="md:hidden space-y-2">
                    <div class="flex justify-between">
                        <p class="font-medium text-complement">{{ lab.date_lab }} </p>
                        <p
                            class="italic"
                            :class="{
                            'text-red-400': lab.result === 'Detected',
                            'text-amber-400': lab.result === 'Inconclusive',
                        }"
                        >{{ lab.result }} </p>
                    </div>
                    <p>{{ lab.name }} </p>
                </div>
                <div class="hidden md:flex space-x-4">
                    <p class="font-medium text-complement">{{ lab.date_lab }} </p>
                    <p>{{ lab.name }} </p>
                    <p
                        class="italic"
                        :class="{
                            'text-red-400': lab.result === 'Detected',
                            'text-amber-400': lab.result === 'Inconclusive',
                        }"
                    >{{ lab.result }} </p>
                </div>
            </div>
        </div>
        <div class="mt-4 md:mt-5 bg-gray-100 rounded shadow p-2 md:p-4">
            <div
                class="flex items-center justify-between pb-2 md:pb-4 xl:pb-8"
                :class="{'border-b border-accent': vaccinations.vaccinations?.length}"
            >
                <label class="form-label !mb-0">
                    Vaccination :
                    <span class="italic" v-if="!vaccinations.ok">ERROR</span>
                    <span class="italic" v-else-if="!vaccinations.found">No data</span>
                    <span class="italic" v-else-if="!vaccinations.vaccinations.length">Unvaccinated</span>
                </label>
                <label class="text-sm italic text-complement">{{ vaccinations.when }}</label>
            </div>
            <div
                v-if="vaccinations.vaccinations?.length"
                v-for="(vaccine, key) in vaccinations.vaccinations"
                :key="key"
                class="py-4 odd:border-t odd:border-b border-dashed last:!border-b-0">
                <div class="md:hidden">
                    <div class="md:hidden space-y-2">
                        <div class="flex justify-between">
                            <p class="font-medium text-complement">{{ vaccine.date }} </p>
                            <p class="italic text-blue-400">{{ vaccine.brand }} </p>
                        </div>
                        <p>{{ vaccine.place }} </p>
                    </div>
                </div>
                <div class="hidden md:flex md:space-x-4">
                    <p class="font-medium text-complement whitespace-nowrap">{{ vaccine.date }} </p>
                    <p>{{ vaccine.place }} </p>
                    <p class="italic text-blue-400">{{ vaccine.brand }} </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import IconInfoCircle from './Icons/IconInfoCircle.vue';

const props = defineProps({
    configs: {type: Object, required: true},
})

const handleError = (error) => {
    console.log(error);
    return {ok: false};
};

let cid = props.configs.cid;
const vaccinations = cid
    ? await window.axios
        .post(props.configs.route_vaccine, {cid: cid})
        .then(res => res.data)
        .catch(handleError)
    : {ok:true,found:false} ;
const labs = await window.axios
    .post(props.configs.route_lab, {hn: props.configs.hn})
    .then(res => res.data)
    .catch(handleError);
</script>
