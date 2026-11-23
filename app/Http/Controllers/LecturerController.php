<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LecturerController extends Controller
{
    /**
     * Display a listing of lecturers.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $lecturers = Lecturer::with('users')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('nidn', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('lecturers.index', compact('lecturers', 'search'));
    }

    /**
     * Show the form for creating a new lecturer.
     */
    public function create(): View
    {
        return view('lecturers.create');
    }

    /**
     * Store a newly created lecturer in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nidn' => ['required', 'string', 'max:50', 'unique:lecturers,nidn'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $lecturer = Lecturer::create([
            'nidn' => $validated['nidn'],
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Find or create matching User account by email
        $user = User::firstOrCreate(
            ['email' => $validated['email']],
            [
                'name' => $validated['name'],
                'password' => Hash::make($validated['password'] ?? 'password'),
                'role' => User::ROLE_LECTURER,
            ]
        );

        // Many-to-many relationship sync
        $lecturer->users()->syncWithoutDetaching([$user->id]);

        return redirect()->route('lecturers.index')
            ->with('success', 'Data Dosen berhasil ditambahkan dan ditautkan ke akun User.');
    }

    /**
     * Display the specified lecturer.
     */
    public function show(Lecturer $lecturer): View
    {
        $lecturer->load(['users', 'schedules.course']);

        return view('lecturers.show', compact('lecturer'));
    }

    /**
     * Show the form for editing the specified lecturer.
     */
    public function edit(Lecturer $lecturer): View
    {
        return view('lecturers.edit', compact('lecturer'));
    }

    /**
     * Update the specified lecturer in storage.
     */
    public function update(Request $request, Lecturer $lecturer): RedirectResponse
    {
        $validated = $request->validate([
            'nidn' => ['required', 'string', 'max:50', Rule::unique('lecturers', 'nidn')->ignore($lecturer->id)],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        $lecturer->update([
            'nidn' => $validated['nidn'],
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Keep linked user in sync
        $user = User::firstOrCreate(
            ['email' => $validated['email']],
            [
                'name' => $validated['name'],
                'password' => Hash::make('password'),
                'role' => User::ROLE_LECTURER,
            ]
        );

        $lecturer->users()->syncWithoutDetaching([$user->id]);

        return redirect()->route('lecturers.index')
            ->with('success', 'Data Dosen berhasil diperbarui.');
    }

    /**
     * Remove the specified lecturer from storage.
     */
    public function destroy(Lecturer $lecturer): RedirectResponse
    {
        $lecturer->users()->detach();
        $lecturer->delete();

        return redirect()->route('lecturers.index')
            ->with('success', 'Data Dosen berhasil dihapus.');
    }
}
