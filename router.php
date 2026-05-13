<?php
/**
 * MovieList Demo Router
 * Simple PHP built-in server router untuk demo tanpa full Laravel setup
 */

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// If accessing static files, serve them
if ($uri !== '/' && file_exists(__DIR__ . '/public' . $uri)) {
    return false; // Let PHP's built-in server handle static files
}

// Try to load index.html from public folder
$index_file = __DIR__ . '/public/index.html';
if (file_exists($index_file)) {
    readfile($index_file);
    exit;
}

// Fallback demo page
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieList - Setup Required</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: linear-gradient(135deg, #1d3557 0%, #282b4c 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 40px;
            backdrop-filter: blur(10px);
            text-align: center;
        }
        h1 {
            font-size: 36px;
            margin-bottom: 20px;
        }
        .emoji {
            font-size: 64px;
            margin-bottom: 20px;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
            color: rgba(255, 255, 255, 0.8);
        }
        .steps {
            text-align: left;
            background: rgba(0, 0, 0, 0.2);
            padding: 20px;
            border-radius: 8px;
            margin: 30px 0;
        }
        .step {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .step:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .step-number {
            background: #ff6b6b;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 10px;
        }
        code {
            background: rgba(0, 0, 0, 0.3);
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Monaco', 'Menlo', monospace;
            font-size: 14px;
        }
        .status {
            display: inline-block;
            padding: 8px 16px;
            background: rgba(100, 255, 150, 0.2);
            border: 1px solid #64ff96;
            color: #64ff96;
            border-radius: 6px;
            margin-top: 10px;
            font-size: 14px;
            font-weight: 600;
        }
        .warning {
            background: rgba(255, 200, 0, 0.2);
            border: 1px solid #ffc800;
            color: #ffc800;
        }
        .error-box {
            background: rgba(255, 107, 107, 0.2);
            border: 1px solid #ff6b6b;
            color: #ff6b6b;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .success {
            background: rgba(100, 255, 150, 0.2);
            border: 1px solid #64ff96;
            color: #64ff96;
        }
        button {
            background: linear-gradient(135deg, #ff6b6b, #ee5a6f);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            margin-top: 20px;
            transition: all 0.3s;
        }
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 107, 107, 0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="emoji">🎬</div>
        <h1>MovieList</h1>
        <p>Aplikasi Laravel 13 sedang dalam proses setup...</p>

        <div class="error-box">
            ⚠️ <strong>Setup Diperlukan</strong><br>
            Silakan selesaikan langkah-langkah setup di bawah
        </div>

        <div class="steps">
            <div class="step">
                <span class="step-number">1</span>
                <strong>Install Dependencies</strong><br>
                Jalankan di terminal:<br>
                <code>php composer.phar install --ignore-platform-reqs</code><br>
                <span class="status warning">⏳ Pending</span>
            </div>

            <div class="step">
                <span class="step-number">2</span>
                <strong>Generate App Key</strong><br>
                <code>php artisan key:generate</code><br>
                <span class="status warning">⏳ Pending</span>
            </div>

            <div class="step">
                <span class="step-number">3</span>
                <strong>Setup Database</strong><br>
                <code>php artisan migrate --force</code><br>
                <span class="status warning">⏳ Pending</span>
            </div>

            <div class="step">
                <span class="step-number">4</span>
                <strong>Konfigurasi TMDB API</strong><br>
                Edit file <code>.env</code>:<br>
                <code>TMDB_API_KEY=your_api_key_here</code><br>
                Get key dari: <strong>https://www.themoviedb.org/settings/api</strong><br>
                <span class="status warning">⏳ Pending</span>
            </div>

            <div class="step">
                <span class="step-number">5</span>
                <strong>Jalankan Laravel Server</strong><br>
                <code>php artisan serve</code><br>
                <span class="status warning">⏳ Pending</span>
            </div>
        </div>

        <div style="background: rgba(0, 0, 0, 0.2); padding: 15px; border-radius: 8px; margin: 20px 0;">
            <strong>Status Sistem:</strong><br><br>
            <div class="status success">✓ PHP 8.3.31 Terinstall</div><br>
            <div class="status success">✓ Server Berjalan (Demo Mode)</div><br>
            <div class="status warning">⚠ Laravel Framework - Belum Terinstall</div><br>
            <div class="status warning">⚠ Database - Belum Setup</div><br>
            <div class="status warning">⚠ TMDB API Key - Belum Dikonfigurasi</div>
        </div>

        <p style="margin-top: 30px; font-size: 13px; color: rgba(255, 255, 255, 0.6);">
            📚 Dokumentasi: Baca <strong>SETUP_INSTRUCTIONS.md</strong> di folder project<br>
            untuk panduan setup lengkap dan troubleshooting.
        </p>

        <p style="font-size: 12px; color: rgba(255, 255, 255, 0.4); margin-top: 20px;">
            MovieList v1.0 | Laravel 13 | TMDB API<br>
            Powered by PHP 8.3
        </p>
    </div>
</body>
</html>
<?php
