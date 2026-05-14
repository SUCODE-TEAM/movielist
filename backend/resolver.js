const axios = require('axios');
const cheerio = require('cheerio');

/**
 * RC4 Decryption Logic (Kunci Sultan Edition)
 */
function rc4(key, str) {
    let s = [], j = 0, x, res = '';
    for (let i = 0; i < 256; i++) {
        s[i] = i;
    }
    for (let i = 0; i < 256; i++) {
        j = (j + s[i] + key.charCodeAt(i % key.length)) % 256;
        x = s[i];
        s[i] = s[j];
        s[j] = x;
    }
    let i = 0;
    j = 0;
    for (let y = 0; y < str.length; y++) {
        i = (i + 1) % 256;
        j = (j + s[i]) % 256;
        x = s[i];
        s[i] = s[j];
        s[j] = x;
        res += String.fromCharCode(str.charCodeAt(y) ^ s[(s[i] + s[j]) % 256]);
    }
    return res;
}

function decryptUrl(encrypted) {
    // Daftar Kunci Sultan yang berhasil dicuri Intel
    const keys = [
        'dawQCziL2v', 'E1KyOcIMf9v7XHg', 'gMvO97yE1cfKIXH', 'thDz4uPKGSYW',
        'ZSsbx4NtMpOoCh', 'ZCo4MthpsNxSOb', 'QIP5jcuvYEKdG', 'fH0n3GZDeKCE6', '0GCn6e3ZfDKEH'
    ];

    try {
        // 1. Decode Base64
        let data = Buffer.from(encrypted, 'base64').toString('binary');
        
        // 2. Coba dekripsi dengan semua kunci sampai berhasil
        for (let key of keys) {
            let decrypted = rc4(key, data);
            if (decrypted.startsWith('http')) {
                return decrypted;
            }
        }
    } catch (e) {
        console.error("Gagal Dekripsi:", e.message);
    }
    return encrypted;
}

class VidsrcResolver {
    constructor() {
        this.client = axios.create({
            headers: {
                'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36',
                'Referer': 'https://vidsrc.cc',
            }
        });
    }

    async resolve(tmdbId, type = 'movie', season, episode) {
        try {
            console.log(`[Resolver Sultan] Memproses ${type} ID: ${tmdbId}`);
            
            // 1. Ambil Embed Page
            const embedPath = type === 'movie' 
                ? `/v2/embed/movie/${tmdbId}` 
                : `/v2/embed/tv/${tmdbId}/${season}/${episode}`;
            
            const { data: html } = await this.client.get(`https://vidsrc.cc${embedPath}`);
            const $ = cheerio.load(html);
            const dataId = $('#player_iframe').attr('data-id') || $('div[data-id]').attr('data-id');
            
            if (!dataId) return null;

            // 2. Ambil Kado Terbungkus (Encrypted AJAX)
            const ajaxUrl = `https://vidsrc.cc/v2/ajax/embed/${dataId}`;
            const { data: ajaxResponse } = await this.client.get(ajaxUrl, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            // 3. BUKA KADO (Dekripsi RC4)
            const encryptedUrl = ajaxResponse.url || ajaxResponse.enc_url || ajaxResponse.result?.url;
            const finalUrl = decryptUrl(encryptedUrl);

            console.log(`[Resolver Sultan] BERHASIL! Link Bening: ${finalUrl.substring(0, 50)}...`);

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
