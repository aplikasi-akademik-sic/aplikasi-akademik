<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StudentController extends Controller
{
    /**
     * Display a listing of students.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $students = Student::with('users')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('student_number', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('students.index', compact('students', 'search'));
    }

    /**
     * Show the form for creating a new student.
     */
    public function create(): View
    {
        return view('students.create');
    }

    /**
     * Store a newly created student in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_number' => ['required', 'string', 'max:50', 'unique:students,student_number'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'semester' => ['required', 'integer', 'min:1', 'max:14'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $student = Student::create([
            'student_number' => $validated['student_number'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'semester' => $validated['semester'],
        ]);

        // Find or create matching User account by email
        $user = User::firstOrCreate(
            ['email' => $validated['email']],
            [
                'name' => $validated['name'],
                'password' => Hash::make($validated['password'] ?? 'password'),
                'role' => User::ROLE_STUDENT,
            ]
        );

        // Many-to-many relationship sync
        $student->users()->syncWithoutDetaching([$user->id]);

        return redirect()->route('students.index')
            ->with('success', 'Data Mahasiswa berhasil ditambahkan dan ditautkan ke akun User.');
    }

    /**
     * Display the specified student.
     */
    public function show(Student $student): View
    {
        $student->load(['users', 'grades.schedule.course', 'grades.schedule.lecturer']);

        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit(Student $student): View
    {
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified student in storage.
     */
    public function update(Request $request, Student $student): RedirectResponse
    {
        $validated = $request->validate([
            'student_number' => ['required', 'string', 'max:50', Rule::unique('students', 'student_number')->ignore($student->id)],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'semester' => ['required', 'integer', 'min:1', 'max:14'],
        ]);

        $student->update([
            'student_number' => $validated['student_number'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'semester' => $validated['semester'],
        ]);

        // Keep linked user in sync
        $user = User::firstOrCreate(
            ['email' => $validated['email']],
            [
                'name' => $validated['name'],
                'password' => Hash::make('password'),
                'role' => User::ROLE_STUDENT,
            ]
        );

        $student->users()->syncWithoutDetaching([$user->id]);

        return redirect()->route('students.index')
            ->with('success', 'Data Mahasiswa berhasil diperbarui.');
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroy(Student $student): RedirectResponse
    {
        $student->users()->detach();
        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Data Mahasiswa berhasil dihapus.');
    }
}
