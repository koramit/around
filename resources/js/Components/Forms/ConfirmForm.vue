<template>
    <Teleport to="body">
        <ModalDialog
            width-mode="form-cols-1"
            ref="modal"
        >
            <template #header>
                <div class="font-semibold text-complement">
                    {{ heading }}
                </div>
            </template>
            <template #body>
                <div class="py-4 my-2 md:py-6 md:my-4 border-t border-b border-accent">
                    <p
                        class="font-semibold text-yellow-400"
                        v-html="confirmText"
                    />
                    <template v-if="requireReason">
                        <FormInput
                            class="mt-4 md:mt-6"
                            v-model="reason"
                            placeholder="reason"
                            name="reason"
                        />
                    </template>
                </div>
            </template>
            <template #footer>
                <SpinnerButton
                    class="btn btn-accent w-full mt-6"
                    @click="confirmed"
                    :disabled="requireReason && !reason"
                >
                    Confirm
                </SpinnerButton>
            </template>
        </ModalDialog>
    </Teleport>
</template>

<script setup>
import { ref } from 'vue';
import ModalDialog from '../Helpers/ModalDialog.vue';
import SpinnerButton from '../Controls/SpinnerButton.vue';
import FormInput from '../Controls/FormInput.vue';
import { usePage } from '@inertiajs/inertia-vue3';

const heading = ref(null);
const confirmText = ref(null);
const requireReason = ref(null);
const reason = ref(null);

const modal = ref(null);
let confirmedEvent =null;
const open = (options) => {
    heading.value = options.heading ?? 'Please confirm';
    confirmText.value = options.confirmText ?? 'Please confirm action or close to cancel';
    confirmedEvent = options.confirmedEvent ?? '';
    requireReason.value = options.requireReason ?? false;
    modal.value.open();
};

const confirmed = () => {
    usePage().props.value.event.payload = reason.value;
    usePage().props.value.event.name = confirmedEvent;
    usePage().props.value.event.fire = + new Date();
    modal.value.close();

    reason.value = null;
};

defineExpose({ open });
</script>