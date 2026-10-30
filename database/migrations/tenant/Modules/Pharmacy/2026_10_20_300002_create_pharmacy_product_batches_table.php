<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pharmacy_product_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            $table->string('batch_number', 100);
            $table->date('expiry_date')->index();
            $table->decimal('quantity', 14, 3)->default(0);
            $table->unsignedBigInteger('purchase_cost')->nullable(); // whole IQD per product unit
            $table->timestamps();
            $table->unique(['branch_id', 'product_id', 'batch_number']);
            $table->index(['branch_id', 'product_id', 'expiry_date']);
        });
    }
    public function down(): void { Schema::dropIfExists('pharmacy_product_batches'); }
};
