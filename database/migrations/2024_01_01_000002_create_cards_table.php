<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migrazione tabella cards.
 * Ogni carta ha uno stato che ne riflette la disponibilità nel marketplace.
 * Lo stato 'in_negotiation' blocca la carta durante una trattativa attiva
 * per evitare double-spending (stessa carta venduta due volte).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();

            // Proprietario corrente della carta
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Dati identificativi della carta
            $table->string('name');
            $table->string('set_name');         // Nome del set (es. "Base Set", "Scarlet & Violet")
            $table->string('card_number');       // Numero carta nel set (es. "025/165")
            $table->string('tcg_category');      // Categoria: mtg, pokemon, yugioh, ecc.
            $table->string('rarity');            // Common, Uncommon, Rare, Mythic, ecc.
            $table->string('condition');         // NM, LP, MP, HP, DMG (Near Mint → Damaged)

            // Prezzo richiesto per la vendita diretta
            $table->decimal('price', 10, 2)->nullable();

            // Flag che indica se la carta è disponibile anche per permuta
            $table->boolean('available_for_trade')->default(false);

            // Stato della carta nel marketplace
            // available: visibile e acquistabile
            // in_negotiation: bloccata in una transazione attiva
            // sold: venduta, rimane per storico
            $table->enum('status', ['available', 'in_negotiation', 'sold'])->default('available');

            // Immagini della carta (fronte/retro) per la verifica del validatore
            $table->json('images')->nullable(); // Array di path: ["front.jpg", "back.jpg"]

            // Descrizione aggiuntiva del venditore (difetti, autografi, ecc.)
            $table->text('description')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indici per le query più frequenti nel marketplace
            $table->index(['status', 'tcg_category']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
