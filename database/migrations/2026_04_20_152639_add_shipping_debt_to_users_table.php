<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('has_shipping_debt')->default(false)->after('vat_number');
            $table->decimal('shipping_debt_amount', 8, 2)->default(0)->after('has_shipping_debt');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['has_shipping_debt', 'shipping_debt_amount']);
        });
    }
};