<template>
    <Teleport to="body">
        <ModalDialog
            ref="modalDialog"
            width-mode="form-cols-1"
            @opened="$refs.otherItemInput.focus()"
            @closed="$emit('closed', otherItemModel)"
        >
            <template #header>
                <div class="font-semibold text-dark-theme-light">
                    {{ placeholder }}
                </div>
            </template>
            <template #body>
                <div class="py-4 my-2 md:py-6 md:my-4 border-t border-b border-bitter-theme-light">
                    <FormInput
                        v-model="otherItemModel"
                        name="otherItemModel"
                        ref="otherItemInput"
                    />
                </div>
            </template>
            <template #footer>
                <div class="flex justify-end items-center">
                    <button
                        class="btn btn-accent px-5"
                        @click="$refs.modalDialog.close()"
                        :disabled="!otherItemModel"
                    >
                        Add
                    </button>
                </div>
            </template>
        </ModalDialog>
    </Teleport>
</template>

<script setup>
import FormInput from '../Controls/FormInput.vue';
import ModalDialog from '../Helpers/ModalDialog.vue';
import { ref } from 'vue';

defineEmits(['closed']);
defineProps({
    placeholder: { type: String, default: 'โปรดระบุ' },
});
const otherItemInput = ref(null);
const otherItemModel = ref('');
const modalDialog = ref(null);
const open = () => {
    modalDialog.value.open();
};
defineExpose({open});
</script>
