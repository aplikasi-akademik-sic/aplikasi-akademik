<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Display a listing of admin users.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $admins = User::where('role', User::ROLE_ADMIN)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admins.index', compact('admins', 'search'));
    }

    /**
     * Show the form for creating a new admin.
     */
    public function create(): View
    {
        return view('admins.create');
    }

    /**
     * Store a newly created admin in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => User::ROLE_ADMIN,
        ]);

        return redirect()->route('admins.index')
            ->with('success', 'Admin baru berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified admin.
     */
    public function edit(User $admin): View
    {
        abort_unless($admin->isAdmin(), 404);

        return view('admins.edit', compact('admin'));
    }

    /**
     * Update the specified admin in storage.
     */
    public function update(Request $request, User $admin): RedirectResponse
    {
        abort_unless($admin->isAdmin(), 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($admin->id)],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if (! empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $admin->update($updateData);

        return redirect()->route('admins.index')
            ->with('success', 'Data admin berhasil diperbarui.');
    }

    /**
     * Remove the specified admin from storage.
     */
    public function destroy(User $admin): RedirectResponse
    {
        abort_unless($admin->isAdmin(), 404);

        if ($admin->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun admin Anda sendiri.');
        }

        $adminCount = User::where('role', User::ROLE_ADMIN)->count();
        if ($adminCount <= 1) {
            return back()->with('error', 'Tidak dapat menghapus admin terakhir pada sistem.');
        }

        $admin->delete();

        return redirect()->route('admins.index')
            ->with('success', 'Admin berhasil dihapus.');
    }
}
