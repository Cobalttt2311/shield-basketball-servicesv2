<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Bersihkan data lama karena perubahan skema agar tidak terjadi constraint NULL error
        Schema::disableForeignKeyConstraints();
        DB::table('pairwise_sub_criteria')->delete();
        DB::table('pairwise_criteria')->delete();
        DB::table('sub_criteria_weights')->delete();
        DB::table('criteria_weights')->delete();
        DB::table('sub_criteria')->delete();
        DB::table('criteria')->delete();
        Schema::enableForeignKeyConstraints();

        Schema::table('criteria', function (Blueprint $table) {
            // Hapus unique constraint lama
            $table->dropUnique('criteria_group_id_name_unique');

            // Hapus foreign key group_id dan kolomnya
            $table->dropForeign(['group_id']);
            $table->dropColumn('group_id');

            // Tambahkan foreign key criteria_set_id dan unique constraint baru
            $table->foreignId('criteria_set_id')->after('id')->constrained('criteria_sets')->cascadeOnDelete();
            $table->unique(['criteria_set_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::table('criteria', function (Blueprint $table) {
            // Hapus unique constraint baru dan foreign key/kolom criteria_set_id
            $table->dropUnique('criteria_criteria_set_id_name_unique');
            $table->dropForeign(['criteria_set_id']);
            $table->dropColumn('criteria_set_id');

            // Kembalikan kolom group_id, foreign key, dan unique constraint lama
            $table->foreignId('group_id')->constrained('groups')->cascadeOnDelete();
            $table->unique(['group_id', 'name']);
        });
    }
};
