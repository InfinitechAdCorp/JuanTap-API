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
        Schema::create('template_user', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('template_id');
            $table->foreignUlid('user_id');
            $table->boolean('published')->default(0);
            $table->boolean('favorite')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_user');
    }
};
