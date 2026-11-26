@extends('layouts.app')

@section('title', 'Tambah Mahasiswa')
@section('page_title', 'Tambah Mahasiswa')

@section('content')
    <form method="POST" action="{{ route('students.store') }}" class="student-form" data-submit-feedback>
        @csrf
        @include('students._form', ['student' => null])
    </form>
@endsection
