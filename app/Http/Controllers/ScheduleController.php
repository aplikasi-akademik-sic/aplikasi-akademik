<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lecturer;
use App\Models\Schedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    /**
     * Display a listing of schedules.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $query = Schedule::with(['course', 'lecturer']);

        if ($user->isLecturer()) {
            $lecturer = $user->lecturerProfile;
            if ($lecturer) {
                $query->where('lecturer_id', $lecturer->id);
            }
        } elseif ($user->isStudent()) {
            // Students can see all active academic schedules
        }

        $dayFilter = $request->query('day');
        if ($dayFilter) {
            $query->where('day', $dayFilter);
        }

        $schedules = $query->orderBy('day')->orderBy('start_time')->paginate(10)->withQueryString();

        return view('schedules.index', compact('schedules', 'dayFilter'));
    }

    /**
     * Show the form for creating a new schedule.
     */
    public function create(): View
    {
        $courses = Course::orderBy('name')->get();
        $lecturers = Lecturer::orderBy('name')->get();

        return view('schedules.create', compact('courses', 'lecturers'));
    }

    /**
     * Store a newly created schedule in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'lecturer_id' => ['required', 'exists:lecturers,id'],
            'day' => ['required', 'string', 'max:50'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'room' => ['required', 'string', 'max:100'],
        ]);

        Schedule::create($validated);

        return redirect()->route('schedules.index')
            ->with('success', 'Jadwal Kuliah berhasil ditambahkan.');
    }

    /**
     * Display the specified schedule.
     */
    public function show(Schedule $schedule): View
    {
        $schedule->load(['course', 'lecturer', 'grades.student']);

        return view('schedules.show', compact('schedule'));
    }

    /**
     * Show the form for editing the specified schedule.
     */
    public function edit(Schedule $schedule): View
    {
        $courses = Course::orderBy('name')->get();
        $lecturers = Lecturer::orderBy('name')->get();

        return view('schedules.edit', compact('schedule', 'courses', 'lecturers'));
    }

    /**
     * Update the specified schedule in storage.
     */
    public function update(Request $request, Schedule $schedule): RedirectResponse
    {
        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'lecturer_id' => ['required', 'exists:lecturers,id'],
            'day' => ['required', 'string', 'max:50'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'room' => ['required', 'string', 'max:100'],
        ]);

        $schedule->update($validated);

        return redirect()->route('schedules.index')
            ->with('success', 'Jadwal Kuliah berhasil diperbarui.');
    }

    /**
     * Remove the specified schedule from storage.
     */
    public function destroy(Schedule $schedule): RedirectResponse
    {
        $schedule->delete();

        return redirect()->route('schedules.index')
            ->with('success', 'Jadwal Kuliah berhasil dihapus.');
    }
}
