@extends('layouts.app')

@section('title', 'Ubah Dosen')
@section('page_title', 'Ubah Dosen')

@section('content')
    <form class="student-form" method="POST" action="{{ route('lecturers.update', $lecturer->id) }}" data-submit-feedback>
        @csrf
        @method('PUT')
        @include('lecturers._form')
    </form>
@endsection
