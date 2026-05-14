const AD_DOMAINS = [
    'doubleclick.net', 'google-analytics.com', 'googlesyndication.com',
    'popads.net', 'popcash.net', 'propellerads.com', 'exoclick.com',
    'a.bestcontentfood.top', 'b.bestcontentfood.top', 'mads.com',
    'onclickperformance.com', 'bet9ja.com', '1xbet.com', 'adsterra.com',
    'vidsrc.stream', 'vidsrc.xyz/ads', 'monetag.com', 'rxtv.site', 'yousubtitles.com',
    'protagads.com', 'bidgear.com', 'jads.co', 'vdo.ai', 'cloudup.com', 'videasy.net',
    'highrevenuegate.com', 'voolemorp.com', 'steepto.com', 'vignette.wikia.nocookie.net'
];


const AD_PATTERNS = [
    '/ads/', '/pop/', 'vast', 'click', 'track', 'analytic', 'videasy.net/ads', 'videasy.net/pop',
    'script.js?v=', 'layer.js', 'check.js', 'detect.js'
];

self.addEventListener('install', (event) => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(clients.claim());
});

self.addEventListener('fetch', (event) => {
    const url = new URL(event.request.url);
    
    // Cek domain iklan
    const isAdDomain = AD_DOMAINS.some(domain => url.hostname.includes(domain));
    
    // Cek pola iklan di URL
    const isAdPattern = AD_PATTERNS.some(pattern => url.href.toLowerCase().includes(pattern.toLowerCase()));
    
    if (isAdDomain || isAdPattern) {
        // JANGAN blokir manifest (.m3u8), segmen (.ts), atau file utama player (.js utama)
        // Kita hanya blokir script yang mencurigakan
        const isCriticalFile = url.href.includes('.m3u8') || url.href.includes('.ts') || 
                               (url.hostname.includes('videasy.net') && url.pathname.includes('/movie/'));

        if (!isCriticalFile) {
            console.warn(`[Ad-Block] Memblokir request iklan: ${url.href.substring(0, 70)}...`);
            event.respondWith(new Response('', { status: 204 }));
            return;
        }
    }

    event.respondWith(fetch(event.request).catch(() => fetch(event.request, { mode: 'no-cors' })));
});



