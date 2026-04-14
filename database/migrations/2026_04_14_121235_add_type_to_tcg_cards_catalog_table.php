<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tcg_cards_catalog', function (Blueprint $table) {
            $table->enum('type', ['sale', 'trade', 'both'])->default('both')->after('image_url');
        });
    }

    public function down(): void
    {
        Schema::table('tcg_cards_catalog', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};