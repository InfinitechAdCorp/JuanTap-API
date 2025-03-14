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
        Schema::create('custom_templates', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('profile_id');
            $table->string('background_color')->nullable();
            $table->string('text_color')->nullable();
            $table->string('font_family')->nullable();
            $table->string('card_color')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_templates');
    }
};
