<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Grade;
use App\Models\Lecturer;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AcademicFlowTest extends TestCase
{
    use DatabaseTransactions;

    protected User $adminUser;

    protected User $lecturerUser;

    protected User $studentUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::where('role', User::ROLE_ADMIN)->first();
        $this->lecturerUser = User::where('role', User::ROLE_LECTURER)->first();
        $this->studentUser = User::where('role', User::ROLE_STUDENT)->first();
    }

    public function test_admin_can_create_student_and_auto_links_user(): void
    {
        $email = 'bambang.pamungkas@student.akademik.com';

        $response = $this->actingAs($this->adminUser)->post('/students', [
            'student_number' => '202499001',
            'name' => 'Bambang Pamungkas',
            'email' => $email,
            'semester' => 2,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('students.index'));

        $this->assertDatabaseHas('students', [
            'student_number' => '202499001',
            'name' => 'Bambang Pamungkas',
            'email' => $email,
            'semester' => 2,
        ]);

        // Verify User was automatically created
        $user = User::where('email', $email)->first();
        $this->assertNotNull($user);
        $this->assertEquals(User::ROLE_STUDENT, $user->role);

        // Verify many-to-many relationship
        $student = Student::where('email', $email)->first();
        $this->assertTrue($student->users->contains($user));
    }

    public function test_admin_can_create_lecturer_and_auto_links_user(): void
    {
        $email = 'dosen.baru@akademik.com';

        $response = $this->actingAs($this->adminUser)->post('/lecturers', [
            'nidn' => '0099887766',
            'name' => 'Dr. Dosen Baru, M.Cs.',
            'email' => $email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('lecturers.index'));

        $this->assertDatabaseHas('lecturers', [
            'nidn' => '0099887766',
            'name' => 'Dr. Dosen Baru, M.Cs.',
            'email' => $email,
        ]);

        // Verify User was automatically created
        $user = User::where('email', $email)->first();
        $this->assertNotNull($user);
        $this->assertEquals(User::ROLE_LECTURER, $user->role);

        // Verify many-to-many relationship
        $lecturer = Lecturer::where('email', $email)->first();
        $this->assertTrue($lecturer->users->contains($user));
    }

    public function test_admin_can_create_schedule(): void
    {
        $course = Course::first();
        $lecturer = Lecturer::first();

        $response = $this->actingAs($this->adminUser)->post('/schedules', [
            'course_id' => $course->id,
            'lecturer_id' => $lecturer->id,
            'day' => 'Senin',
            'start_time' => '13:00',
            'end_time' => '15:30',
            'room' => 'Lab AI 202',
        ]);

        $response->assertRedirect(route('schedules.index'));
        $this->assertDatabaseHas('schedules', [
            'course_id' => $course->id,
            'lecturer_id' => $lecturer->id,
            'day' => 'Senin',
            'room' => 'Lab AI 202',
        ]);
    }

    public function test_admin_or_lecturer_can_input_grade(): void
    {
        $student = Student::create([
            'student_number' => '202488888',
            'name' => 'Student Testing',
            'email' => 'testing@student.akademik.com',
            'semester' => 1,
        ]);

        $schedule = Schedule::first();

        $response = $this->actingAs($this->lecturerUser)->post('/grades', [
            'student_id' => $student->id,
            'schedule_id' => $schedule->id,
            'score' => 88.00,
            'remarks' => 'Sangat Baik',
        ]);

        $response->assertRedirect(route('grades.index'));

        $this->assertDatabaseHas('grades', [
            'student_id' => $student->id,
            'schedule_id' => $schedule->id,
            'score' => 88.00,
            'grade_letter' => 'A',
        ]);
    }

    public function test_grade_calculation(): void
    {
        $calcA = Grade::calculateGrade(87.5);
        $this->assertEquals('A', $calcA['letter']);

        $calcB = Grade::calculateGrade(72.0);
        $this->assertEquals('B', $calcB['letter']);

        $calcC = Grade::calculateGrade(60.0);
        $this->assertEquals('C', $calcC['letter']);

        $calcE = Grade::calculateGrade(40.0);
        $this->assertEquals('E', $calcE['letter']);
    }

    public function test_student_can_view_khs_grades(): void
    {
        $response = $this->actingAs($this->studentUser)->get('/grades');

        $response->assertStatus(200);
        $response->assertSee('Kartu Hasil Studi');
    }
}
