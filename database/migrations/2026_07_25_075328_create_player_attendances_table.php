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
        Schema::create('player_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')
                ->constrained('trainings')
                ->cascadeOnDelete();
            $table->foreignId('player_id')
                ->constrained('players')
                ->cascadeOnDelete();
            $table->enum('status', ['present', 'absent']);
            $table->text('description')->nullable();
            $table->dateTime('attended_at')->nullable();
            $table->timestamps();

            $table->unique(['training_id', 'player_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_attendances');
    }
};
