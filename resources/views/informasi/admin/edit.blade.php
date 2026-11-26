@extends('layouts.app')

@section('title', 'Ubah Pengumuman')
@section('page_title', 'Ubah Pengumuman')

@section('content')
    <form method="POST" class="student-form" action="{{ route('admin.informasi.update', $informasi->id) }}" data-submit-feedback>
        @csrf
        @method('PUT')
        @include('informasi.admin._form')
    </form>
@endsection
