@extends('layouts.app')

@section('title', 'Data Dosen')
@section('page_title', 'Data Dosen')

@section('content')
    <section class="student-list" aria-label="Daftar dosen">
        <div class="student-toolbar">
            <form action="{{ route('lecturers.index') }}" method="GET" role="search">
                <label for="lecturer-search" class="font-semibold text-sm">Cari dosen</label>
                <div class="student-search">
                    <input type="search" name="search" id="lecturer-search" value="{{ $search }}" placeholder="NIDN, nama, atau email">
                    <button class="academic-button" type="submit">Cari</button>
                    @if(filled($search))
                        <a href="{{ route('lecturers.index') }}" class="student-action">Reset</a>
                    @endif
                </div>
            </form>
            @if(auth()->user()->isAdmin())
                <a class="academic-button primary-button" href="{{ route('lecturers.create') }}">Tambah Dosen</a>
            @endif
        </div>
        @if($lecturers->isEmpty())
            <p class="p-6 muted">{{ filled($search) ? 'Dosen tidak ditemukan. Coba NIDN, nama, atau email lain.' : 'Belum ada data dosen.' }}</p>
        @else
            <div class="overflow-x-auto" tabindex="0" role="region" aria-label="Tabel data dosen">
                <table class="student-table directory-table">
                    <thead><tr>
                        <th scope="col">NIDN</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Email</th>
                        @if(auth()->user()->isAdmin()) <th scope="col">Aksi</th> @endif
                    </tr></thead>
                    <tbody>
                        @foreach($lecturers as $lecturer)
                            <tr>
                                <th scope="row" class="font-mono">{{ $lecturer->nidn }}</th>
                                <td><a class="student-action" href="{{ route('lecturers.show', $lecturer) }}">{{ $lecturer->name }}</a></td>
                                <td>{{ $lecturer->email }}</td>
                                @if(auth()->user()->isAdmin())
                                    <td><div class="student-actions">
                                        <a class="student-action" href="{{ route('lecturers.edit', $lecturer) }}" aria-label="Ubah {{ $lecturer->name }}">Ubah</a>
                                        <a class="student-action text-red-800!" href="{{ route('lecturers.show', $lecturer) }}#hapus-dosen" aria-label="Hapus {{ $lecturer->name }}">Hapus</a>
                                    </div></td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        @if($lecturers->hasPages())
            <div class="border-t border-slate-200 p-4">{{ $lecturers->links() }}</div>
        @endif
    </section>
@endsection
