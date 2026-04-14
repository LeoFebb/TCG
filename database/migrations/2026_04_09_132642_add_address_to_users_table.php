<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('address')->nullable()->after('validation_notes');
            $table->string('city')->nullable()->after('address');
            $table->string('zip')->nullable()->after('city');
            $table->string('country')->default('Italia')->after('zip');
            $table->string('phone')->nullable()->after('country');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['address', 'city', 'zip', 'country', 'phone']);
        });
    }
};