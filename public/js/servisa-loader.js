(() => {
    'use strict';

    const overlay = document.querySelector('[data-servisa-loader-overlay]');
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    let activeFetches = 0;

    const initialiseAnimation = (root) => {
        if (!root || root.dataset.lottieReady === 'true' || !window.lottie) {
            return;
        }

        const container = root.querySelector('[data-servisa-lottie]');
        const path = root.dataset.animationPath;

        if (!container || !path) {
            return;
        }

        const animation = window.lottie.loadAnimation({
            container,
            renderer: 'svg',
            loop: !reducedMotion.matches,
            autoplay: !reducedMotion.matches,
            path,
            rendererSettings: {
                preserveAspectRatio: 'xMidYMid meet',
            },
        });

        if (reducedMotion.matches) {
            animation.addEventListener('DOMLoaded', () => animation.goToAndStop(0, true));
        }

        root.dataset.lottieReady = 'true';
    };

    const initialiseAnimations = () => {
        document.querySelectorAll('[data-servisa-loader-overlay], [data-servisa-loader-inline]')
            .forEach(initialiseAnimation);
    };

    const restoreForms = () => {
        document.querySelectorAll('form[data-servisa-submitting="true"]').forEach((form) => {
            delete form.dataset.servisaSubmitting;
        });

        document.querySelectorAll('[data-servisa-loader-disabled="true"]').forEach((button) => {
            button.disabled = false;
            delete button.dataset.servisaLoaderDisabled;
        });
    };

    window.showServisaLoader = (text = 'Memproses...') => {
        if (!overlay) {
            return;
        }

        const status = overlay.querySelector('[data-servisa-loader-text]');
        if (status) {
            status.textContent = text;
        }

        initialiseAnimation(overlay);
        overlay.hidden = false;
        overlay.setAttribute('aria-hidden', 'false');
        document.body.classList.add('servisa-loader-active');
    };

    window.hideServisaLoader = () => {
        if (overlay) {
            overlay.hidden = true;
            overlay.setAttribute('aria-hidden', 'true');
        }

        document.body.classList.remove('servisa-loader-active');
        restoreForms();
    };

    document.addEventListener('submit', (event) => {
        const form = event.target;

        if (!(form instanceof HTMLFormElement) || event.defaultPrevented || form.dataset.noServisaLoader !== undefined) {
            return;
        }

        if ((form.method || 'get').toLowerCase() === 'get') {
            return;
        }

        if (form.dataset.servisaSubmitting === 'true') {
            event.preventDefault();
            return;
        }

        form.dataset.servisaSubmitting = 'true';
        form.querySelectorAll('button[type="submit"], input[type="submit"]').forEach((button) => {
            button.disabled = true;
            button.dataset.servisaLoaderDisabled = 'true';
        });

        window.showServisaLoader(form.dataset.servisaLoaderText || 'Memproses...');
    });

    if (typeof window.fetch === 'function') {
        const nativeFetch = window.fetch.bind(window);

        window.fetch = async (resource, options = {}) => {
            const loaderText = options.servisaLoaderText || 'Memuat data...';
            const skipLoader = options.servisaLoader === false;
            const fetchOptions = { ...options };
            delete fetchOptions.servisaLoader;
            delete fetchOptions.servisaLoaderText;

            if (!skipLoader) {
                activeFetches += 1;
                window.showServisaLoader(loaderText);
            }

            try {
                return await nativeFetch(resource, fetchOptions);
            } finally {
                if (!skipLoader) {
                    activeFetches = Math.max(0, activeFetches - 1);
                    if (activeFetches === 0) {
                        window.hideServisaLoader();
                    }
                }
            }
        };
    }

    window.addEventListener('pageshow', () => {
        activeFetches = 0;
        window.hideServisaLoader();
    });

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initialiseAnimations, { once: true });
    } else {
        initialiseAnimations();
    }
})();
