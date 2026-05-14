import axios from 'axios';
import * as cheerio from 'cheerio';

export class VidsrcResolver {
    private client: any;

    constructor() {
        this.client = axios.create({
            headers: {
                'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36',
                'Referer': 'https://vidsrc.cc',
            }
        });
    }

    async resolve(tmdbId: string, type: 'movie' | 'tv' = 'movie', season?: number, episode?: number) {
        try {
            console.log(`[Resolver] Memproses ${type} ID: ${tmdbId}`);
            const embedPath = type === 'movie' 
                ? `/v2/embed/movie/${tmdbId}` 
                : `/v2/embed/tv/${tmdbId}/${season}/${episode}`;
            
            const { data: html } = await this.client.get(`https://vidsrc.cc${embedPath}`);
            const $ = cheerio.load(html);
            const dataId = $('#player_iframe').attr('data-id') || $('div[data-id]').attr('data-id');
            
            if (!dataId) return null;

            const ajaxUrl = `https://vidsrc.cc/v2/ajax/embed/${dataId}`;
            const { data: ajaxResponse } = await this.client.get(ajaxUrl, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            return {
                url: ajaxResponse.url || ajaxResponse.enc_url,
                quality: 'auto',
                type: 'hls'
            };
        } catch (error: any) {
            console.error(`[Resolver Error]:`, error.message);
            return null;
        }
    }
}
