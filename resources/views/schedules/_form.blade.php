@foreach([
    'course_id' => ['Mata kuliah', $courses->mapWithKeys(fn ($course) => [$course->id => $course->code . ' · ' . $course->name])],
    'lecturer_id' => ['Dosen pengampu', $lecturers->mapWithKeys(fn ($lecturer) => [$lecturer->id => $lecturer->name . ' (' . $lecturer->nidn . ')'])],
    'day' => ['Hari', collect(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'])->mapWithKeys(fn ($day) => [$day => $day])],
] as $field => [$label, $options])
    <div class="form-field">
        <label for="{{ $field }}">{{ $label }}</label>
        <select id="{{ $field }}" name="{{ $field }}" required
            @error($field) aria-invalid="true" aria-describedby="{{ $field }}-error" @enderror>
            <option value="">Pilih {{ strtolower($label) }}</option>
            @foreach($options as $value => $text)
                <option value="{{ $value }}" @selected(old($field, $schedule?->{$field}) == $value)>{{ $text }}</option>
            @endforeach
        </select>
        @if($options->isEmpty())
            <p class="muted text-sm">Data {{ strtolower($label) }} belum tersedia.</p>
        @endif
        @error($field)<p class="field-error" id="{{ $field }}-error">{{ $message }}</p>@enderror
    </div>
@endforeach

<div class="grid gap-4 sm:grid-cols-2">
    @foreach(['start_time' => 'Jam mulai', 'end_time' => 'Jam selesai'] as $field => $label)
        <div class="form-field">
            <label for="{{ $field }}">{{ $label }}</label>
            <input id="{{ $field }}" type="time" name="{{ $field }}" required
                value="{{ old($field, substr((string) $schedule?->{$field}, 0, 5)) }}"
                @error($field) aria-invalid="true" aria-describedby="{{ $field }}-error" @enderror>
            @error($field)<p class="field-error" id="{{ $field }}-error">{{ $message }}</p>@enderror
        </div>
    @endforeach
</div>
@include('layouts._data-field', ['field' => 'room', 'label' => 'Ruang', 'value' => $schedule?->room, 'maxlength' => 100])
<div class="flex flex-wrap gap-3">
    <button class="academic-button primary-button" type="submit" data-busy-label="Menyimpan...">Simpan</button>
    <a class="academic-button" href="{{ route('schedules.index') }}">Batal</a>
</div>
