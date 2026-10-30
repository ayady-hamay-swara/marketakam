<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('market_product_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->unique()->constrained('products')->restrictOnDelete();
            $table->string('brand', 120)->nullable();
            $table->boolean('sold_by_weight')->default(false);
            $table->decimal('package_size', 12, 3)->nullable();
            $table->string('package_unit', 30)->nullable();
            $table->string('shelf_location', 80)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('market_product_details');
    }
};
