<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TenantAssetBypass
{
    public function handle(Request $request, Closure $next)
    {
        // Jika request nyari aset di /build/assets
        if ($request->is('build/assets/*')) {
            $path = public_path($request->path());

            if (File::exists($path)) {
                $extension = pathinfo($path, PATHINFO_EXTENSION);
                $mimes = [
                    'js'  => 'application/javascript',
                    'css' => 'text/css',
                    'png' => 'image/png',
                    'jpg' => 'image/jpeg',
                    'svg' => 'image/svg+xml'
                ];

                $content = file_get_contents($path);
                $mimeType = $mimes[$extension] ?? 'application/octet-stream';

                header("Content-Type: $mimeType");
                header("Access-Control-Allow-Origin: *");
                echo $content;
                exit; // Langsung hentikan proses di sini
            }
        }

        return $next($request);
    }
}