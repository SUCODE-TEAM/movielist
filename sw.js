// SERVICE WORKER: DINONAKTIFKAN (Agar tidak memblokir jalur film Sultan)
self.addEventListener('fetch', (event) => {
    // Biarkan semua request lewat tanpa filter
    return; 
});
