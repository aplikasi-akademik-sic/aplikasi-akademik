<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Schedule;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class GradeController extends Controller
{
    /**
     * Display a listing of grades.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $query = Grade::with(['student', 'schedule.course', 'schedule.lecturer']);

        if ($user->isStudent()) {
            $student = $user->studentProfile;
            if ($student) {
                $query->where('student_id', $student->id);
            } else {
                $query->whereRaw('1 = 0');
            }
        } elseif ($user->isLecturer()) {
            $lecturer = $user->lecturerProfile;
            if ($lecturer) {
                $query->whereHas('schedule', function ($q) use ($lecturer) {
                    $q->where('lecturer_id', $lecturer->id);
                });
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        $scheduleFilter = $request->query('schedule_id');
        if ($scheduleFilter) {
            $query->where('schedule_id', $scheduleFilter);
        }

        $grades = $query->latest()->paginate(10)->withQueryString();

        if ($user->isLecturer()) {
            $lecturer = $user->lecturerProfile;
            $schedules = $lecturer
                ? Schedule::with(['course', 'lecturer'])->where('lecturer_id', $lecturer->id)->get()
                : collect();
        } else {
            $schedules = Schedule::with(['course', 'lecturer'])->get();
        }

        return view('grades.index', compact('grades', 'schedules', 'scheduleFilter'));
    }

    /**
     * Show the form for creating a new grade.
     */
    public function create(): View
    {
        $user = Auth::user();
        $students = Student::orderBy('name')->get();

        if ($user->isLecturer()) {
            $lecturer = $user->lecturerProfile;
            $schedules = $lecturer
                ? Schedule::with(['course', 'lecturer'])->where('lecturer_id', $lecturer->id)->get()
                : collect();
        } else {
            $schedules = Schedule::with(['course', 'lecturer'])->get();
        }

        return view('grades.create', compact('students', 'schedules'));
    }

    /**
     * Store a newly created grade in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'schedule_id' => ['required', 'exists:schedules,id'],
            'score' => ['required', 'numeric', 'min:0', 'max:100'],
            'remarks' => ['nullable', 'string', 'max:255'],
        ]);

        if ($user->isLecturer()) {
            $lecturer = $user->lecturerProfile;
            $schedule = Schedule::findOrFail($validated['schedule_id']);
            if (! $lecturer || $schedule->lecturer_id !== $lecturer->id) {
                abort(403, 'Anda hanya dapat menginput nilai untuk jadwal yang Anda ampu.');
            }
        }

        // Ensure unique grade per student per schedule
        $exists = Grade::where('student_id', $validated['student_id'])
            ->where('schedule_id', $validated['schedule_id'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['schedule_id' => 'Mahasiswa ini sudah memiliki nilai untuk jadwal tersebut. Silakan edit nilai yang ada.'])->withInput();
        }

        $calc = Grade::calculateGrade((float) $validated['score']);

        Grade::create([
            'student_id' => $validated['student_id'],
            'schedule_id' => $validated['schedule_id'],
            'score' => $validated['score'],
            'grade_letter' => $calc['letter'],
            'remarks' => $validated['remarks'] ?: $calc['remarks'],
        ]);

        return redirect()->route('grades.index')
            ->with('success', 'Nilai mahasiswa berhasil diinput.');
    }

    /**
     * Show the form for editing the specified grade.
     */
    public function edit(Grade $grade): View
    {
        $this->authorizeLecturerGrade($grade);

        $grade->load(['student', 'schedule.course', 'schedule.lecturer']);

        return view('grades.edit', compact('grade'));
    }

    /**
     * Update the specified grade in storage.
     */
    public function update(Request $request, Grade $grade): RedirectResponse
    {
        $this->authorizeLecturerGrade($grade);

        $validated = $request->validate([
            'score' => ['required', 'numeric', 'min:0', 'max:100'],
            'remarks' => ['nullable', 'string', 'max:255'],
        ]);

        $calc = Grade::calculateGrade((float) $validated['score']);

        $grade->update([
            'score' => $validated['score'],
            'grade_letter' => $calc['letter'],
            'remarks' => $validated['remarks'] ?: $calc['remarks'],
        ]);

        return redirect()->route('grades.index')
            ->with('success', 'Nilai mahasiswa berhasil diperbarui.');
    }

    /**
     * Remove the specified grade from storage.
     */
    public function destroy(Grade $grade): RedirectResponse
    {
        $this->authorizeLecturerGrade($grade);

        $grade->delete();

        return redirect()->route('grades.index')
            ->with('success', 'Nilai berhasil dihapus.');
    }

    /**
     * Ensure lecturers can only modify grades for their own schedules.
     */
    private function authorizeLecturerGrade(Grade $grade): void
    {
        $user = Auth::user();
        if ($user->isLecturer()) {
            $lecturer = $user->lecturerProfile;
            if (! $lecturer || $grade->schedule->lecturer_id !== $lecturer->id) {
                abort(403, 'Anda hanya dapat mengelola nilai untuk jadwal perkuliahan yang Anda ampu.');
            }
        }
    }
}
