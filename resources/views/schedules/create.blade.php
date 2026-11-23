@extends('layouts.app')

@section('title', 'Tambah Jadwal Kuliah')
@section('page_title', 'Tambah Jadwal Kuliah')
@section('page_subtitle', 'Atur jadwal mata kuliah, dosen, hari, waktu dan ruangan')

@section('content')
<div class="max-w-2xl bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 md:p-8">
    <form action="{{ route('schedules.store') }}" method="POST" class="space-y-5">
        @csrf

        <div>
            <label for="course_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                Mata Kuliah <span class="text-rose-500">*</span>
            </label>
            <select id="course_id" name="course_id" required
                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                <option value="">-- Pilih Mata Kuliah --</option>
                @foreach($courses as $c)
                    <option value="{{ $c->id }}" {{ old('course_id') == $c->id ? 'selected' : '' }}>
                        {{ $c->code }} - {{ $c->name }} ({{ $c->credits }} SKS)
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="lecturer_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                Dosen Pengampu <span class="text-rose-500">*</span>
            </label>
            <select id="lecturer_id" name="lecturer_id" required
                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                <option value="">-- Pilih Dosen Pengampu --</option>
                @foreach($lecturers as $l)
                    <option value="{{ $l->id }}" {{ old('lecturer_id') == $l->id ? 'selected' : '' }}>
                        {{ $l->name }} (NIDN: {{ $l->nidn }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label for="day" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                    Hari <span class="text-rose-500">*</span>
                </label>
                <select id="day" name="day" required
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $d)
                        <option value="{{ $d }}" {{ old('day') == $d ? 'selected' : '' }}>{{ $d }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="start_time" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                    Jam Mulai <span class="text-rose-500">*</span>
                </label>
                <input type="time" id="start_time" name="start_time" value="{{ old('start_time', '08:00') }}" required
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
            </div>

            <div>
                <label for="end_time" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                    Jam Selesai <span class="text-rose-500">*</span>
                </label>
                <input type="time" id="end_time" name="end_time" value="{{ old('end_time', '10:30') }}" required
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
            </div>
        </div>

        <div>
            <label for="room" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                Ruang Perkuliahan <span class="text-rose-500">*</span>
            </label>
            <input type="text" id="room" name="room" value="{{ old('room') }}" required
                placeholder="contoh: Lab Jaringan Komputer / R.304"
                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
        </div>

        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
            <a href="{{ route('schedules.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition">
                Batal
            </a>
            <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-md shadow-indigo-600/20 transition">
                Simpan Jadwal
            </button>
        </div>
    </form>
</div>
@endsection
