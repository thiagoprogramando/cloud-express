<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create('folder_accesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('folder_id')->constrained('folders')->cascadeOnDelete();
            $table->integer('permission')->default(2); // 1 - is admin 2 - is user 3 - client
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('folder_accesses');
    }
};
