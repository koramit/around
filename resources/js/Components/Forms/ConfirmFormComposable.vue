<template>
    <Teleport to="body">
        <ModalDialog
            width-mode="form-cols-1"
            ref="modal"
        >
            <template #header>
                <div class="font-semibold text-complement">
                    {{ config.heading }}
                </div>
            </template>
            <template #body>
                <div class="py-4 my-2 md:py-6 md:my-4 border-t border-b border-accent">
                    <p
                        class="font-semibold text-yellow-400"
                        v-html="config.confirmText"
                    />
                    <template v-if="config.requireReason">
                        <FormInput
                            class="mt-4 md:mt-6"
                            v-model="config.reason"
                            placeholder="reason"
                            name="reason"
                            ref="reasonInput"
                        />
                    </template>
                </div>
            </template>
            <template #footer>
                <SpinnerButton
                    class="btn btn-accent w-full mt-6"
                    @click="confirmed"
                    :disabled="config.requireReason && !config.reason"
                >
                    Confirm
                </SpinnerButton>
            </template>
        </ModalDialog>
    </Teleport>
</template>

<script setup>

import {nextTick, reactive, ref} from 'vue';
import FormInput from '../Controls/FormInput.vue';
import SpinnerButton from '../Controls/SpinnerButton.vue';
import ModalDialog from '../Helpers/ModalDialog.vue';

const emits = defineEmits(['confirmed']);

const config = reactive({
    heading: 'Please confirm',
    confirmText: 'Please confirm action or close to cancel',
    requireReason: false,
    reason: null,
});
const reasonInput = ref(null);
const modal = ref(null);

const open = (options) => {
    config.heading = options.heading ?? 'Please confirm';
    config.confirmText = options.confirmText ?? 'Please confirm action or close to cancel';
    config.requireReason = options.requireReason ?? false;
    config.reason = null;
    modal.value.open();

    if (config.requireReason) {
        nextTick(() => reasonInput.value.focus());
    }
};

const confirmed = () => {
    emits('confirmed', config.reason);
    modal.value.close();
};

defineExpose({ open });
</script>
