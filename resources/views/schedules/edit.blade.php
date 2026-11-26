@extends('layouts.app')

@section('title', 'Ubah Jadwal')
@section('page_title', 'Ubah Jadwal')

@section('content')
    <form class="student-form" method="POST" action="{{ route('schedules.update', $schedule) }}" data-schedule-form data-submit-feedback>
        @csrf
        @method('PUT')
        @include('schedules._form')
    </form>
@endsection
