@php
    $fields = [
        ['student_number', 'NIM', 'text', 50],
        ['name', 'Nama lengkap', 'text', 255],
        ['email', 'Email', 'email', 255],
        ['semester', 'Semester', 'number', null],
    ];
@endphp
@foreach ($fields as [$key, $label, $type, $limit])
    <div class="form-field">
        <label for="{{ $key }}">{{ $label }}</label>
        <input id="{{ $key }}" name="{{ $key }}" type="{{ $type }}"
            value="{{ old($key, $student?->{$key}) }}" required
            data-required-message="{{ $label }} wajib diisi."
            @if($limit) maxlength="{{ $limit }}" @endif
            @if($key === 'email') data-type-message="Masukkan alamat email yang valid." @endif
            @if($key === 'semester') min="1" max="14" step="1" @endif
            @error($key) aria-invalid="true" aria-describedby="{{ $key }}-error" @enderror>
        @error($key)
            <p class="field-error" id="{{ $key }}-error">{{ $message }}</p>
        @enderror
    </div>
@endforeach
@if(!$student || ($canUpdatePassword ?? false))
    <div class="form-field">
        <label for="password">{{ $student ? 'Password baru (opsional)' : 'Password awal' }}</label>
        <input type="password" name="password" id="password" autocomplete="new-password" minlength="6"
            @required(!$student) data-required-message="Password awal wajib diisi."
            aria-describedby="password-hint{{ $errors->has('password') ? ' password-error' : '' }}"
            @error('password') aria-invalid="true" @enderror>
        <p id="password-hint" class="muted text-sm">{{ $student ? 'Kosongkan untuk mempertahankan password lama.' : 'Minimal 6 karakter. Mahasiswa menggunakan email dan password ini untuk login.' }}</p>
        @error('password')
            <p class="field-error" id="password-error">{{ $message }}</p>
        @enderror
    </div>
@else
    <p class="muted text-sm">Perubahan password belum tersedia pada form ini.</p>
@endif
<div class="flex flex-wrap items-center gap-3">
    <button class="academic-button primary-button" type="submit" data-busy-label="Menyimpan...">Simpan</button>
    <a class="academic-button" href="{{ route('students.index') }}">Batal</a>
</div>
