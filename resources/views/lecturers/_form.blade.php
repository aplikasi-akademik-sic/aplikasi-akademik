@include('layouts._data-field', ['field' => 'nidn', 'label' => 'NIDN', 'value' => $lecturer?->nidn, 'maxlength' => 50])
@include('layouts._data-field', ['field' => 'name', 'label' => 'Nama lengkap dan gelar', 'value' => $lecturer?->name, 'maxlength' => 255])
@include('layouts._data-field', ['field' => 'email', 'type' => 'email', 'label' => 'Email', 'value' => $lecturer?->email, 'maxlength' => 255])

<div class="form-field">
    <label for="password">{{ $lecturer ? 'Password baru (opsional)' : 'Password awal' }}</label>
    <input id="password" name="password" type="password" autocomplete="new-password" minlength="6"
        @required(!$lecturer) @disabled($lecturer && !($canUpdatePassword ?? false))
        data-required-message="Password awal wajib diisi."
        aria-describedby="password-hint{{ $errors->has('password') ? ' password-error' : '' }}"
        @error('password') aria-invalid="true" @enderror>
    <p id="password-hint" class="muted text-sm">
        @if(!$lecturer)
            Minimal 6 karakter. Dosen memakai email dan password ini untuk login.
        @elseif($canUpdatePassword ?? false)
            Kosongkan untuk mempertahankan password lama.
        @else
            Perubahan password dosen belum tersedia di sini.
        @endif
    </p>
    @error('password')
        <p class="field-error" id="password-error">{{ $message }}</p>
    @enderror
</div>
<div class="flex flex-wrap gap-3">
    <button class="academic-button primary-button" type="submit" data-busy-label="Menyimpan...">Simpan</button>
    <a class="academic-button" href="{{ route('lecturers.index') }}">Batal</a>
</div>
