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
            $table->boolean('is_consistent')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pairwise_sets', function (Blueprint $table) {
            $table->dropColumn('is_consistent');
        });
    }
};
