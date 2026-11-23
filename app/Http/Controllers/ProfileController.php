<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show the form for editing profile.
     */
    public function edit(): View
    {
        /** @var User $user */
        $user = Auth::user();

        return view('profile.edit', compact('user'));
    }

    /**
     * Update user profile (name, email, and optional password).
     */
    public function update(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'current_password' => ['nullable', 'required_with:password', 'string'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        // If password change is requested, verify current password
        if (! empty($validated['password'])) {
            if (! Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors([
                    'current_password' => 'Kata sandi saat ini yang Anda masukkan salah.',
                ])->withInput();
            }

            $user->password = Hash::make($validated['password']);
        }

        $oldEmail = $user->email;
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->save();

        // If user is a student, sync student record name & email
        if ($user->isStudent()) {
            $student = $user->studentProfile;
            if ($student) {
                $student->update([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                ]);
            }
        }

        // If user is a lecturer, sync lecturer record name & email
        if ($user->isLecturer()) {
            $lecturer = $user->lecturerProfile;
            if ($lecturer) {
                $lecturer->update([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                ]);
            }
        }

        return redirect()->route('profile.edit')
            ->with('success', 'Profil dan informasi akun Anda berhasil diperbarui.');
    }
}
