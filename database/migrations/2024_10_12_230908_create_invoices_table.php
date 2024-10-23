<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('plan_id')->constrained('plans');
            $table->string('name')->default('Plano');
            $table->string('description')->default('---');
            $table->decimal('value', 10, 2)->default(0);

            $table->date('due_date_payment');
            $table->longText('token_payment')->nullable();
            $table->longText('url_payment')->nullable();
            $table->integer('status_payment')->default(0); // 0 is pendent 1 is approved
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('invoice');
    }
};
