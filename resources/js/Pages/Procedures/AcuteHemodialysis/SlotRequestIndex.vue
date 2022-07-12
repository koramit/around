<template>
    <div>
        <!-- table -->
        <div class="bg-white rounded shadow overflow-x-auto hidden md:block">
            <table class="w-full whitespace-nowrap">
                <tr class="text-left font-semibold text-complement">
                    <th
                        class="px-6 pt-6 pb-4"
                        v-for="column in ['HN', 'Name', 'Request', 'By']"
                        :key="column"
                        v-text="column"
                        :colspan="column === 'MD' ? 2:1"
                    />
                </tr>
                <tr
                    class="focus-within:bg-primary-darker"
                    v-for="(request, key) in requests"
                    :key="key"
                >
                    <td
                        class="px-6 py4 border-t"
                        v-for="field in ['hn', 'patient_name', 'request', 'requester']"
                        :key="field"
                        v-text="request[field]"
                    />
                    <td class="border-t flex">
                        <Link
                            class="px-4 py-2 flex items-center focus:text-primary-darker"
                            href="#"
                        >
                            <div class="action-icon">
                                <IconCheckCircle class="w-4 h-4 text-green-600" />
                            </div>
                        </Link>
                        <Link
                            class="px-4 py-2 flex items-center focus:text-primary-darker"
                            href="#"
                        >
                            <div class="action-icon">
                                <IconTimesCircle class="w-4 h-4 text-red-600" />
                            </div>
                        </Link>
                        <Link
                            class="px-4 py-2 flex items-center focus:text-primary-darker"
                            href="#"
                        >
                            <div class="action-icon">
                                <IconTrash class="w-4 h-4 text-red-600" />
                            </div>
                        </Link>
                    </td>
                </tr>
            </table>
        </div>

        <!-- today slot  -->
        <h2 class="mt-4 md:mt-8 form-label text-lg italic text-complement scroll-mt-16 md:scroll-mt-8">
            Today Slot
        </h2>
        <hr class="my-4 border-b border-accent">
        <div class="md:grid grid-cols-2 gap-4">
            <Transition
                name="slide-fade"
                v-if="slot.hd_unit.length"
            >
                <DialysisSlot
                    :slots="slot.hd_unit"
                />
            </Transition>
            <Transition
                name="slide-fade"
                v-if="slot.ward.length"
            >
                <WardSlot
                    :slots="slot.ward"
                />
            </Transition>
        </div>

        <!-- request form  -->
        <h2 class="mt-4 md:mt-8 form-label text-lg italic text-complement scroll-mt-16 md:scroll-mt-8">
            request today extra slot
        </h2>
        <hr class="my-4 border-b border-accent">
        <div class="grid md:grid-cols-2 gap-2 lg:gap-6">
            <FormAutocomplete
                label="case"
                name="case"
                v-model="order.case"
                :endpoint="endpoints.cases"
                :error="order.errors.case"
                :length-to-start="1"
            />
            <FormAutocomplete
                label="dialysis at"
                name="dialysis_at"
                v-model="order.dialysis_at"
                :endpoint="endpoints.resources_api_wards"
                :error="order.errors.dialysis_at"
                :length-to-start="1"
            />
            <FormAutocomplete
                label="attending"
                name="attending_staff"
                v-model="order.attending_staff"
                :endpoint="endpoints.resources_api_staffs"
                :params="configs.staffs_scope_params"
                :error="order.errors.attending_staff"
                :length-to-start="1"
            />
            <FormSelect
                label="dialysis type"
                name="order_dialysis_type"
                v-model="order.dialysis_type"
                :options="order.dialysis_at && order.dialysis_at.startsWith('ไตเทียม') ? configs.in_unit_dialysis_types : configs.out_unit_dialysis_types"
                :disabled="!order.dialysis_at"
            />
            <div>
                <label class="form-label">patient type</label>
                <FormRadio
                    class="grid grid-cols-2 gap-x-2"
                    name="patient_type"
                    v-model="order.patient_type"
                    :options="configs.patient_types"
                    ref="patientTypeInput"
                />
            </div>
        </div>
        <div class="mt-2 lg:mt-0 md:pt-4">
            <SpinnerButton
                class="block w-full text-center btn btn-accent"
                :spin="order.processing"
                :disabled="true"
            >
                REQUEST RESERVE
            </SpinnerButton>
        </div>
    </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/inertia-vue3';
import IconCheckCircle from '../../../Components/Helpers/Icons/IconCheckCircle.vue';
import IconTimesCircle from '../../../Components/Helpers/Icons/IconTimesCircle.vue';
import IconTrash from '../../../Components/Helpers/Icons/IconTrash.vue';
import DialysisSlot from '../../../Partials/Procedures/AcuteHemodialysis/DialysisSlot.vue';
import WardSlot from '../../../Partials/Procedures/AcuteHemodialysis/WardSlot.vue';
import FormAutocomplete from '../../../Components/Controls/FormAutocomplete.vue';
import FormSelect from '../../../Components/Controls/FormSelect.vue';
import FormRadio from '../../../Components/Controls/FormRadio.vue';
import SpinnerButton from '../../../Components/Controls/SpinnerButton.vue';
defineProps({
    requests: { type: Array, required: true },
    slot: { type: Object, required: true },
    configs: { type: Object, required: true },
    endpoints: { type: Object, required: true },
});

// HD orders
const order = useForm({
    case: null,
    dialysis_type: null,
    dialysis_at: null,
    attending_staff: null,
    date_note: null,
    patient_type: null,
});
</script>
