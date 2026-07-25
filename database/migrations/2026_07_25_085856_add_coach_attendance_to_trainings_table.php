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
        Schema::table('trainings', function (Blueprint $table) {
            $table->string('coach_attendance_status')->nullable();
            $table->foreignId('recorded_by_coach_id')->nullable()->constrained('coaches')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trainings', function (Blueprint $table) {
            $table->dropForeign(['recorded_by_coach_id']);
            $table->dropColumn(['coach_attendance_status', 'recorded_by_coach_id']);
        });
    }
};
