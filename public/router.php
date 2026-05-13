<?php
/**
 * MovieList Demo Router
 * Simple PHP built-in server router untuk demo tanpa full Laravel setup
 */

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Try to load public/index.html or public/index.php
$public_path = __DIR__ . '/public';
$file = null;

// Remove leading slash for file path
$file_path = ltrim($uri, '/');

// If file exists in public, serve it
if (file_exists($public_path . '/' . $file_path) && is_file($public_path . '/' . $file_path)) {
    return false; // Let PHP's built-in server handle static files
}

// Try index.html or index.php
if (file_exists($public_path . '/index.html')) {
    readfile($public_path . '/index.html');
    exit;
}

if (file_exists($public_path . '/index.php')) {
    require $public_path . '/index.php';
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
    </style>
</head>
<body>
    <div class="container">
        <div class="emoji">🎬</div>
        <h1>MovieList</h1>
        <p>Aplikasi sedang dalam proses setup...</p>

        <div class="steps">
            <div class="step">
                <span class="step-number">1</span>
                <strong>Install Dependencies</strong><br>
                Jalankan: <code>php composer.phar install --ignore-platform-reqs</code><br>
                <span class="status warning">Pending - Dependencies belum terinstall</span>
            </div>

            <div class="step">
                <span class="step-number">2</span>
                <strong>Setup Database</strong><br>
                Jalankan: <code>php artisan migrate --force</code><br>
                <span class="status warning">Pending - Artisan belum tersedia</span>
            </div>

            <div class="step">
                <span class="step-number">3</span>
                <strong>Configure TMDB API</strong><br>
                Edit file <code>.env</code> dan isi: <code>TMDB_API_KEY=your_key</code><br>
                <span class="status warning">Pending - API key belum dikonfigurasi</span>
            </div>

            <div class="step">
                <span class="step-number">4</span>
                <strong>Jalankan Server</strong><br>
                Jalankan: <code>php artisan serve</code><br>
                <span class="status warning">Pending - Server belum berjalan dengan Laravel</span>
            </div>
        </div>

        <p><strong>Status Aplikasi:</strong></p>
        <div class="status">✓ PHP 8.3.31 - OK</div>
        <div class="status warning">⚠ Composer Dependencies - Belum Terinstall</div>
        <div class="status warning">⚠ Laravel Framework - Belum Tersedia</div>
        <div class="status warning">⚠ Database - Belum Setup</div>

        <p style="margin-top: 30px; font-size: 14px; color: rgba(255, 255, 255, 0.6);">
            Untuk informasi lebih lanjut, baca SETUP_INSTRUCTIONS.md
        </p>
    </div>
</body>
</html>
<?php
