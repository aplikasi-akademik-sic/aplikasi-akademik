@extends('layouts.app')

@section('title', 'Jadwal Perkuliahan')
@section('page_title', 'Jadwal Kuliah')
@section('page_subtitle', 'Daftar jadwal perkuliahan, ruang, dosen pengampu, dan mata kuliah')

@section('content')
<div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
    <!-- Header with Filter & Add Button -->
    <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form method="GET" action="{{ route('schedules.index') }}" class="flex items-center gap-2">
            <select name="day" onchange="this.form.submit()"
                class="px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">-- Semua Hari --</option>
                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $d)
                    <option value="{{ $d }}" {{ $dayFilter == $d ? 'selected' : '' }}>{{ $d }}</option>
                @endforeach
            </select>
            @if($dayFilter)
                <a href="{{ route('schedules.index') }}" class="text-xs text-rose-500 hover:underline">Reset Filter</a>
            @endif
        </form>

        @if(Auth::user()->isAdmin())
        <a href="{{ route('schedules.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-md shadow-indigo-600/20 transition">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Jadwal
        </a>
        @endif
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-slate-700 text-xs uppercase font-semibold border-b border-slate-100">
                <tr>
                    <th class="px-6 py-3.5">Hari & Waktu</th>
                    <th class="px-6 py-3.5">Mata Kuliah</th>
                    <th class="px-6 py-3.5">Dosen Pengampu</th>
                    <th class="px-6 py-3.5">Ruang</th>
                    <th class="px-6 py-3.5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($schedules as $sched)
                <tr class="hover:bg-slate-50/70 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-slate-100 text-slate-800 mr-2">{{ $sched->day }}</span>
                        <span class="text-xs text-slate-500 font-mono">{{ substr($sched->start_time, 0, 5) }} - {{ substr($sched->end_time, 0, 5) }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-semibold text-slate-800">{{ $sched->course->name ?? '-' }}</div>
                        <div class="text-xs text-slate-400 font-mono">{{ $sched->course->code ?? '' }} &bull; {{ $sched->course->credits ?? 0 }} SKS</div>
                    </td>
                    <td class="px-6 py-4 text-slate-700">{{ $sched->lecturer->name ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">{{ $sched->room }}</span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-1 whitespace-nowrap">
                        <a href="{{ route('schedules.show', $sched) }}" class="inline-flex items-center px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold transition">
                            Detail
                        </a>
                        @if(Auth::user()->isAdmin())
                        <a href="{{ route('schedules.edit', $sched) }}" class="inline-flex items-center px-2.5 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg text-xs font-semibold transition">
                            Edit
                        </a>
                        <form action="{{ route('schedules.destroy', $sched) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-semibold transition">
                                Hapus
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                        Tidak ada data jadwal ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($schedules->hasPages())
    <div class="p-4 border-t border-slate-100">
        {{ $schedules->links() }}
    </div>
    @endif
</div>
@endsection
