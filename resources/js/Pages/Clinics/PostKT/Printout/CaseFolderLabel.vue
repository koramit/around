<script setup>
import {onMounted} from 'vue';
import JsBarcode from 'jsbarcode';

const props = defineProps({
    data: {type: Object, required: true}
});

onMounted(() => {
    document.body.classList.remove('bg-primary');
    JsBarcode('svg.barcode-hn', props.data.hn, {
        format: 'CODE128',
        width: 1.15,
        height: 30,
        displayValue: false,
        margin: 0
    });

    setTimeout(() => print(), 300);
});
</script>

<template>
    <div class="h-[1.4cm] w-[27.1cm] flex border border-gray-950 font-semibold text-sm">
        <div class="text-center p-1.5 border-r border-gray-950">
            งานเปลี่ยนอวัยวะศิริราช <br> โทร 0-2419-8079
        </div>
        <div class="text-center p-2 flex items-center w-[18.5cm] justify-around border-r border-gray-950">
            <div>{{ data.patient_name }}</div>
            <div>HN. {{ data.hn }} {{ data.staff }}</div>
            <div>KT date {{ data.date_kt }}</div>
        </div>
        <div class="w-[1.4cm] -ml-6 rotate-90 text-center p-1 border-t border-gray-950">
            {{ data.kt_no }}
        </div>
        <div class="w-[1.4cm] -ml-6 rotate-90 text-center p-1 border-t border-gray-950">
            KT No.
        </div>
        <div class="flex items-center px-2">
            <svg class="barcode-hn" />
        </div>
    </div>
</template>

<style scoped>
@page {
    size: A4 landscape;
}

@media print {
    div.text-xs {
        font-size: 0.625rem !important;
        line-height: 0.85rem !important;
    }
}
</style>
