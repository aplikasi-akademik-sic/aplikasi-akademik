@extends('layouts.app')

@section('title', 'Ubah Mata Kuliah')
@section('page_title', 'Ubah Mata Kuliah')

@section('content')
    <form class="student-form" method="POST" action="{{ route('admin.mata-kuliah.update', $mataKuliah->id) }}" data-submit-feedback>
        @csrf
        @method('PUT')
        @include('mata-kuliah._form')
    </form>
@endsection
