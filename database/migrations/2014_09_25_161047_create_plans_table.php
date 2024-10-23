<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('space_disk')->default(1); //GB
            $table->integer('space_user')->nullable(); // Null or Int
            $table->decimal('value', 10, 2)->default(0);
            $table->enum('validate', ['month', 'year', 'lifetime'])->default('month');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('plans');
    }
};
