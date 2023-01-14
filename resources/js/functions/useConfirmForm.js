import {ref} from 'vue';

export function useConfirmForm() {
    const confirmForm = ref(null);
    const openConfirmForm = (config) => confirmForm.value.open(config);
    const confirmed = (reason, callback) => {
        callback(reason);
    };

    return { confirmForm, openConfirmForm, confirmed };
}
