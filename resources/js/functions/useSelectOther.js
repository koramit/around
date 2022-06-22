import { reactive, ref } from 'vue';

export function useSelectOther() {
    const selectOtherInput = ref(null);
    const selectOther = reactive({
        placeholder: '',
        configs: null,
        input: '',
    });
    const selectOtherClosed = (val, isProperty = false) => {
        if (!val) {
            selectOther.input.setOther('');
            return;
        }

        if (isProperty) {
            selectOther.configs.push({ value: val, label: val });
        } else {
            selectOther.configs.push(val);
        }
        selectOther.input.setOther(val);
    };

    return { selectOtherInput, selectOther, selectOtherClosed };
}