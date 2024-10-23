<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('photo')->nullable();
            $table->string('name');
            $table->string('cpfcnpj')->nullable();
            $table->enum('role', ['company', 'user', 'admin'])->default('company');
            $table->enum('type', ['admin', 'user'])->default('user');
            $table->decimal('wallet', 10, 2)->default(0);
            $table->foreignId('plan_id')->default(1)->constrained('plans');
            $table->foreignId('company_id')->nullable()->constrained('users');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('customer')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('users');
    }
};
