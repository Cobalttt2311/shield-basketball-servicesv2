<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->date('birth_date');
            $table->foreignId('group_id')->constrained('groups')->cascadeOnDelete();
            $table->string('phone_number');
            $table->string('email')->unique();
            $table->float('height');
            $table->float('weight');
            $table->string('parent_name');
            $table->string('parent_phone');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};