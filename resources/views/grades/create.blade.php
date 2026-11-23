@extends('layouts.app')

@section('title', 'Input Nilai Mahasiswa')
@section('page_title', 'Input Nilai Mahasiswa')
@section('page_subtitle', 'Masukkan nilai akademik mahasiswa untuk kelas perkuliahan')

@section('content')
<div class="max-w-2xl bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 md:p-8">
    <form action="{{ route('grades.store') }}" method="POST" class="space-y-5">
        @csrf

        <div>
            <label for="student_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                Pilih Mahasiswa <span class="text-rose-500">*</span>
            </label>
            <select id="student_id" name="student_id" required
                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                <option value="">-- Pilih Mahasiswa --</option>
                @foreach($students as $st)
                    <option value="{{ $st->id }}" {{ old('student_id') == $st->id ? 'selected' : '' }}>
                        {{ $st->student_number }} - {{ $st->name }} (Semester {{ $st->semester }})
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="schedule_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                Pilih Jadwal Perkuliahan / Kelas <span class="text-rose-500">*</span>
            </label>
            <select id="schedule_id" name="schedule_id" required
                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                <option value="">-- Pilih Jadwal / Kelas --</option>
                @foreach($schedules as $sc)
                    <option value="{{ $sc->id }}" {{ old('schedule_id') == $sc->id ? 'selected' : '' }}>
                        {{ $sc->course->name ?? '' }} ({{ $sc->day }}, {{ $sc->room }}) - Dosen: {{ $sc->lecturer->name ?? '' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="score" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                    Nilai Angka (0.00 - 100.00) <span class="text-rose-500">*</span>
                </label>
                <input type="number" step="0.01" min="0" max="100" id="score" name="score" value="{{ old('score') }}" required
                    placeholder="contoh: 85.50"
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                <p class="text-[11px] text-slate-400 mt-1">Nilai huruf (A, B+, B, C, D, E) akan dikalkulasi otomatis oleh sistem.</p>
            </div>

            <div>
                <label for="remarks" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                    Keterangan Tambahan (Opsional)
                </label>
                <input type="text" id="remarks" name="remarks" value="{{ old('remarks') }}"
                    placeholder="contoh: Sangat Baik / Lulus"
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
            <a href="{{ route('grades.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition">
                Batal
            </a>
            <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-md shadow-indigo-600/20 transition">
                Simpan Nilai
            </button>
        </div>
    </form>
</div>
@endsection
