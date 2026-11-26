@extends('layouts.app')

@section('title', 'Tambah Dosen')
@section('page_title', 'Tambah Dosen')

@section('content')
    <form class="student-form" method="POST" action="{{ route('lecturers.store') }}" data-submit-feedback>
        @csrf
        @include('lecturers._form', ['lecturer' => null])
    </form>
@endsection
