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
        Schema::table('sales', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_id')->nullable(); // Add customer_id column
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null'); // Foreign key constraint
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['customer_id']); // Drop the foreign key
            $table->dropColumn('customer_id'); // Drop the customer_id column
        });
    }
};
