@extends('layouts.app')

@section('title', 'Tambah Pengumuman')
@section('page_title', 'Tambah Pengumuman')

@section('content')
    <form method="POST" class="student-form" action="{{ route('admin.informasi.store') }}" data-submit-feedback>
        @csrf
        @include('informasi.admin._form', ['informasi' => null])
    </form>
@endsection
