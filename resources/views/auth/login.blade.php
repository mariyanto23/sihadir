<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - HadirKu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-blue-50">
    <main class="flex min-h-screen items-center justify-center px-4 py-10">
        <div class="w-full max-w-md rounded-lg bg-white p-8 shadow-sm ring-1 ring-blue-100">
            <div class="mb-8">
                <p class="text-sm font-semibold text-blue-700">HadirKu</p>
                <h1 class="mt-2 text-2xl font-bold text-slate-950">Masuk ke Sistem Presensi</h1>
                <p class="mt-2 text-sm text-slate-500">Gunakan akun admin, siswa, atau orang tua.</p>
            </div>
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <x-input label="Email" name="email" type="email" value="{{ old('email') }}" required autofocus />
                <x-input label="Password" name="password" type="password" required />
                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-blue-700">
                    Ingat saya
                </label>
                <x-button type="submit" class="w-full">Masuk</x-button>
            </form>
        </div>
    </main>
</body>
</html>
