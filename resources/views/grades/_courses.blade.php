<section class="student-list mb-6" aria-label="Mata kuliah yang diampu">
    <h2 class="p-4 font-semibold">Mata kuliah yang diampu</h2>
    <ul>
        @forelse($mataKuliah ?? $schedules->pluck('course')->filter()->unique('id') as $course)
            <li class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-200 p-4">
                <span>{{ $course->kode ?? $course->code }} · {{ $course->nama ?? $course->name }}</span>
                <a class="academic-button" href="{{ Route::has('dosen.nilai.edit') ? route('dosen.nilai.edit', $course->id) : route('grades.create', ['course_id' => $course->id]) }}">Input Nilai<span class="sr-only"> {{ $course->nama ?? $course->name }}</span></a>
            </li>
        @empty
            <li class="p-4 muted">Belum ada mata kuliah yang diampu.</li>
        @endforelse
    </ul>
</section>
