<!DOCTYPE html>
<html>

<head>
    {{-- minutes to microseconds --}}
    <meta name="session-lifetime-seconds"
        content="{{ Config::get('session.lifetime') * 60000 }}" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    {{-- favicon --}}
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    {{-- script --}}
    @vite('resources/js/app.js')
    @inertiaHead
</head>
<body class="m-0 font-sarabun font-extralight text-gray-700 {{ config('app.env') === 'uat' ? 'bg-uat-env' : 'bg-primary'}}">
    @inertia
    <div id="page-loading-indicator" style="height: 100vh; display: flex; align-items: center; justify-content: center;">
        <svg style="width: 150px; height: 150px;" class="animate-spin text-accent" viewBox="0 0 512 512">
            <path fill="currentColor" d="M494.5 254.8l-11.37-71.48c-4.102-25.9-16.29-49.8-34.8-68.32l-51.33-51.33c-18.52-18.52-42.3-30.7-68.2-34.8L256.9 17.53C231.2 13.42 204.7 17.64 181.5 29.48L116.7 62.53C93.23 74.36 74.36 93.35 62.41 116.7L29.51 181.2c-11.84 23.44-16.08 50.04-11.98 75.94l11.37 71.48c4.101 25.9 16.29 49.77 34.8 68.41l51.33 51.33c18.52 18.4 42.3 30.61 68.2 34.72l71.84 11.37c25.78 4.102 52.27-.1173 75.47-11.95l64.8-33.05c23.32-11.84 42.3-30.82 54.26-54.14l32.81-64.57C494.4 307.3 498.6 280.8 494.5 254.8zM176 367.1c-17.62 0-31.1-14.37-31.1-31.1c0-17.62 14.37-31.1 31.1-31.1s31.1 14.37 31.1 31.1C208 353.6 193.6 367.1 176 367.1zM208 208c-17.62 0-31.1-14.37-31.1-31.1s14.38-31.1 31.1-31.1c17.62 0 31.1 14.37 31.1 31.1S225.6 208 208 208zM368 335.1c-17.62 0-31.1-14.37-31.1-31.1c0-17.62 14.37-31.1 31.1-31.1s31.1 14.37 31.1 31.1C400 321.6 385.6 335.1 368 335.1z"></path>
        </svg>
    </div>

    @include('partials.translations')
</body>

</html>
