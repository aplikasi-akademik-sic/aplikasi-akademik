<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'role', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';

    public const ROLE_LECTURER = 'lecturer';

    public const ROLE_STUDENT = 'student';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isLecturer(): bool
    {
        return $this->role === self::ROLE_LECTURER;
    }

    public function isStudent(): bool
    {
        return $this->role === self::ROLE_STUDENT;
    }

    /**
     * Many-to-many relationship with Student profiles.
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_user')->withTimestamps();
    }

    /**
     * Many-to-many relationship with Lecturer profiles.
     */
    public function lecturers(): BelongsToMany
    {
        return $this->belongsToMany(Lecturer::class, 'lecturer_user')->withTimestamps();
    }

    /**
     * Get the associated student profile (first linked or by email).
     */
    public function getStudentProfileAttribute(): ?Student
    {
        return $this->students()->first() ?? Student::where('email', $this->email)->first();
    }

    /**
     * Get the associated lecturer profile (first linked or by email).
     */
    public function getLecturerProfileAttribute(): ?Lecturer
    {
        return $this->lecturers()->first() ?? Lecturer::where('email', $this->email)->first();
    }
}
