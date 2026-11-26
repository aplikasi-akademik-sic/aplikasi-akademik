@extends('layouts.app')

@php
    $user = auth()->user();
    $isAdmin = $user->isAdmin();
    $isLecturer = $user->isLecturer();
    $title = $isAdmin ? 'Kelola Jadwal Kuliah' : ($isLecturer ? 'Jadwal Mengajar' : 'Jadwal Kuliah');
    $semester = $user->isStudent() ? $user->studentProfile?->semester : null;
    // Backend supplies semesterSchedules after filtering the query and before pagination.
    $rows = $user->isStudent() ? ($semesterSchedules ?? null) : $schedules;
    $hasProfile = !$isLecturer || $user->lecturerProfile !== null;
@endphp

@section('title', $title)
@section('page_title', $title)

@section('content')
<section class="student-list" aria-label="{{ $title }}">
    @if($user->isStudent() && (!$semester || !isset($rows)))
        <p class="p-6 muted">{{ $semester ? "Jadwal untuk semester $semester belum tersedia." : 'Semester Anda belum tercatat.' }}</p>
    @elseif(!$hasProfile)
        <p class="p-6 muted">Belum ada jadwal. Akun Anda belum terhubung ke profil dosen.</p>
    @else
        <div class="student-toolbar">
            <form method="GET" action="{{ route('schedules.index') }}" class="flex flex-wrap items-end gap-2">
                <div class="form-field">
                    <label for="day">Hari kuliah</label>
                    <select id="day" name="day">
                        <option value="">Semua hari</option>
                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $day)
                            <option @selected($dayFilter === $day)>{{ $day }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="academic-button" type="submit">Tampilkan</button>
            </form>
            @if($isAdmin)
                <a class="academic-button primary-button" href="{{ route('schedules.create') }}">Tambah Jadwal</a>
            @elseif($semester)
                <span>Semester {{ $semester }}</span>
            @endif
        </div>
        @if($rows->isEmpty())
            <p class="p-6 muted">{{ $dayFilter ? "Belum ada jadwal pada hari $dayFilter." : 'Belum ada jadwal' }}</p>
        @else
            <div class="overflow-x-auto" tabindex="0" role="region" aria-label="Tabel jadwal">
                <table class="student-table directory-table">
                    <thead><tr>
                        <th scope="col">Mata kuliah</th>
                        @unless($isLecturer)<th scope="col">Dosen</th>@endunless
                        <th scope="col">Hari</th><th scope="col">Jam</th><th scope="col">Ruang</th>
                        @if($isAdmin)<th scope="col">Aksi</th>@endif
                    </tr></thead>
                    <tbody>
                    @foreach($rows as $schedule)
                        <tr>
                            <th scope="row">
                                @if($user->isStudent())
                                    {{ $schedule->course?->name }}
                                @else
                                    <a class="student-action" href="{{ route('schedules.show', $schedule) }}">{{ $schedule->course?->name }}</a>
                                @endif
                            </th>
                            @unless($isLecturer)<td>{{ $schedule->lecturer?->name }}</td>@endunless
                            <td>{{ $schedule->day }}</td>
                            <td class="whitespace-nowrap">{{ substr($schedule->start_time, 0, 5) }} – {{ substr($schedule->end_time, 0, 5) }}</td>
                            <td>{{ $schedule->room }}</td>
                            @if($isAdmin)
                                <td><div class="student-actions">
                                    <a class="student-action" href="{{ route('schedules.edit', $schedule) }}">Ubah</a>
                                    <details class="student-delete">
                                        <summary><span class="delete-label">Hapus</span><span class="cancel-label">Batal</span></summary>
                                        <form method="POST" action="{{ route('schedules.destroy', $schedule) }}">
                                            @csrf
                                            @method('DELETE')
                                            <p>Hapus jadwal {{ $schedule->course?->name }} hari {{ $schedule->day }} pukul {{ substr($schedule->start_time, 0, 5) }}? Nilai yang terhubung juga akan terhapus.</p>
                                            <button type="submit" class="academic-button danger-button">Ya, hapus</button>
                                        </form>
                                    </details>
                                </div></td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        @if($rows->hasPages())
            <div class="p-4">{{ $rows->links() }}</div>
        @endif
    @endif
</section>
@endsection
