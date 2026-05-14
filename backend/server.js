const express = require('express');
const cors = require('cors');
const axios = require('axios');
const { VidsrcResolver } = require('./resolver.js');

const app = express();
const port = 3000;
const resolver = new VidsrcResolver();

// 1. Logger & PNA Middleware
app.use((req, res, next) => {
    console.log(`>>> [${new Date().toLocaleTimeString()}] ${req.method} ${req.path} from ${req.ip}`);
    
    res.setHeader('Access-Control-Allow-Origin', '*');
    res.setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
    res.setHeader('Access-Control-Allow-Headers', '*');
    
    if (req.headers['access-control-request-private-network']) {
        res.setHeader('Access-Control-Allow-Private-Network', 'true');
    }

    if (req.method === 'OPTIONS') {
        return res.sendStatus(200);
    }
    next();
});

app.use(cors());
app.use(express.json());

app.get('/', (req, res) => {
    res.send('<h1 style="font-family: sans-serif; text-align: center; margin-top: 50px;">🚀 Resolver Sultan Supreme (V5): ONLINE! 🛡️</h1>');
});

app.get('/api/resolve', async (req, res) => {
    const { id, type, s, e } = req.query;
    console.log(`[Resolve Request] ID: ${id}, Type: ${type}`);
    const result = await resolver.resolve(id, type || 'movie', s, e);
    
    if (result && result.url) {
        const host = req.headers.host;
        const proxyUrl = `http://${host}/api/proxy?url=${encodeURIComponent(result.url)}`;
        console.log(`[Resolve Success] Proxy URL Generated`);
        res.json({ ...result, url: proxyUrl });
    } else {
        console.error(`[Resolve Failed] Gagal mendapatkan URL dari Vidsrc`);
        res.status(500).json({ error: "Gagal resolve." });
    }
});

app.get('/api/proxy', async (req, res) => {
    const targetUrl = req.query.url;
    if (!targetUrl) return res.status(400).send("URL required");

    try {
        console.log(`[Proxy] Memulai fetch ke: ${targetUrl.substring(0, 60)}...`);
        
        const response = await axios({
            method: 'get',
            url: targetUrl,
            headers: {
                'Referer': 'https://vidsrc.cc/',
                'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36'
            },
            responseType: (targetUrl.includes('.m3u8') || targetUrl.includes('.vtt')) ? 'text' : 'stream',
            timeout: 15000
        });

        res.set('Access-Control-Allow-Origin', '*');
        res.set('Access-Control-Allow-Private-Network', 'true');

        if (targetUrl.includes('.m3u8')) {
            console.log(`[Proxy] Memproses M3U8 Playlist...`);
            let content = response.data;
            const urlObj = new URL(targetUrl);
            const baseUrl = urlObj.origin + urlObj.pathname.substring(0, urlObj.pathname.lastIndexOf('/') + 1);
            const queryParams = urlObj.search;
            const host = req.headers.host;

            content = content.replace(/URI="([^"]+)"/g, (match, p1) => {
                let fullUri = p1.startsWith('http') ? p1 : baseUrl + p1;
                if (!p1.startsWith('http') && queryParams) {
                    fullUri += (fullUri.includes('?') ? '&' : '?') + queryParams.substring(1);
                }
                return `URI="http://${host}/api/proxy?url=${encodeURIComponent(fullUri)}"`;
            });

            let lines = content.split('\n');
            let rewrittenLines = lines.map(line => {
                line = line.trim();
                if (line.length > 0 && !line.startsWith('#')) {
                    let fullLineUrl = line.startsWith('http') ? line : baseUrl + line;
                    if (!line.startsWith('http') && queryParams && !line.includes('?')) {
                        fullLineUrl += queryParams;
                    }
                    return `http://${host}/api/proxy?url=${encodeURIComponent(fullLineUrl)}`;
                }
                return line;
            });

            res.set('Content-Type', 'application/vnd.apple.mpegurl');
            res.send(rewrittenLines.join('\n'));
        } else {
            if (targetUrl.includes('.ts')) {
                res.set('Content-Type', 'video/MP2T');
            }
            response.data.pipe(res);
        }
    } catch (error) {
        console.error("[Proxy Error]:", error.message);
        res.status(500).send("Proxy failed");
    }
});

app.listen(port, '0.0.0.0', () => {
    console.log(`🚀 Resolver Sultan Supreme V5 berjalan di port ${port}`);
});


