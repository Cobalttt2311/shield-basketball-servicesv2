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
        Schema::table('trainings', function (Blueprint $table) {
            $table->boolean('is_finalized')->default(false);
        });

        Schema::table('player_attendances', function (Blueprint $table) {
            $table->string('status')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('player_attendances', function (Blueprint $table) {
            $table->string('status')->nullable(false)->change();
        });

        Schema::table('trainings', function (Blueprint $table) {
            $table->dropColumn('is_finalized');
        });
    }
};
