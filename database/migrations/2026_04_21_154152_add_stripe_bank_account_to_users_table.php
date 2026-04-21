<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('stripe_bank_account_id')->nullable()->after('stripe_connect_id');
            $table->boolean('stripe_onboarding_complete')->default(false)->after('stripe_bank_account_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['stripe_bank_account_id', 'stripe_onboarding_complete']);
        });
    }
};