@include('layouts._data-field', ['field' => 'kode', 'label' => 'Kode', 'value' => $mataKuliah?->kode])
@include('layouts._data-field', ['field' => 'nama', 'label' => 'Nama mata kuliah', 'value' => $mataKuliah?->nama])
@include('layouts._data-field', ['field' => 'sks', 'type' => 'number', 'label' => 'SKS', 'value' => $mataKuliah?->sks])
@include('layouts._data-field', ['field' => 'semester', 'type' => 'number', 'label' => 'Semester', 'value' => $mataKuliah?->semester])

<div class="flex flex-wrap gap-3">
    <button class="academic-button primary-button" type="submit" data-busy-label="Menyimpan...">Simpan</button>
    <a class="academic-button" href="{{ route('admin.mata-kuliah.index') }}">Batal</a>
</div>
