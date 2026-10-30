<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mobile_phone_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            $table->string('imei', 32)->nullable()->unique();
            $table->string('serial_number', 100)->nullable()->unique();
            $table->string('status', 30)->default('in_stock')->index(); // in_stock, sold, returned, repair
            $table->unsignedBigInteger('purchase_cost')->nullable(); // whole IQD
            $table->foreignId('sold_order_item_id')->nullable()->constrained('order_items')->nullOnDelete();
            $table->timestamps();
            $table->index(['branch_id', 'product_id', 'status']);
        });
    }
    public function down(): void { Schema::dropIfExists('mobile_phone_units'); }
};
