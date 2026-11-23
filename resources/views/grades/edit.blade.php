@extends('layouts.app')

@section('title', 'Edit Nilai Mahasiswa')
@section('page_title', 'Edit Nilai Mahasiswa')
@section('page_subtitle', 'Perbarui nilai akademik mahasiswa')

@section('content')
<div class="max-w-2xl bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 md:p-8">
    <div class="mb-6 p-4 rounded-xl bg-slate-50 border border-slate-200/60">
        <div class="text-sm">
            <p><strong class="text-slate-700">Mahasiswa:</strong> {{ $grade->student->name ?? '-' }} (NIM: {{ $grade->student->student_number ?? '-' }})</p>
            <p><strong class="text-slate-700">Mata Kuliah:</strong> {{ $grade->schedule->course->name ?? '-' }} ({{ $grade->schedule->course->code ?? '' }})</p>
            <p><strong class="text-slate-700">Dosen Pengampu:</strong> {{ $grade->schedule->lecturer->name ?? '-' }}</p>
        </div>
    </div>

    <form action="{{ route('grades.update', $grade) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="score" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                    Nilai Angka (0.00 - 100.00) <span class="text-rose-500">*</span>
                </label>
                <input type="number" step="0.01" min="0" max="100" id="score" name="score" value="{{ old('score', $grade->score) }}" required
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
            </div>

            <div>
                <label for="remarks" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                    Keterangan Tambahan
                </label>
                <input type="text" id="remarks" name="remarks" value="{{ old('remarks', $grade->remarks) }}"
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
            <a href="{{ route('grades.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition">
                Batal
            </a>
            <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-md shadow-indigo-600/20 transition">
                Perbarui Nilai
            </button>
        </div>
    </form>
</div>
@endsection
