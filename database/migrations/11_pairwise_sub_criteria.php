// ======================================================
// MIGRATION
// create_pairwise_sub_criteria_table.php
// ======================================================

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

            $table->foreignId('criteria_id')
                ->constrained('criteria')
                ->onDelete('cascade');

            $table->foreignId('sub_criteria_first_id')
                ->constrained('sub_criteria')
                ->onDelete('cascade');

            $table->foreignId('sub_criteria_second_id')
                ->constrained('sub_criteria')
                ->onDelete('cascade');

            $table->double('value')->nullable();

            $table->timestamps();

            $table->unique([
                'criteria_id',
                'sub_criteria_first_id',
                'sub_criteria_second_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pairwise_sub_criteria');
    }
};