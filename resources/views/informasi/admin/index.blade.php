@extends('layouts.app')

@section('title', 'Kelola Informasi Akademik')
@section('page_title', 'Kelola Informasi Akademik')

@section('content')
    <section class="student-list" aria-label="Pengumuman akademik">
        <div class="student-toolbar justify-end!">
            <a class="academic-button primary-button" href="{{ route('admin.informasi.create') }}">Tambah Pengumuman</a>
        </div>
        @if($informasi->isEmpty())
            <p class="p-5 muted">Belum ada informasi</p>
        @else
            <div class="overflow-x-auto" tabindex="0" role="region" aria-label="Tabel pengumuman">
                <table class="student-table directory-table">
                    <thead><tr><th scope="col">Judul</th><th scope="col">Tanggal terbit</th><th scope="col">Aksi</th></tr></thead>
                    <tbody>
                        @foreach($informasi as $item)
                            <tr>
                                <th scope="row" class="wrap-anywhere">{{ $item->judul }}</th>
                                <td>@include('informasi._tanggal')</td>
                                <td>
                                    <div class="student-actions">
                                        <a class="student-action" href="{{ route('admin.informasi.edit', $item->id) }}">Ubah<span class="sr-only"> {{ $item->judul }}</span></a>
                                        <details class="student-delete">
                                            <summary><span class="delete-label">Hapus</span><span class="cancel-label">Batal</span><span class="sr-only"> {{ $item->judul }}</span></summary>
                                            <form method="POST" action="{{ route('admin.informasi.destroy', $item->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <p>Hapus pengumuman “{{ $item->judul }}”?</p>
                                                <button class="academic-button danger-button" type="submit">Ya, hapus</button>
                                            </form>
                                        </details>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        @if($informasi instanceof \Illuminate\Contracts\Pagination\Paginator && $informasi->hasPages())
            <div class="border-t border-slate-200 p-4">{{ $informasi->links() }}</div>
        @endif
    </section>
@endsection
