// ======================================================
// MIGRATION
// create_pairwise_criteria_table.php
// ======================================================

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

            $table->foreignId('group_id')
                ->constrained('groups')
                ->onDelete('cascade');

            $table->foreignId('criteria_first_id')
                ->constrained('criteria')
                ->onDelete('cascade');

            $table->foreignId('criteria_second_id')
                ->constrained('criteria')
                ->onDelete('cascade');

            $table->double('value')->nullable();

            $table->timestamps();

            $table->unique([
                'group_id',
                'criteria_first_id',
                'criteria_second_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pairwise_criteria');
    }
};