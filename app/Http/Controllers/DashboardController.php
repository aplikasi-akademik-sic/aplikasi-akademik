<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Grade;
use App\Models\Lecturer;
use App\Models\Schedule;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $data = [
            'user' => $user,
        ];

        if ($user->isAdmin()) {
            $data['totalStudents'] = Student::count();
            $data['totalLecturers'] = Lecturer::count();
            $data['totalCourses'] = Course::count();
            $data['totalSchedules'] = Schedule::count();
            $data['recentSchedules'] = Schedule::with(['course', 'lecturer'])
                ->latest()
                ->take(5)
                ->get();
        } elseif ($user->isLecturer()) {
            $lecturer = $user->lecturerProfile;
            $data['lecturer'] = $lecturer;

            if ($lecturer) {
                $data['mySchedules'] = Schedule::with('course')
                    ->where('lecturer_id', $lecturer->id)
                    ->get();
                $data['totalSchedules'] = $data['mySchedules']->count();
                $data['totalGradesInputted'] = Grade::whereIn('schedule_id', $data['mySchedules']->pluck('id'))->count();
            } else {
                $data['mySchedules'] = collect();
                $data['totalSchedules'] = 0;
                $data['totalGradesInputted'] = 0;
            }
        } elseif ($user->isStudent()) {
            $student = $user->studentProfile;
            $data['student'] = $student;

            if ($student) {
                $grades = Grade::with(['schedule.course', 'schedule.lecturer'])
                    ->where('student_id', $student->id)
                    ->get();
                $data['grades'] = $grades;
                $data['totalCourses'] = $grades->count();
                $data['averageScore'] = $grades->avg('score') ?? 0;
            } else {
                $data['grades'] = collect();
                $data['totalCourses'] = 0;
                $data['averageScore'] = 0;
            }
        }

        return view('dashboard', $data);
    }
}
