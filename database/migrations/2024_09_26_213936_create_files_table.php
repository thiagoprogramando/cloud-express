<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up(): void {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('folder_id')->nullable()->constrained('folders')->cascadeOnDelete();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->string('password')->nullable();
            $table->boolean('visible')->default(false);
            $table->string('extension')->nullable();
            $table->bigInteger('space_disk');
            $table->string('file');
            $table->string('url_visible')->nullable();
            $table->string('drive_file_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('files');
    }
};
