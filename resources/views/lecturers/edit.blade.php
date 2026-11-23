@extends('layouts.app')

@section('title', 'Edit Dosen')
@section('page_title', 'Edit Data Dosen')
@section('page_subtitle', 'Perbarui informasi data dosen')

@section('content')
<div class="max-w-2xl bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 md:p-8">
    <form action="{{ route('lecturers.update', $lecturer) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label for="nidn" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                NIDN (Nomor Induk Dosen Nasional) <span class="text-rose-500">*</span>
            </label>
            <input type="text" id="nidn" name="nidn" value="{{ old('nidn', $lecturer->nidn) }}" required
                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
        </div>

        <div>
            <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                Nama Lengkap & Gelar <span class="text-rose-500">*</span>
            </label>
            <input type="text" id="name" name="name" value="{{ old('name', $lecturer->name) }}" required
                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
        </div>

        <div>
            <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                Email Dosen <span class="text-rose-500">*</span>
            </label>
            <input type="email" id="email" name="email" value="{{ old('email', $lecturer->email) }}" required
                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
        </div>

        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
            <a href="{{ route('lecturers.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition">
                Batal
            </a>
            <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-md shadow-indigo-600/20 transition">
                Perbarui Data
            </button>
        </div>
    </form>
</div>
@endsection
