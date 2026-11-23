<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('schedule_id')->constrained('schedules')->cascadeOnDelete();
            $table->decimal('score', 5, 2)->default(0); // Nilai angka (0 - 100)
            $table->string('grade_letter', 5)->default('E'); // Nilai huruf (A, B+, B, C, D, E)
            $table->string('remarks')->nullable(); // Keterangan (Lulus / Tidak Lulus)
            $table->timestamps();

            $table->unique(['student_id', 'schedule_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
