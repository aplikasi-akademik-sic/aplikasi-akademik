@extends('layouts.app')

@section('title', 'Data Mahasiswa')
@section('page_title', 'Data Mahasiswa')

@section('content')
    <section class="student-list" aria-label="Daftar mahasiswa">
        <div class="student-toolbar">
            <form action="{{ route('students.index') }}" method="GET" role="search">
                <label for="student-search" class="font-semibold text-sm">Cari mahasiswa</label>
                <div class="student-search">
                    <input type="search" name="search" id="student-search" placeholder="NIM, nama, atau email" value="{{ $search }}">
                    <button class="academic-button" type="submit">Cari</button>
                    @if(filled($search))
                        <a href="{{ route('students.index') }}" class="student-action">Reset</a>
                    @endif
                </div>
            </form>
            @if(auth()->user()->isAdmin())
                <a class="academic-button primary-button" href="{{ route('students.create') }}">Tambah Mahasiswa</a>
            @endif
        </div>
        @if($students->isEmpty())
            <p class="p-6 muted">{{ filled($search) ? 'Tidak ada mahasiswa yang cocok. Coba NIM, nama, atau email lain.' : 'Belum ada data mahasiswa.' }}</p>
        @else
            <div class="overflow-x-auto" tabindex="0" role="region" aria-label="Tabel data mahasiswa">
                <table class="student-table">
                    <thead><tr>
                        @foreach(['NIM', 'Nama', 'Email', 'Semester', 'Aksi'] as $heading)
                            <th scope="col">{{ $heading }}</th>
                        @endforeach
                    </tr></thead>
                    <tbody>
                        @foreach($students as $student)
                            <tr>
                                <th scope="row" class="font-mono">{{ $student->student_number }}</th>
                                <td><a class="student-action" href="{{ route('students.show', $student) }}">{{ $student->name }}</a></td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->semester }}</td>
                                <td>
                                    @if(auth()->user()->isAdmin())
                                        <div class="student-actions">
                                            <a class="student-action" href="{{ route('students.edit', $student) }}" aria-label="Ubah {{ $student->name }}">Ubah</a>
                                            <details class="student-delete">
                                                <summary aria-label="Konfirmasi hapus {{ $student->name }}"><span class="delete-label">Hapus</span><span class="cancel-label">Batal</span></summary>
                                                <form method="POST" action="{{ route('students.destroy', $student) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <p>Hapus {{ $student->name }} ({{ $student->student_number }}) beserta nilainya? Akun loginnya tetap ada.</p>
                                                    <button type="submit" class="academic-button danger-button">Ya, hapus</button>
                                                </form>
                                            </details>
                                        </div>
                                    @else
                                        <a class="student-action" href="{{ route('students.show', $student) }}">Detail</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        @if($students->hasPages())
            <div class="border-t border-slate-200 p-4">{{ $students->links() }}</div>
        @endif
    </section>
@endsection
