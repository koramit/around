import { onBeforeMount } from 'vue';

export function useManageLocale(trans) {
    onBeforeMount(() => {
        if (trans === null) {
            return;
        }

        window.translations = trans;
    });
}