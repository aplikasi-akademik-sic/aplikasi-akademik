@extends('layouts.app')

@section('title', 'Kelola Admin')
@section('page_title', 'Kelola Administrator')
@section('page_subtitle', 'Manajemen akun pengguna dengan hak akses Administrator')

@section('content')
<div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
    <!-- Header with Search & Add Button -->
    <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admins.index') }}" class="flex items-center gap-2 max-w-md w-full">
            <div class="relative w-full">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau email admin..."
                    class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <button type="submit" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition">
                Cari
            </button>
            @if($search)
                <a href="{{ route('admins.index') }}" class="text-xs text-rose-500 hover:underline whitespace-nowrap">Reset</a>
            @endif
        </form>

        <a href="{{ route('admins.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-md shadow-indigo-600/20 transition whitespace-nowrap">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            + Tambah Admin
        </a>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-slate-700 text-xs uppercase font-semibold border-b border-slate-100">
                <tr>
                    <th class="px-6 py-3.5">ID</th>
                    <th class="px-6 py-3.5">Nama Admin</th>
                    <th class="px-6 py-3.5">Email</th>
                    <th class="px-6 py-3.5">Tanggal Dibuat</th>
                    <th class="px-6 py-3.5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($admins as $admin)
                <tr class="hover:bg-slate-50/70 transition">
                    <td class="px-6 py-4 font-mono font-semibold text-slate-500">#{{ $admin->id }}</td>
                    <td class="px-6 py-4">
                        <div class="font-semibold text-slate-800 flex items-center gap-2">
                            {{ $admin->name }}
                            @if($admin->id === Auth::id())
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-indigo-100 text-indigo-700 border border-indigo-200">Akun Anda</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 text-slate-600">{{ $admin->email }}</td>
                    <td class="px-6 py-4 text-xs text-slate-500">{{ $admin->created_at?->format('d M Y, H:i') ?? '-' }}</td>
                    <td class="px-6 py-4 text-right space-x-1 whitespace-nowrap">
                        <a href="{{ route('admins.edit', $admin) }}" class="inline-flex items-center px-2.5 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg text-xs font-semibold transition">
                            @include('layouts._action-icon', ['action' => 'edit']) Ubah
                        </a>

                        @if($admin->id !== Auth::id())
                        <form action="{{ route('admins.destroy', $admin) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun admin ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-semibold transition">
                                @include('layouts._action-icon', ['action' => 'delete']) Hapus
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                        Tidak ada data admin ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($admins->hasPages())
    <div class="p-4 border-t border-slate-100">
        {{ $admins->links() }}
    </div>
    @endif
</div>
@endsection
