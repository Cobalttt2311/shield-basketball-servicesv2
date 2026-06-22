<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluation_reports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('evaluation_id')
                ->constrained('evaluations')
                ->cascadeOnDelete();

            $table->foreignId('player_id')
                ->constrained('players')
                ->cascadeOnDelete();

            $table->foreignId('recommended_position_id')
                ->constrained('positions')
                ->cascadeOnDelete();

            $table->foreignId('final_position_id')
                ->constrained('positions')
                ->cascadeOnDelete();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique([
                'evaluation_id',
                'player_id',
            ], 'evaluation_report_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluation_reports');
    }
};
