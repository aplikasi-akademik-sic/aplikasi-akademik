@extends('layouts.app')

@section('title', 'Tambah Jadwal')
@section('page_title', 'Tambah Jadwal')

@section('content')
    <form class="student-form" method="POST" action="{{ route('schedules.store') }}" data-schedule-form data-submit-feedback>
        @csrf
        @include('schedules._form', ['schedule' => null])
    </form>
@endsection
