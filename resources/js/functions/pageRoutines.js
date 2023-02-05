/**
 * 1. remove page loading if needed
 * 2. add check session timeout functional
 * 3. Manage locale
 */

import { nextTick, onBeforeMount, onMounted, onUnmounted } from 'vue';

export function pageRoutines() {
    var lastTimeCheckSessionTimeout = Date.now();

    const baseUrl = document.querySelector('meta[name=base-url]').content;
    const endpoint = baseUrl + '/check-timeout';
    const sessionLifetimeSeconds = parseInt(document.querySelector('meta[name=session-lifetime-seconds]').content);
    const checkSessionTimeoutOnFocus = () => {
        let timeDiff = Date.now() - lastTimeCheckSessionTimeout;
        if ((timeDiff) > (sessionLifetimeSeconds)) {
            window.axios
                .post(endpoint)
                .then(() => lastTimeCheckSessionTimeout = Date.now())
                .catch(() => location.reload());
        }
    };

    onBeforeMount(async () => {
        await window.axios
            .post(baseUrl + '/translations')
            .then(response => {
                if (response.data.translations === false) {
                    return;
                }
                window.translations = response.data.translations;
            })
            .catch(function (error) {
                if (error.response.status === 419) {
                    window.location.reload();
                } else {
                    console.error(error);
                }
            });
    });

    onMounted(() => {
        window.addEventListener('focus', checkSessionTimeoutOnFocus);

        nextTick(() => {
            const pageLoadingIndicator = document.getElementById('page-loading-indicator');
            if (pageLoadingIndicator) {
                pageLoadingIndicator.remove();
            }
        });
    });

    onUnmounted(() => {
        window.removeEventListener('focus', checkSessionTimeoutOnFocus);
    });
}
