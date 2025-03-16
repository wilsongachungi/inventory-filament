<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->string('name');
            $table->string('code')->unique();
            $table->decimal('quantity', 10, 2)->default(1);

            // Pricing fields
            $table->decimal('regular_price', 10, 2)->default(0);
            $table->decimal('discounted_price', 10, 2)->nullable();
            $table->decimal('in_stock_price', 10, 2)->default(0);

            $table->decimal('safety_stock', 10, 2)->default(0);
            $table->string('batch_number')->nullable();
            $table->date('expiry_date')->nullable();
            $table->boolean('requires_prescription')->default(false);
            $table->string('strength')->nullable();
            $table->text('description')->nullable();
            $table->json('data')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
