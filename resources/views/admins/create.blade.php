@extends('layouts.app')

@section('title', 'Tambah Admin')
@section('page_title', 'Tambah Administrator Baru')
@section('page_subtitle', 'Buat akun administrator baru untuk mengelola sistem akademik')

@section('content')
<div class="max-w-2xl bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 md:p-8">
    <form action="{{ route('admins.store') }}" method="POST" class="space-y-5">
        @csrf

        <div>
            <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                Nama Lengkap Administrator <span class="text-rose-500">*</span>
            </label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                placeholder="contoh: Administrator Dua"
                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
        </div>

        <div>
            <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                Alamat Email <span class="text-rose-500">*</span>
            </label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                placeholder="contoh: admin2@akademik.com"
                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                    Kata Sandi <span class="text-rose-500">*</span>
                </label>
                <input type="password" id="password" name="password" required minlength="6"
                    placeholder="Minimal 6 karakter"
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                    Konfirmasi Kata Sandi <span class="text-rose-500">*</span>
                </label>
                <input type="password" id="password_confirmation" name="password_confirmation" required minlength="6"
                    placeholder="Ulangi kata sandi"
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
            <a href="{{ route('admins.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition">
                Batal
            </a>
            <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-md shadow-indigo-600/20 transition">
                Simpan Admin
            </button>
        </div>
    </form>
</div>
@endsection
