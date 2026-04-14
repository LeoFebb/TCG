<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShippingController extends Controller
{
    /**
     * ETICHETTA 1: Venditore → Validatore
     * Generata dal VENDITORE dopo che l'acquirente ha pagato.
     * La carta va spedita al centro di validazione TCG Vault.
     */
    public function generateLabel(Transaction $transaction)
    {
        // Solo il VENDITORE può generare questa etichetta
        abort_if($transaction->seller_id !== Auth::id(), 403, 'Solo il venditore può generare questa etichetta.');
        abort_if(!in_array($transaction->status, ['paid_escrow', 'in_validation']), 422);

        $transaction->load(['card', 'buyer', 'seller', 'validator']);

        // Indirizzo destinazione: il VALIDATORE assegnato
        // Se non c'è un validatore specifico, usiamo il centro TCG Vault
        // Usa l'indirizzo del validatore assegnato alla transazione
        if ($transaction->validator) {
            $validatorAddress = [
                'name' => $transaction->validator->name . ' (Validatore TCG Vault)',
                'address' => $transaction->validator->address ?? 'Da confermare',
                'city' => $transaction->validator->city ?? '',
                'zip' => $transaction->validator->zip ?? '',
                'country' => $transaction->validator->country ?? 'Italia',
                'phone' => $transaction->validator->phone ?? '',
            ];
        } else {
            // Nessun validatore ancora assegnato
            $validatorAddress = [
                'name' => 'TCG Vault — Centro Validazione',
                'address' => 'Il validatore verrà assegnato a breve',
                'city' => '',
                'zip' => '',
                'country' => 'Italia',
                'phone' => 'support@tcgvault.it',
            ];
        }

        $data = [
            'transaction' => $transaction,
            'mittente' => [
                'nome' => $transaction->seller->name,
                'ruolo' => 'Venditore',
            ],
            'destinatario' => $validatorAddress,
            'tipo' => 'VENDITORE → VALIDATORE',
            'istruzioni' => 'Inserire la carta in un toploader. Il validatore esaminerà la carta e la spedirà all\'acquirente.',
            'generated_at' => now()->format('d/m/Y H:i'),
            'tracking_code' => 'TCG-' . str_pad($transaction->id, 6, '0', STR_PAD_LEFT) . '-V',
        ];

        $transaction->update(['label_generated_at' => now()]);

        $pdf = Pdf::loadView('shipping.label', $data)->setPaper([0, 0, 283, 425], 'portrait');

        return $pdf->download("etichetta-venditore-{$transaction->id}.pdf");
    }

    /**
     * ETICHETTA 2: Validatore → Acquirente
     * Generata dal VALIDATORE dopo aver approvato la carta.
     * La carta va rispedita all'acquirente finale.
     */
    public function generateReturnLabel(Transaction $transaction)
    {
        // Solo il VALIDATORE può generare questa etichetta
        abort_if($transaction->validator_id !== Auth::id(), 403, 'Solo il validatore può generare questa etichetta.');
        abort_if($transaction->status !== 'validated', 422, 'La transazione deve essere validata prima di generare l\'etichetta di rispedizione.');

        $transaction->load(['card', 'buyer', 'seller', 'validator']);

        $data = [
            'transaction' => $transaction,
            'mittente' => [
                'nome' => 'TCG Vault — Centro Validazione',
                'ruolo' => 'Validatore',
                'address' => 'Via Roma 1, 20100 Milano',
                'phone' => '+39 02 1234567',
            ],
            'destinatario' => [
                'name' => $transaction->shipping_name ?? $transaction->buyer->name,
                'address' => $transaction->shipping_address,
                'city' => $transaction->shipping_city,
                'zip' => $transaction->shipping_zip,
                'country' => $transaction->shipping_country ?? 'Italia',
                'phone' => $transaction->shipping_phone,
            ],
            'tipo' => 'VALIDATORE → ACQUIRENTE',
            'istruzioni' => 'Carta certificata autentica da TCG Vault. Spedire con cura in imballo protettivo.',
            'generated_at' => now()->format('d/m/Y H:i'),
            'tracking_code' => 'TCG-' . str_pad($transaction->id, 6, '0', STR_PAD_LEFT) . '-R',
            'is_return' => true,
        ];

        $pdf = Pdf::loadView('shipping.label', $data)->setPaper([0, 0, 283, 425], 'portrait');

        return $pdf->download("etichetta-rispedizione-{$transaction->id}.pdf");
    }

    /**
     * ETICHETTA PERMUTA ACQUIRENTE: Acquirente → Validatore acquirente
     */
    public function generateBuyerTradeLabel(Transaction $transaction)
    {
        abort_if($transaction->buyer_id !== Auth::id(), 403);
        abort_if($transaction->type !== 'trade', 422);
        abort_if(!in_array($transaction->status, ['accepted', 'in_validation']), 422);

        $transaction->load(['card', 'buyer', 'seller', 'buyerValidator']);

        $validator = $transaction->buyerValidator;

        $data = [
            'transaction' => $transaction,
            'mittente' => [
                'nome' => $transaction->buyer->name,
                'ruolo' => 'Acquirente',
                'address' => $transaction->buyer->address ?? '',
                'phone' => $transaction->buyer->phone ?? '',
            ],
            'destinatario' => [
                'name' => $validator ? $validator->name . ' (Validatore TCG Vault)' : 'TCG Vault — Centro Validazione',
                'address' => $validator?->address ?? 'Da confermare',
                'city' => $validator?->city ?? '',
                'zip' => $validator?->zip ?? '',
                'country' => $validator?->country ?? 'Italia',
                'phone' => $validator?->phone ?? '',
            ],
            'tipo' => 'ACQUIRENTE → VALIDATORE',
            'istruzioni' => 'Inserire la carta in un toploader. Il validatore esaminerà la carta.',
            'generated_at' => now()->format('d/m/Y H:i'),
            'tracking_code' => 'TCG-' . str_pad($transaction->id, 6, '0', STR_PAD_LEFT) . '-BV',
        ];

        $pdf = Pdf::loadView('shipping.label', $data)->setPaper([0, 0, 283, 425], 'portrait');

        return $pdf->download("etichetta-acquirente-{$transaction->id}.pdf");
    }

    /**
     * ETICHETTA PERMUTA VENDITORE: Venditore → Validatore venditore
     */
    public function generateSellerTradeLabel(Transaction $transaction)
    {
        abort_if($transaction->seller_id !== Auth::id(), 403);
        abort_if($transaction->type !== 'trade', 422);
        abort_if(!in_array($transaction->status, ['accepted', 'in_validation']), 422);

        $transaction->load(['card', 'buyer', 'seller', 'validator']);

        $validator = $transaction->validator;

        $data = [
            'transaction' => $transaction,
            'mittente' => [
                'nome' => $transaction->seller->name,
                'ruolo' => 'Venditore',
                'address' => $transaction->seller->address ?? '',
                'phone' => $transaction->seller->phone ?? '',
            ],
            'destinatario' => [
                'name' => $validator ? $validator->name . ' (Validatore TCG Vault)' : 'TCG Vault — Centro Validazione',
                'address' => $validator?->address ?? 'Da confermare',
                'city' => $validator?->city ?? '',
                'zip' => $validator?->zip ?? '',
                'country' => $validator?->country ?? 'Italia',
                'phone' => $validator?->phone ?? '',
            ],
            'tipo' => 'VENDITORE → VALIDATORE',
            'istruzioni' => 'Inserire la carta in un toploader. Il validatore esaminerà la carta.',
            'generated_at' => now()->format('d/m/Y H:i'),
            'tracking_code' => 'TCG-' . str_pad($transaction->id, 6, '0', STR_PAD_LEFT) . '-SV',
        ];

        $pdf = Pdf::loadView('shipping.label', $data)->setPaper([0, 0, 283, 425], 'portrait');

        return $pdf->download("etichetta-venditore-{$transaction->id}.pdf");
    }
}
