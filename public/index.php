<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// -------------------------------------------------------------------------
// JURUS NUKLIR ANTI-404 ASSET (PHP MURNI)
// -------------------------------------------------------------------------
if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/build/assets/') !== false) {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $path = __DIR__ . $uri;

    if (file_exists($path)) {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $mimes = [
            'js'  => 'application/javascript',
            'css' => 'text/css',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'svg' => 'image/svg+xml'
        ];
        header("Content-Type: " . ($mimes[$ext] ?? 'application/octet-stream'));
        header("Access-Control-Allow-Origin: *");
        readfile($path);
        exit;
    }
}
// -------------------------------------------------------------------------

// Cek apakah aplikasi sedang dalam mode pemeliharaan...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Daftarkan auto-loader Composer...
require __DIR__.'/../vendor/autoload.php';

// Jalankan aplikasi Laravel...
(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());