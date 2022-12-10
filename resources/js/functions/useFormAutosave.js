import debounce from 'lodash/debounce';
import {reactive} from 'vue';

const formState = reactive({
    state: '',
    error: {},
});
export function useFormAutosave() {
    const autosave = (form, endpoint) => {
        if (formState.state !== 'saving') {
            formState.state = 'saving';
        }

        autosaveDebounce(form, endpoint);
    };

    const autosaveDebounce = debounce((form, endpoint) => {
        window.axios
            .patch(endpoint, form)
            .then(() => formState.state = 'saved')
            .catch(error => {
                console.log(error);
                formState.state = 'error';
                formState.error = error;
            });
    }, 2000);

    return { autosave, formState };
}
