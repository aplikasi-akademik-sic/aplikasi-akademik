@extends('layouts.app')

@section('title', 'Input Nilai')
@section('page_title', 'Input Nilai')
@section('content')
    <form class="student-form" action="{{ route('dosen.nilai.update', $mataKuliah->id) }}" method="POST" data-submit-feedback>
        @csrf
        <h2 class="font-semibold">{{ $mataKuliah->kode }} · {{ $mataKuliah->nama }} · Semester {{ $mataKuliah->semester }}</h2>
        @forelse($mahasiswa as $student)
            <div class="form-field">
                <label for="score-{{ $student->id }}">{{ $student->nim }} · {{ $student->user->name ?? $student->nama }}</label>
                <input type="number" id="score-{{ $student->id }}" name="nilai[{{ $student->id }}]"
                    required min="0" max="100" step="0.01" value="{{ old('nilai.'.$student->id, $nilai[$student->id] ?? '') }}"
                    @error('nilai.'.$student->id) aria-invalid="true" aria-describedby="error-{{ $student->id }}" @enderror>
                @error('nilai.'.$student->id)<p id="error-{{ $student->id }}" class="field-error">{{ $message }}</p>@enderror
            </div>
        @empty
            <p>Belum ada mahasiswa pada semester ini.</p>
        @endforelse
        <div class="flex flex-wrap gap-3">
            @if(count($mahasiswa))
                <button class="academic-button primary-button" type="submit" data-busy-label="Menyimpan...">Simpan nilai</button>
            @endif
            <a class="academic-button" href="{{ route('dosen.nilai.index') }}">Kembali</a>
        </div>
    </form>
@endsection
