<template>
    <div>
        <label class="form-label">{{ label }} :</label>
        <div
            class="p-2 rounded bg-primary-darker text-complement italic"
            v-if="String(data).startsWith('<p>')"
            v-html="data"
        />
        <p
            class="p-2 rounded bg-primary-darker text-complement italic"
            v-else
            v-html="data"
        />
    </div>
</template>

<script setup>
defineProps({
    label: {type: [String,null], default: null},
    data: {type: [String,Number,null], default: null}
});
</script>
