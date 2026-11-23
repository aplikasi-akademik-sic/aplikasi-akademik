<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lecturer extends Model
{
    use HasFactory;

    protected $fillable = [
        'nidn',
        'name',
        'email',
    ];

    /**
     * Many-to-many relationship with Users.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'lecturer_user')->withTimestamps();
    }

    /**
     * Schedules taught by this lecturer.
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }
}
