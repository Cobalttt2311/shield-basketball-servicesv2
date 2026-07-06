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
        Schema::table('pairwise_sub_criteria', function (Blueprint $table) {
            $table->decimal('value', 10, 3)->nullable()->change();
        });

        Schema::table('pairwise_criteria', function (Blueprint $table) {
            $table->decimal('value', 10, 3)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       
    }
};
