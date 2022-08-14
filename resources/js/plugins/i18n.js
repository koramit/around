import _get from 'lodash/get';
export default {
    install: (app) => {
        // inject a globally available __() method
        app.config.globalProperties.__ = (key, replace = {}) => {
            let translation = _get(window.translations, key);

            for (let placeholder in replace) {
                translation = translation.replace(`:${placeholder}`, replace[placeholder]);
            }

            return translation ?? key;
        };
    }
};
