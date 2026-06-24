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
        Schema::create('pairwise_sets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('group_id')->nullable()->constrained('groups')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::table('pairwise_criteria', function (Blueprint $table) {
            $table->foreignId('pairwise_set_id')->nullable()->constrained('pairwise_sets')->cascadeOnDelete();
        });

        Schema::table('pairwise_sub_criteria', function (Blueprint $table) {
            $table->foreignId('pairwise_set_id')->nullable()->constrained('pairwise_sets')->cascadeOnDelete();
        });

        Schema::table('criteria_weights', function (Blueprint $table) {
            $table->foreignId('pairwise_set_id')->nullable()->constrained('pairwise_sets')->cascadeOnDelete();
        });

        Schema::table('sub_criteria_weights', function (Blueprint $table) {
            $table->foreignId('pairwise_set_id')->nullable()->constrained('pairwise_sets')->cascadeOnDelete();
        });

        Schema::table('evaluations', function (Blueprint $table) {
            $table->foreignId('pairwise_set_id')->nullable()->constrained('pairwise_sets')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->dropForeign(['pairwise_set_id']);
            $table->dropColumn('pairwise_set_id');
        });

        Schema::table('sub_criteria_weights', function (Blueprint $table) {
            $table->dropForeign(['pairwise_set_id']);
            $table->dropColumn('pairwise_set_id');
        });

        Schema::table('criteria_weights', function (Blueprint $table) {
            $table->dropForeign(['pairwise_set_id']);
            $table->dropColumn('pairwise_set_id');
        });

        Schema::table('pairwise_sub_criteria', function (Blueprint $table) {
            $table->dropForeign(['pairwise_set_id']);
            $table->dropColumn('pairwise_set_id');
        });

        Schema::table('pairwise_criteria', function (Blueprint $table) {
            $table->dropForeign(['pairwise_set_id']);
            $table->dropColumn('pairwise_set_id');
        });

        Schema::dropIfExists('pairwise_sets');
    }
};
