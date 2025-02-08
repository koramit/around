import debounce from 'lodash/debounce';
import {reactive} from 'vue';
import {usePage} from '@inertiajs/vue3';

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
                console.log(error.response);
                formState.state = 'error';
                formState.error = error;
                if (error.response.status === 419) {
                    window.location.reload();
                } else if (error.response.status === 422) {
                    let errors = {};
                    Object.keys(error.response.data.errors).forEach(k => errors[k] = error.response.data.errors[k][0]);
                    usePage().props.errors = {...errors};
                }
            });
    }, 2000);

    return { autosave, formState };
}
