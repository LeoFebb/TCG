<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->boolean('validator_shipped')->default(false)->after('buyer_validated');
            $table->boolean('buyer_validator_shipped')->default(false)->after('validator_shipped');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['validator_shipped', 'buyer_validator_shipped']);
        });
    }
};