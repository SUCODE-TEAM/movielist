import express from 'express';
import cors from 'cors';
import { VidsrcResolver } from './resolver.ts';

const app = express();
const port = 3000;
const resolver = new VidsrcResolver();

app.use(cors());
app.use(express.json());

app.get('/api/resolve', async (req: any, res: any) => {
    const { id, type, s, e } = req.query;

    if (!id) {
        return res.status(400).json({ error: "TMDB ID wajib diisi" });
    }

    const result = await resolver.resolve(
        id as string, 
        (type as 'movie' | 'tv') || 'movie',
        s ? parseInt(s as string) : undefined,
        e ? parseInt(e as string) : undefined
    );

    if (result) {
        res.json(result);
    } else {
        res.status(500).json({ error: "Gagal me-resolve video." });
    }
});

app.listen(port, () => {
    console.log(`🚀 Resolver Server (Modern) berjalan di http://localhost:${port}`);
});
