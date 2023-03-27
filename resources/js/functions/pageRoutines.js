/**
 * 1. remove page loading if needed
 * 2. add check session timeout functional
 * 3. Manage locale
 */

import { nextTick, onBeforeMount, onMounted, onUnmounted } from 'vue';

export function pageRoutines() {
    let lastTimeCheckSessionTimeout = Date.now();

    const sessionLifetimeSeconds = parseInt(document.querySelector('meta[name=session-lifetime-seconds]').content);
    const checkSessionTimeoutOnFocus = () => {
        let timeDiff = Date.now() - lastTimeCheckSessionTimeout;
        if ((timeDiff) > (sessionLifetimeSeconds)) {
            window.axios
                .post('/check-timeout')
                .then(() => lastTimeCheckSessionTimeout = Date.now())
                .catch(() => window.location.reload());
        }
    };

    onBeforeMount(async () => {
        await window.axios
            .post('/translations')
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

        // noinspection JSIgnoredPromiseFromCall
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
