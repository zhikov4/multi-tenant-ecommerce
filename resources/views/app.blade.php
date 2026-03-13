<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        @routes
        @php
            $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
            $mainJs = $manifest['resources/js/app.js']['file'];
            $mainCss = $manifest['resources/js/app.js']['css'][0] ?? null;
        @endphp

        <link rel="stylesheet" href="/build/{{ $mainCss }}">
        <script type="module" src="/build/{{ $mainJs }}"></script>
        
        @inertiaHead
    </head>
    <body class="font-sans antialiased bg-gray-50">
        @inertia
    </body>
</html>