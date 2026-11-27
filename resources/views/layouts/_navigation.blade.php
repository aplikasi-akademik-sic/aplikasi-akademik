@php
    $icons = [
        'Dashboard' => 'M3 3h7v7H3z M14 3h7v7h-7z M3 14h7v7H3z M14 14h7v7h-7z',
        'Mahasiswa' => 'm2 9 10-5 10 5-10 5z M6 11v6q6 5 12 0v-6',
        'Dosen' => 'M4 4h16v12h-7 M8 20v-4 M4 20v-3q0-4 4-4t4 4v3 M10 9a2 2 0 1 1-4 0 2 2 0 0 1 4 0',
        'Mata Kuliah' => 'M12 6Q8 3 2 4v14q6-1 10 2 4-3 10-2V4q-6-1-10 2v14',
        'jadwal' => 'M3 5h18v16H3z M3 10h18 M7 3v5 M17 3v5',
        'nilai' => 'M4 3h16v18H4z M8 8h8 M8 12h8 M8 16h4',
        'Informasi Akademik' => 'M3 5h18v15H3z M7 9h4v4H7z M14 9h3 M14 12h3 M7 16h10',
    ];
    $menuItems = [
        ['label' => 'Dashboard', 'routes' => ['dashboard'], 'active' => ['dashboard']],
    ];
    if (auth()->user()->isAdmin()) {
        $menuItems = array_merge($menuItems, [
            ['label' => 'Mahasiswa', 'routes' => ['admin.mahasiswa.index', 'students.index'], 'active' => ['admin.mahasiswa.*', 'students.*']],
            ['label' => 'Dosen', 'routes' => ['admin.dosen.index', 'lecturers.index'], 'active' => ['admin.dosen.*', 'lecturers.*']],
            ['label' => 'Mata Kuliah', 'routes' => ['admin.mata-kuliah.index'], 'active' => ['admin.mata-kuliah.*']],
            ['label' => 'Jadwal', 'routes' => ['admin.jadwal.index', 'schedules.index'], 'active' => ['admin.jadwal.*', 'schedules.*']],
            ['label' => 'Informasi Akademik', 'routes' => ['admin.informasi.index'], 'active' => ['admin.informasi.*']],
        ]);
    } elseif (auth()->user()->isLecturer()) {
        $menuItems = array_merge($menuItems, [
            ['label' => 'Jadwal Mengajar', 'routes' => ['dosen.jadwal', 'schedules.index'], 'active' => ['dosen.jadwal', 'schedules.*']],
            ['label' => 'Input Nilai', 'routes' => ['dosen.nilai.index', 'grades.index'], 'active' => ['dosen.nilai.*', 'grades.*']],
            ['label' => 'Informasi Akademik', 'routes' => ['informasi.index'], 'active' => ['informasi.*']],
        ]);
    } elseif (auth()->user()->isStudent()) {
        $menuItems = array_merge($menuItems, [
            ['label' => 'Jadwal Kuliah', 'routes' => ['mahasiswa.jadwal', 'schedules.index'], 'active' => ['mahasiswa.jadwal', 'schedules.*']],
            ['label' => 'Nilai', 'routes' => ['mahasiswa.nilai', 'grades.index'], 'active' => ['mahasiswa.nilai', 'grades.*']],
            ['label' => 'Informasi Akademik', 'routes' => ['informasi.index'], 'active' => ['informasi.*']],
        ]);
    }
@endphp
<nav id="role-navigation" aria-label="Menu {{ $roleLabel }}">
    <p class="navigation-label">Menu {{ $roleLabel }}</p>
    <ul class="navigation-list">
        @foreach($menuItems as $item)
            @php
                $destination = collect($item['routes'])->first(fn ($name) => \Illuminate\Support\Facades\Route::has($name));
                $isActive = request()->routeIs(...$item['active']);
                $icon = $icons[$item['label']] ?? $icons[str_contains($item['label'], 'Jadwal') ? 'jadwal' : 'nilai'];
            @endphp
            <li>
                <svg class="module-icon" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $icon }}"/></svg>
                @if($destination)
                    <a href="{{ route($destination) }}" class="navigation-link" @if($isActive) aria-current="page" @endif>{{ $item['label'] }}</a>
                @else
                    {{-- TODO: Aktifkan melalui route pada kontrak BE saat modul tersedia. --}}
                    <span class="navigation-unavailable" aria-disabled="true">
                        <span>{{ $item['label'] }}</span>
                        <small>Belum tersedia</small>
                    </span>
                @endif
            </li>
        @endforeach
    </ul>
</nav>
