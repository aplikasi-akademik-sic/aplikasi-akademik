@extends('layouts.app')

@section('title', 'Ubah Mahasiswa')
@section('page_title', 'Ubah Mahasiswa')

@section('content')
    <form method="POST" action="{{ route('students.update', $student) }}" class="student-form" data-submit-feedback>
        @csrf
        @method('PUT')
        @include('students._form', ['student' => $student])
    </form>
@endsection
