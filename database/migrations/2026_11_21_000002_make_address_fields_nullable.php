<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->string('house_or_building')->nullable()->change();
            $table->string('door_number')->nullable()->change();
            $table->string('street')->nullable()->change();
            $table->string('area_locality')->nullable()->change();
            $table->string('city')->nullable()->default('Chennai')->change();
            $table->string('pincode', 10)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->string('house_or_building')->nullable(false)->change();
            $table->string('door_number')->nullable(false)->change();
            $table->string('street')->nullable(false)->change();
            $table->string('area_locality')->nullable(false)->change();
            $table->string('city')->nullable(false)->default('Chennai')->change();
            $table->string('pincode', 10)->nullable(false)->change();
        });
    }
};
