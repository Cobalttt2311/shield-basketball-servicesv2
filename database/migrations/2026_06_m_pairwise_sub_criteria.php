<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pairwise_sub_criteria', function (Blueprint $table) {

            $table->id();

            $table->foreignId('position_id')
                ->constrained('positions')
                ->cascadeOnDelete();

            $table->foreignId('criteria_id')
                ->constrained('criteria')
                ->cascadeOnDelete();

            $table->foreignId('sub_criteria_first_id')
                ->constrained('sub_criteria')
                ->cascadeOnDelete();

            $table->foreignId('sub_criteria_second_id')
                ->constrained('sub_criteria')
                ->cascadeOnDelete();

            $table->decimal('value', 10, 3)
                ->nullable();

            $table->timestamps();

            $table->unique([
                'position_id',
                'criteria_id',
                'sub_criteria_first_id',
                'sub_criteria_second_id',
            ], 'pairwise_subcriteria_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pairwise_sub_criteria');
    }
};
