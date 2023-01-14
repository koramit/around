import {ref} from 'vue';

const actionStore = ref({});

export function useActionStore() {
    const setActionStore = (action) => actionStore.value = {...action};
    const resetActionStore = () => actionStore.value = {};

    return {actionStore, setActionStore, resetActionStore};
}
