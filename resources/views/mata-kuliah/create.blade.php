@extends('layouts.app')

@section('title', 'Tambah Mata Kuliah')
@section('page_title', 'Tambah Mata Kuliah')

@section('content')
    <form class="student-form" method="POST" action="{{ route('admin.mata-kuliah.store') }}" data-submit-feedback>
        @csrf
        @include('mata-kuliah._form', ['mataKuliah' => null])
    </form>
@endsection
