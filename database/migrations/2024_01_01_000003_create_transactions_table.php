<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migrazione tabella transactions.
 *
 * Questa tabella gestisce sia le VENDITE che le PERMUTE (trade).
 *
 * Flusso VENDITA:
 *   pending → paid_escrow → in_validation → validated → shipping → completed
 *
 * Flusso PERMUTA:
 *   pending → in_validation (entrambi spediscono al validatore)
 *           → validated (validatore approva entrambe le carte)
 *           → shipping (validatore rispedisce le carte ai nuovi proprietari)
 *           → completed
 *
 * In caso di problemi: qualsiasi stato → disputed
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            // Parti della transazione
            $table->foreignId('buyer_id')->constrained('users');
            $table->foreignId('seller_id')->constrained('users');
            $table->foreignId('card_id')->constrained('cards');  // Carta oggetto della transazione

            // Solo per le PERMUTE: carta offerta dall'acquirente in cambio
            $table->foreignId('offered_card_id')
                  ->nullable()
                  ->constrained('cards');

            // Validatore assegnato a questa transazione
            $table->foreignId('validator_id')
                  ->nullable()
                  ->constrained('users');

            // Tipo di transazione
            $table->enum('type', ['sale', 'trade'])->default('sale');

            /**
             * Macchina a stati della transazione:
             * - pending:       transazione creata, in attesa di pagamento (vendita) o conferma (permuta)
             * - paid_escrow:   i fondi sono trattenuti dalla piattaforma (solo vendite)
             * - in_validation: le carte sono state inviate al validatore
             * - validated:     il validatore ha approvato le carte
             * - shipping:      le carte sono in spedizione verso i destinatari finali
             * - completed:     transazione conclusa, fondi rilasciati al venditore
             * - disputed:      controversia aperta, fondi bloccati fino a risoluzione admin
             */
            $table->enum('status', [
                'pending',
                'paid_escrow',
                'in_validation',
                'validated',
                'shipping',
                'completed',
                'disputed',
            ])->default('pending');

            // --- Campi per le VENDITE ---
            $table->decimal('amount', 10, 2)->nullable();              // Prezzo della transazione
            $table->decimal('platform_fee', 10, 2)->nullable();        // Commissione piattaforma (calcolata al momento del pagamento)
            $table->string('stripe_intent_id')->nullable();            // ID del PaymentIntent Stripe
            $table->string('stripe_transfer_id')->nullable();          // ID del Transfer Stripe verso il venditore (popolato al completamento)
            $table->timestamp('escrow_paid_at')->nullable();           // Timestamp del pagamento in escrow
            $table->timestamp('funds_released_at')->nullable();        // Timestamp del rilascio fondi al venditore

            // --- Campi per la VALIDAZIONE ---
            $table->boolean('buyer_shipped')->default(false);          // L'acquirente ha spedito al validatore (per permute)
            $table->boolean('seller_shipped')->default(false);         // Il venditore ha spedito al validatore
            $table->text('validator_notes')->nullable();               // Note del validatore sull'esito
            $table->timestamp('validated_at')->nullable();             // Timestamp della validazione

            // --- Tracking spedizione ---
            $table->string('tracking_number')->nullable();             // Numero tracking spedizione principale
            $table->string('return_tracking_number')->nullable();      // Numero tracking rispedizione (permute)

            $table->timestamps();
            $table->softDeletes();

            // Indici per query frequenti nella dashboard del validatore
            $table->index(['validator_id', 'status']);
            $table->index(['buyer_id', 'status']);
            $table->index(['seller_id', 'status']);
            $table->index('stripe_intent_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
