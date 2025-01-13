<script setup>
import {onMounted} from 'vue';
import JsBarcode from 'jsbarcode';

onMounted(() => {
    document.body.classList.remove('bg-primary');
    JsBarcode('svg.barcode-hn', 'D1060', {
        format: 'CODE128',
        width: 1.15,
        height: 30,
        displayValue: false,
        margin: 0
    });
});
</script>

<template>
    <div class="h-[1.4cm] w-[27.1cm] flex border border-gray-950 font-semibold text-sm">
        <div class="text-center p-1.5 border-r border-gray-950">
            งานเปลี่ยนอวัยวะศิริราช <br> โทร 0-2419-8079
        </div>
        <div class="text-center p-2 flex items-center w-[18.5cm] justify-around border-r border-gray-950">
            <div>NAME xxx xxxxxxxx xxxxxxxxx</div>
            <div>HN xxxxxxxx อ.ปีณิดา</div>
            <div>KT date 11 เมษายน 2567</div>
        </div>
        <div class="w-[1.4cm] -ml-6 rotate-90 text-center p-1 border-t border-gray-950">
            67-38
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
