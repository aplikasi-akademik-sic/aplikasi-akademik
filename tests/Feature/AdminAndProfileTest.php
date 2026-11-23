<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAndProfileTest extends TestCase
{
    use DatabaseTransactions;

    protected User $adminUser;

    protected User $studentUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::where('role', User::ROLE_ADMIN)->first();
        $this->studentUser = User::where('role', User::ROLE_STUDENT)->first();
    }

    public function test_admin_can_view_admins_list(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admins');

        $response->assertStatus(200);
        $response->assertSee('Kelola Administrator');
    }

    public function test_non_admin_cannot_access_admins_list(): void
    {
        $response = $this->actingAs($this->studentUser)->get('/admins');

        $response->assertStatus(403);
    }

    public function test_admin_can_create_new_admin(): void
    {
        $email = 'new.admin@akademik.com';

        $response = $this->actingAs($this->adminUser)->post('/admins', [
            'name' => 'Admin Baru',
            'email' => $email,
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect(route('admins.index'));

        $this->assertDatabaseHas('users', [
            'name' => 'Admin Baru',
            'email' => $email,
            'role' => User::ROLE_ADMIN,
        ]);
    }

    public function test_admin_can_update_another_admin(): void
    {
        $otherAdmin = User::create([
            'name' => 'Admin To Update',
            'email' => 'admin.update@akademik.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ADMIN,
        ]);

        $response = $this->actingAs($this->adminUser)->put("/admins/{$otherAdmin->id}", [
            'name' => 'Admin Updated Name',
            'email' => 'admin.updated@akademik.com',
        ]);

        $response->assertRedirect(route('admins.index'));

        $this->assertDatabaseHas('users', [
            'id' => $otherAdmin->id,
            'name' => 'Admin Updated Name',
            'email' => 'admin.updated@akademik.com',
        ]);
    }

    public function test_admin_cannot_delete_themselves(): void
    {
        $response = $this->actingAs($this->adminUser)->delete("/admins/{$this->adminUser->id}");

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', [
            'id' => $this->adminUser->id,
        ]);
    }

    public function test_user_can_view_profile_edit_page(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/profile');

        $response->assertStatus(200);
        $response->assertSee('Pengaturan Profil Akun');
    }

    public function test_user_can_update_their_own_name_and_email(): void
    {
        $user = User::create([
            'name' => 'Original Name',
            'email' => 'original@student.akademik.com',
            'password' => Hash::make('oldpassword'),
            'role' => User::ROLE_STUDENT,
        ]);

        $student = Student::create([
            'student_number' => '9999901',
            'name' => 'Original Name',
            'email' => 'original@student.akademik.com',
            'semester' => 1,
        ]);
        $student->users()->attach($user->id);

        $response = $this->actingAs($user)->put('/profile', [
            'name' => 'Changed Name',
            'email' => 'changed@student.akademik.com',
        ]);

        $response->assertRedirect(route('profile.edit'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Changed Name',
            'email' => 'changed@student.akademik.com',
        ]);

        // Verify student record was synchronized
        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'name' => 'Changed Name',
            'email' => 'changed@student.akademik.com',
        ]);
    }

    public function test_user_can_update_their_password_with_correct_current_password(): void
    {
        $user = User::create([
            'name' => 'Password Test User',
            'email' => 'pwdtest@akademik.com',
            'password' => Hash::make('oldpassword'),
            'role' => User::ROLE_ADMIN,
        ]);

        $response = $this->actingAs($user)->put('/profile', [
            'name' => $user->name,
            'email' => $user->email,
            'current_password' => 'oldpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }

    public function test_user_cannot_update_password_with_wrong_current_password(): void
    {
        $user = User::create([
            'name' => 'Password Test User 2',
            'email' => 'pwdtest2@akademik.com',
            'password' => Hash::make('correctpassword'),
            'role' => User::ROLE_ADMIN,
        ]);

        $response = $this->actingAs($user)->put('/profile', [
            'name' => $user->name,
            'email' => $user->email,
            'current_password' => 'wrongpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasErrors('current_password');
        $user->refresh();
        $this->assertTrue(Hash::check('correctpassword', $user->password));
    }
}
