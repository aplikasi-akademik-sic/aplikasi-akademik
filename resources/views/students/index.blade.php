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
        @if(filled($search))
            <p class="px-5 py-3 text-sm muted" role="status">{{ $students->total() }} mahasiswa ditemukan untuk “{{ $search }}”.</p>
        @endif
        @if($students->isEmpty())
            <p class="p-6 muted">{{ filled($search) ? 'Coba NIM, nama, atau email lain.' : 'Belum ada data mahasiswa.' }}</p>
        @else
            <ul class="md:hidden" aria-label="Mahasiswa">
                @foreach($students as $student)
                    <li class="border-t border-slate-200 px-5 py-3">
                        @include('layouts._record-link', ['url' => route('students.show', $student), 'label' => $student->name])
                        <p class="text-sm"><span class="muted">NIM</span> {{ $student->student_number }} · Semester {{ $student->semester }}</p>
                        <p class="text-sm muted wrap-anywhere">{{ $student->email }}</p>
                        @include('students._actions')
                    </li>
                @endforeach
            </ul>
            <div class="hidden overflow-x-auto md:block" tabindex="0" role="region" aria-label="Tabel data mahasiswa">
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
                                <td>@include('layouts._record-link', ['url' => route('students.show', $student), 'label' => $student->name])</td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->semester }}</td>
                                <td>
                                    @include('students._actions')
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
