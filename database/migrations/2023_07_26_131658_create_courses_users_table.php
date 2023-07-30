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
        Schema::create('courses_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('courses_id');
            $table->string('user_jmbg');
            $table->timestamps();

            $table->foreign('courses_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('user_jmbg')->references('jmbg')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses_users');
    }
};
