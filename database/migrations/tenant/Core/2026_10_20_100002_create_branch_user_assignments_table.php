<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('branch_user_assignments', function (Blueprint $table) {
            $table->id();
            // Global users.id; no cross-database foreign key.
            $table->unsignedBigInteger('user_id');
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'branch_id']); $table->index('user_id');
        });
    }
    public function down(): void { Schema::dropIfExists('branch_user_assignments'); }
};
