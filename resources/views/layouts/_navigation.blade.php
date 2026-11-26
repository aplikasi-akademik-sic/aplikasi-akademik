@php
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
            @endphp
            <li>
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
