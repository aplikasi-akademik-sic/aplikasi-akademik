@if(auth()->user()->isAdmin())
    <div class="student-actions">
        <a class="student-action" href="{{ route('students.edit', $student) }}" aria-label="Ubah {{ $student->name }}">@include('layouts._action-icon', ['action' => 'edit']) Ubah</a>
        <details class="student-delete">
            <summary><span class="sr-only">{{ $student->name }} — </span><span class="delete-label">@include('layouts._action-icon', ['action' => 'delete']) Hapus</span><span class="cancel-label">Batal</span></summary>
            <form method="POST" action="{{ route('students.destroy', $student) }}">
                @csrf
                @method('DELETE')
                <p>Hapus {{ $student->name }} ({{ $student->student_number }}) beserta nilainya? Akun loginnya tetap ada.</p>
                <button type="submit" class="academic-button danger-button">Ya, hapus</button>
            </form>
        </details>
    </div>
@else
    <a class="student-action" href="{{ route('students.show', $student) }}">Detail</a>
@endif
