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

function hostMatches(hostname, blockedHost) {
    return hostname === blockedHost || hostname.endsWith(`.${blockedHost}`);
}

function isMediaRequest(url) {
    const pathname = url.pathname.toLowerCase();

    if (url.origin === self.location.origin && pathname.startsWith('/api/proxy')) {
        return true;
    }

    return MEDIA_EXTENSIONS.some(extension => pathname.endsWith(extension));
}

function shouldBlock(url) {
    if (!/^https?:$/.test(url.protocol) || isMediaRequest(url)) {
        return false;
    }

    const hostname = url.hostname.toLowerCase();
    const href = url.href.toLowerCase();

    return AD_HOSTS.some(host => hostMatches(hostname, host)) ||
        AD_PATTERNS.some(pattern => href.includes(pattern));
}

self.addEventListener('install', event => {
    event.waitUntil(self.skipWaiting());
});

self.addEventListener('activate', event => {
    event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', event => {
    let url;

    try {
        url = new URL(event.request.url);
    } catch (_) {
        return;
    }

    if (shouldBlock(url)) {
        event.respondWith(new Response('', {
            status: 204,
            headers: {
                'X-MovieList-Blocked': '1'
            }
        }));
        return;
    }

    event.respondWith(fetch(event.request));
});
