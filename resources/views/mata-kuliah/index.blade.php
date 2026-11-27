@extends('layouts.app')

@section('title', 'Data Mata Kuliah')
@section('page_title', 'Data Mata Kuliah')

@section('content')
    <section class="student-list" aria-label="Daftar mata kuliah">
        <div class="student-toolbar justify-end!">
            <a class="academic-button primary-button" href="{{ route('admin.mata-kuliah.create') }}">Tambah Mata Kuliah</a>
        </div>
        @if($mataKuliah->isEmpty())
            <p class="p-6 muted">Belum ada data mata kuliah.</p>
        @else
            <div class="overflow-x-auto" tabindex="0" role="region" aria-label="Tabel data mata kuliah">
                <table class="student-table directory-table">
                    <thead><tr>
                        <th scope="col">Kode</th>
                        <th scope="col">Nama Mata Kuliah</th>
                        <th scope="col">SKS</th>
                        <th scope="col">Semester</th>
                        <th scope="col">Aksi</th>
                    </tr></thead>
                    <tbody>
                        @foreach($mataKuliah as $mk)
                            <tr>
                                <th scope="row" class="font-mono">{{ $mk->kode }}</th>
                                <td>{{ $mk->nama }}</td>
                                <td>{{ $mk->sks }}</td>
                                <td>{{ $mk->semester ?? 'Belum diisi' }}</td>
                                <td><div class="student-actions">
                                    <a class="student-action" href="{{ route('admin.mata-kuliah.edit', $mk->id) }}" aria-label="Ubah {{ $mk->nama }}">@include('layouts._action-icon', ['action' => 'edit']) Ubah</a>
                                    <details class="student-delete">
                                        <summary><span class="sr-only">{{ $mk->nama }} — </span><span class="delete-label">@include('layouts._action-icon', ['action' => 'delete']) Hapus</span><span class="cancel-label">Batal</span></summary>
                                        <form method="POST" action="{{ route('admin.mata-kuliah.destroy', $mk->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <p>Hapus mata kuliah {{ $mk->nama }} ({{ $mk->kode }})?</p>
                                            <button class="academic-button danger-button" type="submit">Ya, hapus</button>
                                        </form>
                                    </details>
                                </div></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        @if($mataKuliah instanceof \Illuminate\Contracts\Pagination\Paginator && $mataKuliah->hasPages())
            <div class="border-t border-slate-200 p-4">{{ $mataKuliah->links() }}</div>
        @endif
    </section>
@endsection
