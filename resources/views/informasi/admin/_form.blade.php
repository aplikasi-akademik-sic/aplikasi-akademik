@include('layouts._data-field', ['field' => 'judul', 'label' => 'Judul', 'value' => $informasi?->judul])
<div class="form-field">
    <label for="isi">Isi pengumuman</label>
    <textarea id="isi" name="isi" required rows="8" data-required-message="Isi pengumuman wajib diisi."
        class="w-full resize-y rounded-md border border-slate-500 px-3 py-2"
        @error('isi') aria-invalid="true" aria-describedby="isi-error" @enderror>{{ old('isi', $informasi?->isi) }}</textarea>
    @error('isi')
        <p id="isi-error" class="field-error">{{ $message }}</p>
    @enderror
</div>
<div class="flex flex-wrap gap-3">
    <button class="academic-button primary-button" data-busy-label="Menyimpan..." type="submit">Simpan</button>
    <a class="academic-button" href="{{ route('admin.informasi.index') }}">Batal</a>
</div>
