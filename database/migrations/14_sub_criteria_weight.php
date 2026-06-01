<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sub_criteria_weights', function (Blueprint $table) {

            $table->id();

            $table->foreignId('position_id')
                ->constrained('positions')
                ->cascadeOnDelete();

            $table->foreignId('sub_criteria_id')
                ->constrained('sub_criteria')
                ->cascadeOnDelete();

            $table->decimal('weight', 12, 8);

            $table->timestamps();

            $table->unique([
                'position_id',
                'sub_criteria_id'
            ], 'subcriteria_weight_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_criteria_weights');
    }
};