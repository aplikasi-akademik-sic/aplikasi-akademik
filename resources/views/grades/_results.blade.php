@php
    $results = $hasilStudi ?? $grades;
@endphp
<section class="student-list" aria-label="Hasil studi">
    <div class="overflow-x-auto" role="region" aria-label="Tabel nilai mahasiswa" tabindex="0">
        <table class="student-table directory-table">
            <thead><tr>
                <th scope="col">Kode</th><th scope="col">Nama mata kuliah</th>
                <th scope="col">SKS</th><th scope="col">Nilai</th>
            </tr></thead>
            <tbody>
                @forelse($results as $row)
                    @php
                        $course = isset($hasilStudi) ? $row : $row->schedule->course;
                        $score = isset($hasilStudi) ? data_get($row, 'nilai') : $row->score;
                    @endphp
                    <tr>
                        <td>{{ data_get($course, 'kode') ?? data_get($course, 'code') }}</td>
                        <th scope="row">{{ data_get($course, 'nama') ?? data_get($course, 'name') }}</th>
                        <td>{{ data_get($course, 'sks') ?? data_get($course, 'credits') }}</td>
                        <td>{{ $score ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4">Belum ada nilai.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($results instanceof \Illuminate\Contracts\Pagination\Paginator && $results->hasPages())
        <div class="p-4">{{ $results->links() }}</div>
    @endif
</section>
