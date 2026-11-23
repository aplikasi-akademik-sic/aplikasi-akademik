@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page_title', 'Pengaturan Profil Akun')
@section('page_subtitle', 'Kelola nama, email, dan kata sandi akun Anda')

@section('content')
<div class="max-w-2xl bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 md:p-8">
    <div class="flex items-center space-x-4 pb-6 border-b border-slate-100 mb-6">
        <div class="w-14 h-14 rounded-2xl bg-indigo-600 flex items-center justify-center text-white font-bold text-xl uppercase shadow-lg shadow-indigo-600/30 shrink-0">
            {{ substr($user->name, 0, 2) }}
        </div>
        <div>
            <h3 class="text-lg font-bold text-slate-800">{{ $user->name }}</h3>
            <p class="text-xs text-slate-500 font-mono">{{ $user->email }}</p>
            <div class="mt-1">
                @if($user->isAdmin())
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-indigo-100 text-indigo-700">Administrator</span>
                @elseif($user->isLecturer())
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-emerald-100 text-emerald-700">Dosen Pengajar</span>
                @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-sky-100 text-sky-700">Mahasiswa</span>
                @endif
            </div>
        </div>
    </div>

    <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Bagian Biodata Pokok -->
        <div class="space-y-4">
            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500">Informasi Dasar</h4>

            <div>
                <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                    Nama Lengkap <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
            </div>

            <div>
                <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                    Alamat Email <span class="text-rose-500">*</span>
                </label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                <p class="text-[11px] text-slate-400 mt-1">Jika Anda mahasiswa atau dosen, perubahan email otomatis tersinkronisasi dengan biodata akademik Anda.</p>
            </div>
        </div>

        <!-- Bagian Ganti Password -->
        <div class="pt-6 border-t border-slate-100 space-y-4">
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500">Ganti Kata Sandi</h4>
                <p class="text-xs text-slate-400 mt-0.5">Kosongkan jika Anda tidak ingin mengubah kata sandi.</p>
            </div>

            <div>
                <label for="current_password" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                    Kata Sandi Saat Ini
                </label>
                <input type="password" id="current_password" name="current_password"
                    placeholder="Masukkan kata sandi lama Anda"
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                        Kata Sandi Baru
                    </label>
                    <input type="password" id="password" name="password" minlength="6"
                        placeholder="Minimal 6 karakter"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                        Ulangi Kata Sandi Baru
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation" minlength="6"
                        placeholder="Konfirmasi kata sandi baru"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition">
                Kembali ke Dashboard
            </a>
            <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-md shadow-indigo-600/20 transition">
                Simpan Perubahan Profil
            </button>
        </div>
    </form>
</div>
@endsection
