<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['user', 'validator', 'admin'])->default('user')->after('email');
            $table->boolean('is_verified_validator')->default(false)->after('role');
            $table->string('stripe_connect_id')->nullable()->after('is_verified_validator');
            $table->string('identity_document_path')->nullable()->after('stripe_connect_id');
            $table->json('tcg_categories')->nullable()->after('identity_document_path');
            $table->text('validation_notes')->nullable()->after('tcg_categories');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role', 'is_verified_validator', 'stripe_connect_id',
                'identity_document_path', 'tcg_categories', 'validation_notes',
            ]);
        });
    }
};