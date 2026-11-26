<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\DataProvider;
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

    public function test_empty_login_shows_errors_at_each_field_without_repopulating_password(): void
    {
        $response = $this->from('/login')->post('/login', ['email' => '', 'password' => ''])
            ->assertRedirect('/login')
            ->assertSessionHasErrors([
                'email' => 'Email wajib diisi.',
                'password' => 'Password wajib diisi.',
            ]);

        $cookie = $response->getCookie(config('session.cookie'));
        $this->withCookie($cookie->getName(), $cookie->getValue())->get('/login')->assertOk()
            ->assertSee('id="email-error"', false)
            ->assertSee('id="password-error"', false)
            ->assertSee('aria-describedby="email-error"', false)
            ->assertSee('aria-describedby="password-error"', false);
    }

    public function test_failed_login_retains_email_but_never_password(): void
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'missing@example.test',
            'password' => 'NeverDisplayThisPassword',
        ])->assertRedirect('/login')->assertSessionHasErrors('email');

        $cookie = $response->getCookie(config('session.cookie'));
        $this->withCookie($cookie->getName(), $cookie->getValue())->get('/login')->assertOk()
            ->assertSee('value="missing@example.test"', false)
            ->assertSee('Email atau kata sandi yang Anda masukkan salah.')
            ->assertDontSee('NeverDisplayThisPassword');
    }

    public function test_invalid_email_has_an_indonesian_validation_message(): void
    {
        $this->from('/login')->post('/login', [
            'email' => 'not-an-email',
            'password' => 'password',
        ])->assertSessionHasErrors(['email' => 'Masukkan alamat email yang valid.']);
    }

    /**
     * @return array<string, array{string, string, list<string>, list<string>, int}>
     */
    public static function roleMenus(): array
    {
        return [
            'admin' => [User::ROLE_ADMIN, 'Admin', ['Dashboard', 'Mahasiswa', 'Dosen', 'Mata Kuliah', 'Jadwal', 'Informasi Akademik'], ['/dashboard', '/students', '/lecturers', '/schedules'], 2],
            'dosen' => [User::ROLE_LECTURER, 'Dosen', ['Dashboard', 'Jadwal Mengajar', 'Input Nilai', 'Informasi Akademik'], ['/dashboard', '/schedules', '/grades'], 1],
            'mahasiswa' => [User::ROLE_STUDENT, 'Mahasiswa', ['Dashboard', 'Jadwal Kuliah', 'Nilai', 'Informasi Akademik'], ['/dashboard', '/schedules', '/grades'], 1],
        ];
    }

    /**
     * @param  list<string>  $labels
     * @param  list<string>  $paths
     */
    #[DataProvider('roleMenus')]
    public function test_each_role_can_login_and_gets_only_its_required_navigation(string $role, string $label, array $labels, array $paths, int $unavailable): void
    {
        $user = User::factory()->create(['role' => $role]);
        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect(route('dashboard'));

        $response = $this->get('/dashboard')->assertOk()
            ->assertSee($user->name)
            ->assertSee('Anda login sebagai:')
            ->assertSee('<strong>'.$label.'</strong>', false);

        $document = new \DOMDocument;
        @$document->loadHTML('<?xml encoding="UTF-8">'.$response->getContent());
        $xpath = new \DOMXPath($document);
        $navigation = $xpath->query('//nav[@id="role-navigation"]')->item(0);
        $this->assertNotNull($navigation);
        $actualLabels = [];
        foreach ($xpath->query('.//li', $navigation) as $item) {
            $actualLabels[] = trim(preg_replace('/\s+/', ' ', str_replace('Belum tersedia', '', $item->textContent)));
        }
        $this->assertSame($labels, $actualLabels);
        $this->assertSame($unavailable, $xpath->query('.//*[@aria-disabled="true"]', $navigation)->length);
        $this->assertSame(0, $xpath->query('.//*[@aria-disabled="true"]//a', $navigation)->length);
        $this->assertSame(1, $xpath->query('.//a[@aria-current="page"]', $navigation)->length);

        $logoutForms = $xpath->query('//form[@action="'.route('logout').'"]');
        $this->assertSame(1, $logoutForms->length);
        $this->assertSame(1, $xpath->query('//header//form[@action="'.route('logout').'"]')->length);
        $this->assertSame('POST', $logoutForms->item(0)->getAttribute('method'));
        $this->assertSame(1, $xpath->query('.//input[@name="_token"]', $logoutForms->item(0))->length);

        $actualPaths = [];
        foreach ($xpath->query('.//a', $navigation) as $link) {
            $actualPaths[] = parse_url($link->getAttribute('href'), PHP_URL_PATH);
        }
        $this->assertSame($paths, $actualPaths);
        foreach ($paths as $path) {
            $this->get($path)->assertOk();
        }

        $this->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_demo_accounts_are_not_rendered_outside_local_environment(): void
    {
        $this->get('/login')->assertOk()->assertDontSee('data-demo-email', false);
        $this->app['env'] = 'local';
        $this->get('/login')->assertOk()->assertSee('data-demo-email', false);
    }
}
