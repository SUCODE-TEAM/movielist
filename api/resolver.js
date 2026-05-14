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
                'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
                'Referer': 'https://vidsrc.cc',
                'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8',
                'Accept-Language': 'en-US,en;q=0.9'
            },
            timeout: 10000
        });
    }

    async resolve(tmdbId, type = 'movie', season, episode) {
        try {
            console.log(`[Resolver Sultan] Mencari Link untuk ${type} ID: ${tmdbId}`);

            const embedPath = type === 'movie'
                ? `/v2/embed/movie/${tmdbId}`
                : `/v2/embed/tv/${tmdbId}/${season}/${episode}`;

            // 1. Ambil Halaman Embed
            const response = await this.client.get(`https://vidsrc.cc${embedPath}`);
            const html = response.data;
            const $ = cheerio.load(html);

            // Cari data-id di berbagai kemungkinan tempat
            let dataId = $('#player_iframe').attr('data-id') ||
                $('div#player_iframe').attr('data-id') ||
                $('div[data-id]').attr('data-id');

            if (!dataId) {
                console.error(`[Resolver Error] Tidak menemukan data-id di HTML. vidsrc mungkin memblokir IP laptop Anda.`);
                return null;
            }

            console.log(`[Resolver] Data-ID ditemukan: ${dataId}`);

            // 2. Ambil AJAX Link
            const ajaxUrl = `https://vidsrc.cc/v2/ajax/embed/${dataId}`;
            const { data: ajaxResponse } = await this.client.get(ajaxUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Referer': `https://vidsrc.cc${embedPath}`
                }
            });

            if (!ajaxResponse || !ajaxResponse.url) {
                console.error(`[Resolver Error] AJAX tidak memberikan URL.`);
                return null;
            }

            const finalUrl = decryptUrl(ajaxResponse.url);
            console.log(`[Resolver Success] URL Terpecahkan!`);

            return {
                url: finalUrl,
                quality: 'auto',
                type: 'hls'
            };
        } catch (error) {
            console.error(`[Resolver Error]:`, error.message);
            return null;
        }
    }
}

module.exports = { VidsrcResolver };

