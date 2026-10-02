<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/favicon.ico?v=2" sizes="48x48">
    <link rel="icon" href="/favicon-32.png?v=2" type="image/png" sizes="32x32">
    <link rel="icon" href="/favicon-192.png?v=2" type="image/png" sizes="192x192">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png?v=2">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    @inertiaHead
    @isset($structuredData)
        <script type="application/ld+json">{!! json_encode($structuredData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) !!}</script>
    @endisset
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    @inertia
</body>
</html>
