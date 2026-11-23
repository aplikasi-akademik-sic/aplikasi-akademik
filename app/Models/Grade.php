<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'schedule_id',
        'score',
        'grade_letter',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'decimal:2',
        ];
    }

    /**
     * Determine letter grade and default remark based on numeric score.
     *
     * @return array{letter: string, remarks: string}
     */
    public static function calculateGrade(float $score): array
    {
        if ($score >= 85) {
            return ['letter' => 'A', 'remarks' => 'Sangat Baik (Lulus)'];
        } elseif ($score >= 80) {
            return ['letter' => 'A-', 'remarks' => 'Sangat Baik (Lulus)'];
        } elseif ($score >= 75) {
            return ['letter' => 'B+', 'remarks' => 'Baik (Lulus)'];
        } elseif ($score >= 70) {
            return ['letter' => 'B', 'remarks' => 'Baik (Lulus)'];
        } elseif ($score >= 65) {
            return ['letter' => 'C+', 'remarks' => 'Cukup (Lulus)'];
        } elseif ($score >= 60) {
            return ['letter' => 'C', 'remarks' => 'Cukup (Lulus)'];
        } elseif ($score >= 50) {
            return ['letter' => 'D', 'remarks' => 'Kurang'];
        }

        return ['letter' => 'E', 'remarks' => 'Gagal'];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }
}
