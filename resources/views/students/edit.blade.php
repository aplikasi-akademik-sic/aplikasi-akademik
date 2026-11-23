@extends('layouts.app')

@section('title', 'Edit Mahasiswa')
@section('page_title', 'Edit Data Mahasiswa')
@section('page_subtitle', 'Perbarui informasi data mahasiswa')

@section('content')
<div class="max-w-2xl bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 md:p-8">
    <form action="{{ route('students.update', $student) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label for="student_number" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                NIM (Nomor Induk Mahasiswa) <span class="text-rose-500">*</span>
            </label>
            <input type="text" id="student_number" name="student_number" value="{{ old('student_number', $student->student_number) }}" required
                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
        </div>

        <div>
            <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                Nama Lengkap Mahasiswa <span class="text-rose-500">*</span>
            </label>
            <input type="text" id="name" name="name" value="{{ old('name', $student->name) }}" required
                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
        </div>

        <div>
            <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                Email Mahasiswa <span class="text-rose-500">*</span>
            </label>
            <input type="email" id="email" name="email" value="{{ old('email', $student->email) }}" required
                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
        </div>

        <div>
            <label for="semester" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                Semester <span class="text-rose-500">*</span>
            </label>
            <input type="number" id="semester" name="semester" value="{{ old('semester', $student->semester) }}" min="1" max="14" required
                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
        </div>

        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
            <a href="{{ route('students.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition">
                Batal
            </a>
            <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-md shadow-indigo-600/20 transition">
                Perbarui Data
            </button>
        </div>
    </form>
</div>
@endsection
