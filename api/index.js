const express = require('express');
const cors = require('cors');
const axios = require('axios');
const { VidsrcResolver } = require('./resolver.js');

const app = express();
const resolver = new VidsrcResolver();

// 1. Logger & PNA Middleware (Disesuaikan untuk Vercel)
app.use((req, res, next) => {
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

app.get('/api', (req, res) => {
    res.send('🚀 MovieList Serverless Resolver: ONLINE');
});

app.get('/api/resolve', async (req, res) => {
    const { id, type, s, e } = req.query;
    const result = await resolver.resolve(id, type || 'movie', s, e);
    
    if (result && result.url) {
        // DI VERCEL: Gunakan URL Vercel saat ini sebagai host proxy
        const host = req.headers.host;
        const protocol = req.headers['x-forwarded-proto'] || 'http';
        const proxyUrl = `${protocol}://${host}/api/proxy?url=${encodeURIComponent(result.url)}`;
        res.json({ ...result, url: proxyUrl });
    } else {
        res.status(500).json({ error: "Gagal resolve." });
    }
});

app.get('/api/proxy', async (req, res) => {
    const targetUrl = req.query.url;
    if (!targetUrl) return res.status(400).send("URL required");

    try {
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
            let content = response.data;
            const urlObj = new URL(targetUrl);
            const baseUrl = urlObj.origin + urlObj.pathname.substring(0, urlObj.pathname.lastIndexOf('/') + 1);
            const queryParams = urlObj.search;
            
            const host = req.headers.host;
            const protocol = req.headers['x-forwarded-proto'] || 'http';

            content = content.replace(/URI="([^"]+)"/g, (match, p1) => {
                let fullUri = p1.startsWith('http') ? p1 : baseUrl + p1;
                if (!p1.startsWith('http') && queryParams) {
                    fullUri += (fullUri.includes('?') ? '&' : '?') + queryParams.substring(1);
                }
                return `URI="${protocol}://${host}/api/proxy?url=${encodeURIComponent(fullUri)}"`;
            });

            let lines = content.split('\n');
            let rewrittenLines = lines.map(line => {
                line = line.trim();
                if (line.length > 0 && !line.startsWith('#')) {
                    let fullLineUrl = line.startsWith('http') ? line : baseUrl + line;
                    if (!line.startsWith('http') && queryParams && !line.includes('?')) {
                        fullLineUrl += queryParams;
                    }
                    return `${protocol}://${host}/api/proxy?url=${encodeURIComponent(fullLineUrl)}`;
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
        res.status(500).send("Proxy failed");
    }
});

// EKSPOR UNTUK VERCEL (PENTING!)
module.exports = app;
