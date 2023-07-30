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
        Schema::create('users_passwords', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('password');
            $table->timestamps();

            $table->foreign('user_id')->references('jmbg')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_passwords');
    }
};
