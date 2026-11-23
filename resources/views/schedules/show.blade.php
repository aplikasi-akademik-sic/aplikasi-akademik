@extends('layouts.app')

@section('title', 'Detail Jadwal - ' . ($schedule->course->name ?? ''))
@section('page_title', 'Detail Jadwal Perkuliahan')
@section('page_subtitle', 'Informasi kelas perkuliahan dan daftar mahasiswa serta nilai')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
            <div>
                <h3 class="text-xl font-bold text-slate-800">{{ $schedule->course->name ?? '-' }}</h3>
                <p class="text-xs text-slate-500 font-mono mt-0.5">{{ $schedule->course->code ?? '' }} &bull; {{ $schedule->course->credits ?? 0 }} SKS</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('schedules.index') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                    &larr; Kembali
                </a>
                @if(Auth::user()->isAdmin())
                <a href="{{ route('schedules.edit', $schedule) }}" class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl shadow transition">
                    Edit Jadwal
                </a>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 pt-6">
            <div>
                <span class="text-xs uppercase font-semibold text-slate-400 block mb-1">Dosen Pengampu</span>
                <span class="text-sm font-semibold text-slate-800">{{ $schedule->lecturer->name ?? '-' }}</span>
            </div>
            <div>
                <span class="text-xs uppercase font-semibold text-slate-400 block mb-1">Hari Kuliah</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-slate-100 text-slate-800">
                    {{ $schedule->day }}
                </span>
            </div>
            <div>
                <span class="text-xs uppercase font-semibold text-slate-400 block mb-1">Waktu Kuliah</span>
                <span class="text-sm font-mono text-slate-700">{{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }} WIB</span>
            </div>
            <div>
                <span class="text-xs uppercase font-semibold text-slate-400 block mb-1">Ruangan</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-indigo-50 text-indigo-700">
                    {{ $schedule->room }}
                </span>
            </div>
        </div>
    </div>

    <!-- Mahasiswa & Nilai di Kelas Ini -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h4 class="font-bold text-slate-800">Mahasiswa & Nilai di Kelas Ini</h4>
            @if(Auth::user()->isAdmin() || (Auth::user()->isLecturer() && Auth::user()->lecturerProfile?->id === $schedule->lecturer_id))
            <a href="{{ route('grades.create') }}" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold shadow">
                + Input Nilai Mahasiswa
            </a>
            @endif
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 text-xs uppercase font-semibold border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3">NIM</th>
                        <th class="px-6 py-3">Nama Mahasiswa</th>
                        <th class="px-6 py-3 text-center">Nilai Angka</th>
                        <th class="px-6 py-3 text-center">Nilai Huruf</th>
                        <th class="px-6 py-3">Keterangan</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($schedule->grades as $grade)
                    <tr>
                        <td class="px-6 py-3.5 font-mono font-semibold text-slate-800">{{ $grade->student->student_number ?? '-' }}</td>
                        <td class="px-6 py-3.5 font-semibold text-slate-800">{{ $grade->student->name ?? '-' }}</td>
                        <td class="px-6 py-3.5 text-center font-mono font-semibold">{{ number_format($grade->score, 2) }}</td>
                        <td class="px-6 py-3.5 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold {{ in_array($grade->grade_letter, ['A', 'A-']) ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $grade->grade_letter }}
                            </span>
                        </td>
                        <td class="px-6 py-3.5 text-xs text-slate-500">{{ $grade->remarks }}</td>
                        <td class="px-6 py-3.5 text-right whitespace-nowrap">
                            @if(Auth::user()->isAdmin() || (Auth::user()->isLecturer() && Auth::user()->lecturerProfile?->id === $schedule->lecturer_id))
                            <a href="{{ route('grades.edit', $grade) }}" class="inline-flex items-center px-2 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded text-xs font-semibold">
                                Edit Nilai
                            </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-6 text-center text-slate-400">Belum ada mahasiswa yang dinilai di jadwal ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
