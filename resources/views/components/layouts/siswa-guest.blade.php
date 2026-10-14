<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ ($title ?? null) ? $title . ' - ' . config('app.name') : config('app.name') }}</title>

    <script>
        try {
            if (localStorage.getItem('siswa-theme') === 'dark') {
                document.documentElement.classList.add('dark');
            }
        } catch (e) { }
    </script>

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body
    class="min-h-screen font-sans antialiased bg-surface text-on-surface dark:bg-surface-dark dark:text-on-surface-dark">
    {{ $slot }}

    @livewireScripts
</body>

</html>