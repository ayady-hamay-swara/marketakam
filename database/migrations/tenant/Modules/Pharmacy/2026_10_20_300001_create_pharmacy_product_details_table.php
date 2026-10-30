<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pharmacy_product_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->unique()->constrained('products')->restrictOnDelete();
            $table->string('generic_name')->nullable();
            $table->string('active_ingredient')->nullable();
            $table->string('strength')->nullable();
            $table->string('dosage_form')->nullable();
            $table->string('manufacturer')->nullable();
            $table->boolean('requires_prescription')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('pharmacy_product_details'); }
};
