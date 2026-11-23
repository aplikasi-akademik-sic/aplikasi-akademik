<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Grade;
use App\Models\Lecturer;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Admin User
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@akademik.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ADMIN,
        ]);

        // 2. Seed Lecturers & Lecturer Users (Many-to-Many relation)
        $lecturersData = [
            [
                'name' => 'Dr. Ir. Budi Santoso, M.Kom.',
                'nidn' => '0012057801',
                'email' => 'budi.santoso@akademik.com',
            ],
            [
                'name' => 'Siti Rahmawati, S.T., M.T.',
                'nidn' => '0023088202',
                'email' => 'siti.rahmawati@akademik.com',
            ],
            [
                'name' => 'Prof. Dr. Hendra Wijaya',
                'nidn' => '0015097503',
                'email' => 'hendra.wijaya@akademik.com',
            ],
        ];

        $lecturerModels = [];
        foreach ($lecturersData as $data) {
            $lecturer = Lecturer::create($data);

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role' => User::ROLE_LECTURER,
            ]);

            // Sync many-to-many relationship
            $lecturer->users()->attach($user->id);
            $lecturerModels[] = $lecturer;
        }

        // 3. Seed Students & Student Users (Many-to-Many relation)
        $studentsData = [
            [
                'student_number' => '202401001',
                'name' => 'Ahmad Umar Fauzi',
                'email' => 'ahmad.fauzi@student.akademik.com',
                'semester' => 3,
            ],
            [
                'student_number' => '202401002',
                'name' => 'Dinda Lestari',
                'email' => 'dinda.lestari@student.akademik.com',
                'semester' => 3,
            ],
            [
                'student_number' => '202301015',
                'name' => 'Rian Setiawan',
                'email' => 'rian.setiawan@student.akademik.com',
                'semester' => 5,
            ],
        ];

        $studentModels = [];
        foreach ($studentsData as $data) {
            $student = Student::create($data);

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role' => User::ROLE_STUDENT,
            ]);

            // Sync many-to-many relationship
            $student->users()->attach($user->id);
            $studentModels[] = $student;
        }

        // 4. Seed Courses
        $coursesData = [
            ['code' => 'IF101', 'name' => 'Algoritma & Pemrograman', 'credits' => 3],
            ['code' => 'IF201', 'name' => 'Basis Data & SQL', 'credits' => 3],
            ['code' => 'IF202', 'name' => 'Rekayasa Perangkat Lunak', 'credits' => 3],
            ['code' => 'IF301', 'name' => 'Pemrograman Web Lanjut', 'credits' => 4],
            ['code' => 'IF302', 'name' => 'Kecerdasan Buatan', 'credits' => 3],
        ];

        $courseModels = [];
        foreach ($coursesData as $course) {
            $courseModels[] = Course::create($course);
        }

        // 5. Seed Schedules
        $schedulesData = [
            [
                'course_id' => $courseModels[0]->id, // Algoritma & Pemrograman
                'lecturer_id' => $lecturerModels[0]->id, // Budi Santoso
                'day' => 'Senin',
                'start_time' => '08:00:00',
                'end_time' => '10:30:00',
                'room' => 'Lab Komputer 1',
            ],
            [
                'course_id' => $courseModels[1]->id, // Basis Data & SQL
                'lecturer_id' => $lecturerModels[1]->id, // Siti Rahmawati
                'day' => 'Selasa',
                'start_time' => '10:30:00',
                'end_time' => '13:00:00',
                'room' => 'Lab Basis Data',
            ],
            [
                'course_id' => $courseModels[3]->id, // Pemrograman Web Lanjut
                'lecturer_id' => $lecturerModels[1]->id, // Siti Rahmawati
                'day' => 'Rabu',
                'start_time' => '08:00:00',
                'end_time' => '11:30:00',
                'room' => 'Lab Multimedia',
            ],
            [
                'course_id' => $courseModels[2]->id, // Rekayasa Perangkat Lunak
                'lecturer_id' => $lecturerModels[0]->id, // Budi Santoso
                'day' => 'Kamis',
                'start_time' => '13:00:00',
                'end_time' => '15:30:00',
                'room' => 'Ruang Teori 302',
            ],
            [
                'course_id' => $courseModels[4]->id, // Kecerdasan Buatan
                'lecturer_id' => $lecturerModels[2]->id, // Hendra Wijaya
                'day' => 'Jumat',
                'start_time' => '08:30:00',
                'end_time' => '11:00:00',
                'room' => 'Ruang Seminar 401',
            ],
        ];

        $scheduleModels = [];
        foreach ($schedulesData as $schedule) {
            $scheduleModels[] = Schedule::create($schedule);
        }

        // 6. Seed Grades
        $gradesData = [
            // Ahmad Fauzi
            ['student_id' => $studentModels[0]->id, 'schedule_id' => $scheduleModels[0]->id, 'score' => 88.50],
            ['student_id' => $studentModels[0]->id, 'schedule_id' => $scheduleModels[1]->id, 'score' => 78.00],
            ['student_id' => $studentModels[0]->id, 'schedule_id' => $scheduleModels[2]->id, 'score' => 91.00],

            // Dewi Lestari
            ['student_id' => $studentModels[1]->id, 'schedule_id' => $scheduleModels[0]->id, 'score' => 74.00],
            ['student_id' => $studentModels[1]->id, 'schedule_id' => $scheduleModels[1]->id, 'score' => 85.00],

            // Rian Pratama
            ['student_id' => $studentModels[2]->id, 'schedule_id' => $scheduleModels[3]->id, 'score' => 82.00],
            ['student_id' => $studentModels[2]->id, 'schedule_id' => $scheduleModels[4]->id, 'score' => 68.50],
        ];

        foreach ($gradesData as $g) {
            $calc = Grade::calculateGrade($g['score']);
            Grade::create([
                'student_id' => $g['student_id'],
                'schedule_id' => $g['schedule_id'],
                'score' => $g['score'],
                'grade_letter' => $calc['letter'],
                'remarks' => $calc['remarks'],
            ]);
        }
    }
}
