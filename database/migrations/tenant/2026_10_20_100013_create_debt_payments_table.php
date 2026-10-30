<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('debt_payments', function (Blueprint $table) {
            $table->id(); $table->foreignId('debt_id')->constrained('debts')->restrictOnDelete();
            $table->unsignedBigInteger('amount'); $table->string('payment_method', 30)->default('cash');
            $table->timestamp('paid_at'); $table->unsignedBigInteger('user_id')->nullable()->index(); // global user ID
            $table->text('note')->nullable(); $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('debt_payments'); }
};
