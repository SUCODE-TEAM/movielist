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
    'yahoo.com'
];

self.addEventListener('fetch', (event) => {
    const url = event.request.url;
    
    // Cek apakah request menuju ke domain iklan
    const isAd = AD_BLACKLIST.some(domain => url.includes(domain));
    
    if (isAd) {
        console.log("ServiceWorker: Iklan diblokir ->", url);
        event.respondWith(new Response('', { status: 204 })); // Kirim respon kosong
    }
});
