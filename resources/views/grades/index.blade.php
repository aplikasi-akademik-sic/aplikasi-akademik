@extends('layouts.app')

@section('title', 'Data Nilai Akademik')
@section('page_title', Auth::user()->isStudent() ? 'Kartu Hasil Studi (KHS)' : (Auth::user()->isLecturer() ? 'Input Nilai' : 'Data Nilai Mahasiswa'))

@section('content')
@if(Auth::user()->isStudent())
    @include('grades._results')
@else
@if(Auth::user()->isLecturer())
    @include('grades._courses')
@endif
@isset($grades)
<div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
    <!-- Header with Filter & Input Nilai Button -->
    <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form method="GET" action="{{ route('grades.index') }}" class="flex items-center gap-2 max-w-md w-full">
            <select name="schedule_id" aria-label="Filter nilai berdasarkan kelas" onchange="this.form.submit()"
                class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">-- Filter Berdasarkan Kelas / Mata Kuliah --</option>
                @foreach($schedules as $s)
                    <option value="{{ $s->id }}" {{ $scheduleFilter == $s->id ? 'selected' : '' }}>
                        {{ $s->course->name ?? '' }} ({{ $s->day }}, {{ $s->room }})
                    </option>
                @endforeach
            </select>
            @if($scheduleFilter)
                <a href="{{ route('grades.index') }}" class="text-xs text-rose-500 hover:underline whitespace-nowrap">Reset</a>
            @endif
        </form>

        @if(Auth::user()->isAdmin())
        <a href="{{ route('grades.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-md shadow-indigo-600/20 transition whitespace-nowrap">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            + Input Nilai
        </a>
        @endif
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-slate-700 text-xs uppercase font-semibold border-b border-slate-100">
                <tr>
                    @if(!Auth::user()->isStudent())
                    <th class="px-6 py-3.5">Mahasiswa</th>
                    @endif
                    <th class="px-6 py-3.5">Mata Kuliah & SKS</th>
                    <th class="px-6 py-3.5">Dosen Pengampu</th>
                    <th class="px-6 py-3.5 text-center">Nilai Angka</th>
                    <th class="px-6 py-3.5 text-center">Nilai Huruf</th>
                    <th class="px-6 py-3.5">Keterangan</th>
                    @if(Auth::user()->isAdmin() || Auth::user()->isLecturer())
                    <th class="px-6 py-3.5 text-right">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($grades as $grade)
                <tr class="hover:bg-slate-50/70 transition">
                    @if(!Auth::user()->isStudent())
                    <td class="px-6 py-4">
                        <div class="font-semibold text-slate-800">{{ $grade->student->name ?? '-' }}</div>
                        <div class="text-xs text-slate-400 font-mono">NIM: {{ $grade->student->student_number ?? '-' }}</div>
                    </td>
                    @endif
                    <td class="px-6 py-4">
                        <div class="font-semibold text-slate-800">{{ $grade->schedule->course->name ?? '-' }}</div>
                        <div class="text-xs text-slate-400 font-mono">{{ $grade->schedule->course->code ?? '' }} &bull; {{ $grade->schedule->course->credits ?? 0 }} SKS</div>
                    </td>
                    <td class="px-6 py-4 text-slate-700">{{ $grade->schedule->lecturer->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-center font-mono font-bold text-slate-800">
                        {{ number_format($grade->score, 2) }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold {{ in_array($grade->grade_letter, ['A', 'A-']) ? 'bg-emerald-100 text-emerald-800' : (in_array($grade->grade_letter, ['B+', 'B', 'C+', 'C']) ? 'bg-blue-100 text-blue-800' : 'bg-rose-100 text-rose-800') }}">
                            {{ $grade->grade_letter }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-xs font-medium text-slate-600">
                        {{ $grade->remarks }}
                    </td>
                    @if(Auth::user()->isAdmin() || Auth::user()->isLecturer())
                    <td class="px-6 py-4 text-right space-x-1 whitespace-nowrap">
                        <a href="{{ route('grades.edit', $grade) }}" class="inline-flex items-center px-2.5 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg text-xs font-semibold transition">
                            Edit
                        </a>
                        <form action="{{ route('grades.destroy', $grade) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data nilai ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-semibold transition">
                                Hapus
                            </button>
                        </form>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-slate-400">
                        Belum ada data nilai akademik.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($grades->hasPages())
    <div class="p-4 border-t border-slate-100">
        {{ $grades->links() }}
    </div>
    @endif
</div>
@endisset
@endif
@endsection
