<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tcg_cards_catalog', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('set_name');
            $table->string('card_number')->nullable();
            $table->string('tcg_category');
            $table->string('rarity')->nullable();
            $table->string('image_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tcg_cards_catalog');
    }
};
