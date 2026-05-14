const AD_BLACKLIST = [
    'propellerads.com',
    'adsterra.com',
    'monetag.com',
    'onclickads.net',
    'jads.co',
    'exoclick.com',
    'popads.net',
    'popcash.net',
    'juicyads.com',
    'nofeu.com',
    'vidsrc.cc/ads',
    '2embed.cc/ads',
    'vidnode.net',
    's.shopee.co.id',
    'wg.vaguiosfurors.cfd',
    'tukrd.com',
    'buy.ceklinkbio.com',
    'technotology.com',
    'itvalleynews.com',
    'ludzme.com',
    'aboutnews.com',
    'sukseskan.com',
    'topnews.com',
    'kbglfm.com',
    '34-sportnews.com',
    'howtogetacard.com',
    'yahoo.com',
    'terusmilo.xyz',
    'sorrowfulpsychology.com',
    'gulamerah.online',
    'shopee.co.id',
    'shope.ee'
];

self.addEventListener('fetch', (event) => {
    const url = new URL(event.request.url);
    
    // Cek apakah request atau navigasi menuju ke domain iklan
    const isAd = AD_BLACKLIST.some(domain => url.hostname.includes(domain));
    
    if (isAd) {
        console.log("ServiceWorker: EKSEKUSI IKLAN ->", url.href);
        
        // Jika ini adalah navigasi (pindah halaman), kita batalkan atau kirim ke halaman kosong
        if (event.request.mode === 'navigate') {
            event.respondWith(new Response('<h1>Iklan Diblokir oleh MovieList</h1>', {
                headers: { 'Content-Type': 'text/html' }
            }));
        } else {
            event.respondWith(new Response('', { status: 204 }));
        }
    }
});
