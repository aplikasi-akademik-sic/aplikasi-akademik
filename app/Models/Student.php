<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_number',
        'name',
        'email',
        'semester',
    ];

    /**
     * Many-to-many relationship with Users.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'student_user')->withTimestamps();
    }

    /**
     * Grades associated with this student.
     */
    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Schedules enrolled through grades.
     */
    public function schedules(): BelongsToMany
    {
        return $this->belongsToMany(Schedule::class, 'grades')
            ->withPivot(['id', 'score', 'grade_letter', 'remarks'])
            ->withTimestamps();
    }
}
