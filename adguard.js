(function () {
    'use strict';

    const AD_HOSTS = [
        'doubleclick.net',
        'google-analytics.com',
        'googlesyndication.com',
        'googletagmanager.com',
        'adservice.google.com',
        'popads.net',
        'popcash.net',
        'propellerads.com',
        'exoclick.com',
        'adsterra.com',
        'monetag.com',
        'onclickperformance.com',
        'highrevenuegate.com',
        'voolemorp.com',
        'steepto.com',
        'bestcontentfood.top',
        'bidgear.com',
        'protagads.com',
        'jads.co',
        'vdo.ai',
        'yousubtitles.com',
        'nofeu.com',
        'terusmilo.xyz',
        'sorrowfulpsychology.com',
        'gulamerah.online'
    ];

    const AD_PATTERNS = [
        '/ads/',
        '/ad/',
        '/pop/',
        'popunder',
        'popup',
        'prebid',
        'vast',
        'vpaid',
        'preroll',
        'onclick',
        'banner',
        'adserver',
        'adservice',
        'track.php',
        'click.php'
    ];

    const MEDIA_EXTENSIONS = [
        '.m3u8',
        '.ts',
        '.m4s',
        '.mp4',
        '.webm',
        '.vtt',
        '.srt',
        '.mpd'
    ];

    const originalOpen = window.open.bind(window);
    const originalFetch = window.fetch ? window.fetch.bind(window) : null;
    const originalXHROpen = window.XMLHttpRequest && window.XMLHttpRequest.prototype.open;
    const originalXHRSend = window.XMLHttpRequest && window.XMLHttpRequest.prototype.send;

    let playbackGuardEnabled = false;
    let cleanupTimer = null;
    let cleanupObserver = null;

    function normalizeUrl(value) {
        if (!value || typeof value !== 'string') return null;

        try {
            return new URL(value, window.location.href);
        } catch (_) {
            return null;
        }
    }

    function hostMatches(hostname, blockedHost) {
        return hostname === blockedHost || hostname.endsWith(`.${blockedHost}`);
    }

    function isMediaUrl(url) {
        const pathname = url.pathname.toLowerCase();

        if (url.origin === window.location.origin && pathname.startsWith('/api/proxy')) {
            return true;
        }

        return MEDIA_EXTENSIONS.some(extension => pathname.endsWith(extension));
    }

    function shouldBlockUrl(value) {
        const url = normalizeUrl(value);
        if (!url || !/^https?:$/.test(url.protocol) || isMediaUrl(url)) {
            return false;
        }

        const hostname = url.hostname.toLowerCase();
        const href = url.href.toLowerCase();

        return AD_HOSTS.some(host => hostMatches(hostname, host)) ||
            AD_PATTERNS.some(pattern => href.includes(pattern));
    }

    function emptyBlockedResponse() {
        return new Response('', {
            status: 204,
            headers: {
                'X-MovieList-Blocked': '1'
            }
        });
    }

    function blockPopup(url) {
        if (playbackGuardEnabled || shouldBlockUrl(url)) {
            return true;
        }

        return false;
    }

    function cleanupInjectedOverlays() {
        if (!playbackGuardEnabled) return;

        const modal = document.getElementById('trailerModal');
        if (!modal || modal.classList.contains('hidden')) return;

        const protectedSelectors = [
            '.trailer-content',
            '.trailer-player',
            '.modal-close',
            '.sultan-fullscreen-btn',
            '.subtitle-tip',
            '.plyr',
            '#sultan-player',
            'video',
            'iframe'
        ].join(',');

        modal.querySelectorAll('a[href], div, section, aside').forEach(element => {
            if (element.matches(protectedSelectors) || element.closest(protectedSelectors)) {
                return;
            }

            const style = window.getComputedStyle(element);
            const zIndex = Number.parseInt(style.zIndex, 10);
            const rect = element.getBoundingClientRect();
            const takesLargeArea = rect.width > window.innerWidth * 0.45 && rect.height > window.innerHeight * 0.35;
            const floatsAbovePlayer = (style.position === 'fixed' || style.position === 'absolute') && zIndex >= 1000;
            const hasAdName = /(^|[-_])(ad|ads|banner|popup|popunder|sponsor)([-_]|$)/i.test(`${element.id} ${element.className}`);

            if ((floatsAbovePlayer && takesLargeArea) || hasAdName) {
                element.remove();
            }
        });
    }

    function enablePlayback() {
        playbackGuardEnabled = true;
        window.isWatchingFilm = true;

        cleanupInjectedOverlays();

        if (!cleanupTimer) {
            cleanupTimer = window.setInterval(cleanupInjectedOverlays, 700);
        }

        if (!cleanupObserver && document.body) {
            cleanupObserver = new MutationObserver(cleanupInjectedOverlays);
            cleanupObserver.observe(document.body, {
                childList: true,
                subtree: true
            });
        }
    }

    function disablePlayback() {
        playbackGuardEnabled = false;
        window.isWatchingFilm = false;

        if (cleanupTimer) {
            window.clearInterval(cleanupTimer);
            cleanupTimer = null;
        }

        if (cleanupObserver) {
            cleanupObserver.disconnect();
            cleanupObserver = null;
        }
    }

    function registerServiceWorker() {
        if (!('serviceWorker' in navigator)) return;

        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js')
                .then(registration => registration.update())
                .catch(error => console.error('MovieList ad guard service worker failed:', error));
        });
    }

    window.open = function guardedOpen(url, target, features) {
        if (blockPopup(url)) {
            return null;
        }

        return originalOpen(url, target, features);
    };

    if (originalFetch) {
        window.fetch = function guardedFetch(input, init) {
            const url = typeof input === 'string' ? input : input && input.url;

            if (shouldBlockUrl(url)) {
                return Promise.resolve(emptyBlockedResponse());
            }

            return originalFetch(input, init);
        };
    }

    if (originalXHROpen && originalXHRSend) {
        window.XMLHttpRequest.prototype.open = function guardedXHROpen(method, url) {
            this.__movielistBlockedUrl = shouldBlockUrl(url);
            return originalXHROpen.apply(this, arguments);
        };

        window.XMLHttpRequest.prototype.send = function guardedXHRSend() {
            if (this.__movielistBlockedUrl) {
                this.abort();
                return;
            }

            return originalXHRSend.apply(this, arguments);
        };
    }

    window.addEventListener('beforeunload', event => {
        if (!playbackGuardEnabled) return;

        event.preventDefault();
        event.returnValue = '';
    });

    window.MovieListAdGuard = {
        enablePlayback,
        disablePlayback,
        shouldBlockUrl,
        registerServiceWorker
    };

    registerServiceWorker();
})();
