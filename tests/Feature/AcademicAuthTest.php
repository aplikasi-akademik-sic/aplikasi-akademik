<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AcademicAuthTest extends TestCase
{
    use DatabaseTransactions;

    public function test_login_page_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Sistem Informasi Akademik');
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::where('email', 'admin@akademik.com')->first();
        if (! $user) {
            $user = User::create([
                'name' => 'Admin Test',
                'email' => 'admin@akademik.com',
                'password' => bcrypt('password'),
                'role' => User::ROLE_ADMIN,
            ]);
        }

        $response = $this->post('/login', [
            'email' => 'admin@akademik.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@akademik.com',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_user_can_logout(): void
    {
        $user = User::where('email', 'admin@akademik.com')->first();
        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_guest_cannot_access_dashboard(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_student_cannot_access_student_create_form(): void
    {
        $studentUser = User::where('role', User::ROLE_STUDENT)->first();

        $response = $this->actingAs($studentUser)->get('/students-create');

        $response->assertStatus(403);
    }
}
