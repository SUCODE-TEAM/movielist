const axios = require('axios');
const cheerio = require('cheerio');

/**
 * RC4 Decryption (Kunci Sultan)
 */
function rc4(key, str) {
    let s = [], j = 0, x, res = '';
    for (let i = 0; i < 256; i++) s[i] = i;
    for (let i = 0; i < 256; i++) {
        j = (j + s[i] + key.charCodeAt(i % key.length)) % 256;
        x = s[i]; s[i] = s[j]; s[j] = x;
    }
    let i = 0; j = 0;
    for (let y = 0; y < str.length; y++) {
        i = (i + 1) % 256;
        j = (j + s[i]) % 256;
        x = s[i]; s[i] = s[j]; s[j] = x;
        res += String.fromCharCode(str.charCodeAt(y) ^ s[(s[i] + s[j]) % 256]);
    }
    return res;
}

function decryptUrl(encrypted) {
    const keys = ['dawQCziL2v', 'E1KyOcIMf9v7XHg', 'gMvO97yE1cfKIXH', 'thDz4uPKGSYW', 'ZSsbx4NtMpOoCh', 'ZCo4MthpsNxSOb', 'QIP5jcuvYEKdG', 'fH0n3GZDeKCE6', '0GCn6e3ZfDKEH'];
    try {
        let data = Buffer.from(encrypted, 'base64').toString('binary');
        for (let key of keys) {
            let decrypted = rc4(key, data);
            if (decrypted.startsWith('http')) return decrypted;
        }
    } catch (e) { }
    return encrypted;
}

class VidsrcResolver {
    constructor() {
        this.client = axios.create({
            headers: {
                'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
                'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8',
                'Accept-Language': 'en-US,en;q=0.9',
                'Cache-Control': 'no-cache',
                'Pragma': 'no-cache'
            },
            timeout: 10000
        });
    }

    async resolve(tmdbId, type = 'movie', season, episode) {
        // Daftar provider untuk rotasi jika gagal
        const providers = [
            { name: 'vidsrc.cc', url: 'https://vidsrc.cc' },
            { name: 'vidsrc.xyz', url: 'https://vidsrc.xyz' },
            { name: 'vidsrc.pm', url: 'https://vidsrc.pm' }
        ];

        for (const provider of providers) {
            try {
                console.log(`[Resolver] Mencoba provider: ${provider.name}`);

                const embedPath = type === 'movie'
                    ? `/v2/embed/movie/${tmdbId}`
                    : `/v2/embed/tv/${tmdbId}/${season}/${episode}`;

                const response = await this.client.get(`${provider.url}${embedPath}`, {
                    headers: { 'Referer': provider.url }
                });
                
                const html = response.data;
                const $ = cheerio.load(html);

                let dataId = $('#player_iframe').attr('data-id') || 
                             $('div#player_iframe').attr('data-id') || 
                             $('[data-id]').attr('data-id');

                if (!dataId) continue;

                const ajaxUrl = `${provider.url}/v2/ajax/embed/${dataId}`;
                const { data: ajaxResponse } = await this.client.get(ajaxUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Referer': `${provider.url}${embedPath}`
                    }
                });

                if (ajaxResponse && ajaxResponse.url) {
                    const finalUrl = decryptUrl(ajaxResponse.url);
                    console.log(`[Resolver Success] Berhasil lewat ${provider.name}`);
                    return { url: finalUrl, quality: 'auto', type: 'hls', provider: provider.name };
                }
            } catch (err) {
                console.warn(`[Resolver Warning] Provider ${provider.name} gagal: ${err.message}`);
            }
        }
        
        console.error(`[Resolver Error] Semua provider gagal.`);
        return null;
    }
}

module.exports = { VidsrcResolver };


