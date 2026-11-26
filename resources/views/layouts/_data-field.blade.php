<div class="form-field">
    <label for="{{ $field }}">{{ $label }}</label>
    <input id="{{ $field }}" name="{{ $field }}" type="{{ $type ?? 'text' }}"
        value="{{ old($field, $value) }}" required data-required-message="{{ $label }} wajib diisi."
        @isset($maxlength) maxlength="{{ $maxlength }}" @endisset
        @if(($type ?? 'text') === 'number') min="1" step="1" @endif
        @if(($type ?? 'text') === 'email') data-type-message="Masukkan alamat email yang valid." @endif
        @error($field) aria-invalid="true" aria-describedby="{{ $field }}-error" @enderror>
    @error($field)
        <p class="field-error" id="{{ $field }}-error">{{ $message }}</p>
    @enderror
</div>
