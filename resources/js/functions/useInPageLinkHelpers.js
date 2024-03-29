export function useInPageLinkHelpers() {
    const isUrl = (url) => {
        if (Array.isArray(url)) {
            return url.includes(location.origin + (location.pathname === '/' ? '' : location.pathname));
        }
        return (location.origin + (location.pathname === '/' ? '' : location.pathname)) === url;
    };
    const smoothScroll = (href) => {
        if (href.startsWith('#')) {
            document.getElementById(href.replace('#', '')).scrollIntoView({
                behavior: 'smooth'
            });
            return;
        }

        const target = document.querySelector(href);
        if (target === undefined || !target) {
            return;
        }
        document.querySelector(href).scrollIntoView({
            behavior: 'smooth'
        });
    };

    return { isUrl, smoothScroll };
}
