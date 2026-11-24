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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('slug', 150)->unique();
            $table->string('tagline', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('icon', 100)->nullable();
            $table->string('image_path', 500);
            $table->string('badge_text', 50)->nullable();
            $table->json('features')->nullable();
            $table->string('action_text', 100)->default('Learn More');
            $table->string('action_url', 255)->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
