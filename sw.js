// DAFTAR PUTIH: Hanya domain ini yang boleh diakses
const WHITE_LIST = [
    'themoviedb.org',
    'tmdb.org',
    'vidsrc.cc',
    'vidsrc.me',
    'vidsrc.pro',
    'vidsrc.xyz',
    'vidsrc.to',
    'vidsrc.in',
    'vidsrc.net',
    'google.com',
    'gstatic.com',
    'googleapis.com',
    'cloudflare.com',
    'akamaihd.net'
];

self.addEventListener('fetch', (event) => {
    const url = new URL(event.request.url);
    const isOurSite = url.origin === self.location.origin;
    const isWhiteListed = WHITE_LIST.some(domain => url.hostname.includes(domain));

    // Jika bukan dari web kita dan tidak ada di daftar putih, BLOKIR TOTAL
    if (!isOurSite && !isWhiteListed) {
        console.warn("DaruratMiliter: Memblokir domain ilegal ->", url.hostname);
        event.respondWith(new Response('', { status: 204 }));
    }
});
