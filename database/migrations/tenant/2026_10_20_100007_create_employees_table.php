<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('employees', function (Blueprint $table) {
            $table->id(); $table->unsignedBigInteger('user_id')->nullable()->unique(); // global account, no cross-db FK
            $table->string('employee_code', 50)->unique(); $table->string('name'); $table->string('position', 100)->nullable();
            $table->string('email')->nullable(); $table->string('phone', 30)->nullable(); $table->string('address')->nullable();
            $table->unsignedBigInteger('salary')->nullable(); // whole IQD
            $table->date('hire_date')->nullable(); $table->boolean('is_active')->default(true)->index(); $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('employees'); }
};
