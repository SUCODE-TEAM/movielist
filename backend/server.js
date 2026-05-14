const express = require('express');
const cors = require('cors');
const { VidsrcResolver } = require('./resolver.js');

const app = express();
const port = 3000;
const resolver = new VidsrcResolver();

app.use(cors());
app.use(express.json());

// Halaman Status Depan
app.get('/', (req, res) => {
    res.send('<h1 style="font-family: sans-serif; text-align: center; margin-top: 50px;">🚀 Resolver Sultan MovieList: AKTIF & SIAGA! 🛡️</h1>');
});

app.get('/api/resolve', async (req, res) => {
    const { id, type, s, e } = req.query;

    if (!id) return res.status(400).json({ error: "ID wajib diisi" });

    const result = await resolver.resolve(id, type || 'movie', s, e);

    if (result) {
        res.json(result);
    } else {
        res.status(500).json({ error: "Gagal resolve." });
    }
});

app.listen(port, () => {
    console.log(`🚀 Resolver Sultan (Vanilla JS) berjalan di http://localhost:${port}`);
});
