@php
    $role = auth()->user()?->roles->first()?->name;
    $menus = match ($role) {
        \App\Support\Role::ADMIN => [
            ['Dashboard', 'admin.dashboard'],
            ['Siswa', 'admin.students.index'],
            ['Izin', 'admin.leave-requests.index'],
            ['Laporan', 'admin.reports.index'],
        ],
        \App\Support\Role::STUDENT => [
            ['Beranda', 'student.dashboard'],
            ['Scan', 'student.scan'],
            ['Izin', 'student.leave-requests.index'],
            ['Riwayat', 'student.history'],
        ],
        \App\Support\Role::PARENT => [
            ['Beranda', 'parent.dashboard'],
            ['Anak', 'parent.children.index'],
            ['Profil', 'parent.profile'],
        ],
        default => [],
    };
@endphp

@auth
<nav class="fixed inset-x-0 bottom-0 z-40 border-t border-slate-200 bg-white lg:hidden">
    <div class="grid" style="grid-template-columns: repeat({{ max(count($menus), 1) }}, minmax(0, 1fr));">
        @foreach ($menus as [$label, $route])
            <a href="{{ route($route) }}" class="px-2 py-3 text-center text-xs font-semibold {{ request()->routeIs($route) ? 'text-blue-700' : 'text-slate-500' }}">{{ $label }}</a>
        @endforeach
    </div>
</nav>
@endauth
