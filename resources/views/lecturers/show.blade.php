@extends('layouts.app')

@section('title', 'Detail Dosen - ' . $lecturer->name)
@section('page_title', 'Detail Dosen')
@section('page_subtitle', 'Informasi profil, akun terhubung, dan jadwal mengajar')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
            <div>
                <h3 class="text-xl font-bold text-slate-800">{{ $lecturer->name }}</h3>
                <p class="text-xs text-slate-500 font-mono mt-0.5">NIDN: {{ $lecturer->nidn }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('lecturers.index') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                    &larr; Kembali
                </a>
                @if(Auth::user()->isAdmin())
                <a href="{{ route('lecturers.edit', $lecturer) }}" class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl shadow transition">
                    Edit Data
                </a>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6">
            <div>
                <span class="text-xs uppercase font-semibold text-slate-400 block mb-1">Email Institusi</span>
                <span class="text-sm font-medium text-slate-700">{{ $lecturer->email }}</span>
            </div>
            <div>
                <span class="text-xs uppercase font-semibold text-slate-400 block mb-1">Relasi Tabel Users (Many-to-Many)</span>
                @forelse($lecturer->users as $user)
                    <div class="text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-lg inline-block">
                        User ID #{{ $user->id }} &bull; {{ $user->email }} (Role: {{ $user->role }})
                    </div>
                @empty
                    <span class="text-xs text-slate-400 italic">Belum ada akun User terhubung</span>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Jadwal Mengajar -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h4 class="font-bold text-slate-800">Daftar Jadwal Mengajar Dosen</h4>
            <span class="text-xs text-slate-400">Total: {{ $lecturer->schedules->count() }} Kelas</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 text-xs uppercase font-semibold border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3">Hari & Waktu</th>
                        <th class="px-6 py-3">Mata Kuliah</th>
                        <th class="px-6 py-3">Ruang</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($lecturer->schedules as $sched)
                    <tr>
                        <td class="px-6 py-3.5">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-slate-100 text-slate-800 mr-2">{{ $sched->day }}</span>
                            <span class="text-xs text-slate-500 font-mono">{{ substr($sched->start_time, 0, 5) }} - {{ substr($sched->end_time, 0, 5) }}</span>
                        </td>
                        <td class="px-6 py-3.5 font-semibold text-slate-800">
                            {{ $sched->course->name ?? '-' }}
                            <div class="text-xs text-slate-400 font-mono">{{ $sched->course->code ?? '' }} &bull; {{ $sched->course->credits ?? 0 }} SKS</div>
                        </td>
                        <td class="px-6 py-3.5">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">{{ $sched->room }}</span>
                        </td>
                        <td class="px-6 py-3.5 text-right">
                            <a href="{{ route('schedules.show', $sched) }}" class="inline-flex items-center px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-medium">
                                Detail Kelas & Nilai
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-6 text-center text-slate-400">Dosen ini belum memiliki jadwal perkuliahan aktif.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
