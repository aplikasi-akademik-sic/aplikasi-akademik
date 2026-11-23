@extends('layouts.app')

@section('title', 'Detail Mahasiswa - ' . $student->name)
@section('page_title', 'Detail Mahasiswa')
@section('page_subtitle', 'Informasi profil, akun terhubung, dan riwayat nilai akademik')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
            <div>
                <h3 class="text-xl font-bold text-slate-800">{{ $student->name }}</h3>
                <p class="text-xs text-slate-500 font-mono mt-0.5">NIM: {{ $student->student_number }} &bull; Semester {{ $student->semester }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('students.index') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                    &larr; Kembali
                </a>
                @if(Auth::user()->isAdmin())
                <a href="{{ route('students.edit', $student) }}" class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl shadow transition">
                    Edit Data
                </a>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-6">
            <div>
                <span class="text-xs uppercase font-semibold text-slate-400 block mb-1">Email Mahasiswa</span>
                <span class="text-sm font-medium text-slate-700">{{ $student->email }}</span>
            </div>
            <div>
                <span class="text-xs uppercase font-semibold text-slate-400 block mb-1">Status Semester</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-sky-100 text-sky-800">
                    Semester {{ $student->semester }}
                </span>
            </div>
            <div>
                <span class="text-xs uppercase font-semibold text-slate-400 block mb-1">Relasi Tabel Users (Many-to-Many)</span>
                @forelse($student->users as $user)
                    <div class="text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-1 rounded inline-block">
                        User ID #{{ $user->id }} &bull; {{ $user->email }} (Role: {{ $user->role }})
                    </div>
                @empty
                    <span class="text-xs text-slate-400 italic">Belum ada akun User terhubung</span>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Riwayat Nilai & Mata Kuliah -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h4 class="font-bold text-slate-800">Riwayat Nilai & KHS Mahasiswa</h4>
            <span class="text-xs text-slate-400">Total: {{ $student->grades->count() }} Mata Kuliah</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 text-xs uppercase font-semibold border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3">Mata Kuliah</th>
                        <th class="px-6 py-3">Dosen Pengampu</th>
                        <th class="px-6 py-3 text-center">Nilai Angka</th>
                        <th class="px-6 py-3 text-center">Nilai Huruf</th>
                        <th class="px-6 py-3">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($student->grades as $grade)
                    <tr>
                        <td class="px-6 py-3.5 font-semibold text-slate-800">
                            {{ $grade->schedule->course->name ?? '-' }}
                            <div class="text-xs text-slate-400 font-mono">{{ $grade->schedule->course->code ?? '' }} &bull; {{ $grade->schedule->course->credits ?? 0 }} SKS</div>
                        </td>
                        <td class="px-6 py-3.5 text-slate-700">{{ $grade->schedule->lecturer->name ?? '-' }}</td>
                        <td class="px-6 py-3.5 text-center font-mono font-semibold">{{ number_format($grade->score, 2) }}</td>
                        <td class="px-6 py-3.5 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold {{ in_array($grade->grade_letter, ['A', 'A-']) ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $grade->grade_letter }}
                            </span>
                        </td>
                        <td class="px-6 py-3.5 text-xs text-slate-500">{{ $grade->remarks }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-6 text-center text-slate-400">Mahasiswa ini belum memiliki data nilai perkuliahan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
