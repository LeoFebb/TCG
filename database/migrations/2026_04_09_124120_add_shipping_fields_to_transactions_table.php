<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('shipping_cost', 8, 2)->default(5.90)->after('platform_fee');
            $table->string('shipping_name')->nullable()->after('shipping_cost');
            $table->string('shipping_address')->nullable()->after('shipping_name');
            $table->string('shipping_city')->nullable()->after('shipping_address');
            $table->string('shipping_zip')->nullable()->after('shipping_city');
            $table->string('shipping_country')->default('IT')->after('shipping_zip');
            $table->string('shipping_phone')->nullable()->after('shipping_country');
            $table->timestamp('label_generated_at')->nullable()->after('shipping_phone');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_cost', 'shipping_name', 'shipping_address',
                'shipping_city', 'shipping_zip', 'shipping_country',
                'shipping_phone', 'label_generated_at',
            ]);
        });
    }
};