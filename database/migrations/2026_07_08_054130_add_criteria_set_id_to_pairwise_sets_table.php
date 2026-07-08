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
        Schema::table('pairwise_sets', function (Blueprint $table) {
            $table->foreignId('criteria_set_id')->nullable()->constrained('criteria_sets')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pairwise_sets', function (Blueprint $table) {
            $table->dropForeign(['criteria_set_id']);
            $table->dropColumn('criteria_set_id');
        });
    }
};
