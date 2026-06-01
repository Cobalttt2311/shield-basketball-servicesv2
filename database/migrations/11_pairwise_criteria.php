<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pairwise_criteria', function (Blueprint $table) {

            $table->id();

            $table->foreignId('position_id')
                ->constrained('positions')
                ->cascadeOnDelete();

            $table->foreignId('criteria_first_id')
                ->constrained('criteria')
                ->cascadeOnDelete();

            $table->foreignId('criteria_second_id')
                ->constrained('criteria')
                ->cascadeOnDelete();

            $table->decimal('value', 10, 4)
                ->nullable();

            $table->timestamps();

            $table->unique([
                'position_id',
                'criteria_first_id',
                'criteria_second_id'
            ], 'pairwise_criteria_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pairwise_criteria');
    }
};