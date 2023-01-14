import {reactive} from 'vue';

export const store = reactive({
    action: {},
    setAction(action = {}) {
        this.action = action;
    }
});
