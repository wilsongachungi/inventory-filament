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
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('provider_id');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade')->after('tenant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {

            $table->dropForeign(['customer_id']);
            $table->dropColumn('customer_id');

            $table->foreignId('provider_id')->constrained('providers')->onDelete('cascade')->after('tenant_id');
        });
    }
};
