@props(['title' => 'HadirKu'])
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} - HadirKu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>[x-cloak]{display:none!important}</style>
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased">
    <div
        x-data="{ toast: '', showToast: false }"
        x-on:toast.window="toast = $event.detail.message; showToast = true; setTimeout(() => showToast = false, 5000)"
        class="min-h-screen"
    >
        <x-sidebar />
        <main class="min-h-screen pb-24 lg:ml-72 lg:pb-8">
            <header class="sticky top-0 z-20 border-b border-slate-200 bg-white/90 px-4 py-4 backdrop-blur lg:px-8">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-blue-700">HadirKu</p>
                        <h1 class="text-xl font-semibold text-slate-950">{{ $title }}</h1>
                    </div>
                    @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="rounded-md border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Keluar</button>
                        </form>
                    @endauth
                </div>
            </header>
            <div class="mx-auto max-w-7xl px-4 py-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>
        <x-bottom-nav />
        <div x-cloak x-show="showToast" x-transition class="fixed right-4 top-4 z-50 max-w-sm rounded-lg bg-slate-950 px-4 py-3 text-sm font-medium text-white shadow-lg">
            <span x-text="toast"></span>
        </div>
    </div>
    @livewireScripts
</body>
</html>
