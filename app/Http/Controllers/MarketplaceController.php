<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Transaction;
use App\Services\EscrowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * MarketplaceController
 *
 * Gestisce il listing delle carte, la creazione delle offerte di permuta
 * e le azioni post-pagamento dell'acquirente (conferma spedizione).
 */
class MarketplaceController extends Controller
{
    public function __construct() {}

    /**
     * Homepage del marketplace: lista carte disponibili con filtri.
     */
    public function index(Request $request)
    {
        $query = Card::with('owner')->available()->latest();

        if ($request->filled('category')) {
            $query->where('tcg_category', $request->category);
        }
        if ($request->get('type') === 'sale') {
            $query->whereNotNull('price');
        } elseif ($request->get('type') === 'trade') {
            $query->where('available_for_trade', true);
        }
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        if ($request->get('sort') === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($request->get('sort') === 'price_desc') {
            $query->orderBy('price', 'desc');
        }

        $cards = $query->paginate(24);

        return view('marketplace.index', compact('cards'));
    }

    public function trades(Request $request)
    {
        $cards = Card::with('owner')->available()->where('available_for_trade', true)->latest()->paginate(24);

        return view('marketplace.trades', compact('cards'));
    }

    public function category(Request $request, string $category)
    {
        $categories = ['pokemon', 'mtg', 'yugioh', 'onepiece', 'dragon_ball_super', 'naruto'];
        abort_if(!in_array($category, $categories), 404);

        $query = Card::with('owner')->available()->where('tcg_category', $category)->latest();

        if ($request->get('type') === 'sale') {
            $query->whereNotNull('price')->where('available_for_trade', false);
        } elseif ($request->get('type') === 'trade') {
            $query->where('available_for_trade', true)->whereNull('price');
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->get('sort') === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($request->get('sort') === 'price_desc') {
            $query->orderBy('price', 'desc');
        }

        $cards = $query->paginate(24);

        $info = [
            'pokemon' => ['nome' => 'Pokémon', 'icona' => '🔴', 'desc' => 'Carta base, olofoil, ex, GX, V e molto altro.', 'colore' => '#ef4444'],
            'mtg' => ['nome' => 'Magic: The Gathering', 'icona' => '🪄', 'desc' => 'Standard, Legacy, Vintage, Commander.', 'colore' => '#f59e0b'],
            'yugioh' => ['nome' => 'Yu-Gi-Oh!', 'icona' => '👁', 'desc' => 'Mostri, magie, trappole e carte rare.', 'colore' => '#8b5cf6'],
            'onepiece' => ['nome' => 'One Piece', 'icona' => '☠️', 'desc' => 'Carte dal gioco ufficiale One Piece.', 'colore' => '#f97316'],
            'dragon_ball_super' => ['nome' => 'Dragon Ball Super CG', 'icona' => '🐉', 'desc' => 'Carte dal gioco ufficiale Dragon Ball Super.', 'colore' => '#f97316'],
            'naruto' => ['nome' => 'Naruto Card Game', 'icona' => '🍃', 'desc' => 'Carte dal gioco ufficiale Naruto.', 'colore' => '#f97316'],
        ];

        return view('marketplace.category', [
            'cards' => $cards,
            'category' => $category,
            'info' => $info[$category],
        ]);
    }

    /**
     * Pagina di dettaglio di una carta.
     */
    public function show(Card $card, Request $request)
    {
        $card->load('owner');

        $tradeOptions = [];
        if (Auth::check() && $card->available_for_trade) {
            $tradeOptions = Card::where('user_id', Auth::id())->where('status', 'available')->where('id', '!=', $card->id)->get();
        }

        // Catalogo carte per la permuta
        $catalogCards = collect();
        if (Auth::check() && $card->available_for_trade) {
            $catalogCards = \App\Models\TcgCardCatalog::where('tcg_category', $card->tcg_category)
                ->orderBy('name')
                ->get(['id', 'name', 'set_name', 'card_number', 'rarity', 'image_url']);
        }

        return view('marketplace.show', compact('card', 'tradeOptions', 'catalogCards'));
    }

    /**
     * Crea un'offerta di permuta (trade offer).
     *
     * LOGICA PERMUTA:
     * 1. L'acquirente propone una propria carta in cambio
     * 2. Il venditore accetta o rifiuta
     * 3. Entrambi spediscono le carte al validatore
     * 4. Il validatore esamina entrambe → approva o rifiuta
     * 5. Il validatore rispedisce le carte ai nuovi proprietari
     */
    public function createTradeOffer(Request $request)
    {
        $validated = $request->validate([
            'card_id' => 'required|exists:cards,id',
            'offered_card_id' => 'required|exists:cards,id|different:card_id',
        ]);

        $targetCard = Card::findOrFail($validated['card_id']);
        $offeredCard = Card::findOrFail($validated['offered_card_id']);

        // Verifica che la carta offerta appartenga all'acquirente
        abort_if($offeredCard->user_id !== Auth::id(), 403, 'Non puoi offrire una carta che non ti appartiene.');

        // Verifica che la carta target sia disponibile per permuta
        abort_if(!$targetCard->available_for_trade || $targetCard->status !== 'available', 422);
        abort_if($targetCard->user_id === Auth::id(), 403, 'Non puoi fare permuta con te stesso.');

        DB::transaction(function () use ($targetCard, $offeredCard) {
            // Assegna un validatore disponibile (logica round-robin semplificata)
            // In produzione: algoritmo più sofisticato basato su categoria e carico di lavoro
            $validator = \App\Models\User::where('role', 'validator')->where('is_verified_validator', true)->whereJsonContains('tcg_categories', $targetCard->tcg_category)->inRandomOrder()->first();

            // Crea la transazione di permuta
            $transaction = Transaction::create([
                'buyer_id' => Auth::id(),
                'seller_id' => $targetCard->user_id,
                'card_id' => $targetCard->id,
                'offered_card_id' => $offeredCard->id,
                'validator_id' => $validator?->id,
                'type' => 'trade',
                'status' => 'pending',
            ]);

            // Blocca entrambe le carte durante la trattativa
            $targetCard->update(['status' => 'in_negotiation']);
            $offeredCard->update(['status' => 'in_negotiation']);
        });

        return redirect()->back()->with('success', 'Offerta di permuta inviata! Attendi la risposta del venditore.');
    }

    /**
     * Il venditore accetta l'offerta di permuta.
     * La transazione passa a 'in_validation' e vengono notificate le parti.
     */
    public function confirmTrade(Request $request, Transaction $transaction)
    {
        abort_if($transaction->seller_id !== Auth::id(), 403);
        abort_if($transaction->status !== 'pending', 422);
        abort_if($transaction->type !== 'trade', 422);

        $transaction->update(['status' => 'in_validation']);

        // TODO: Notifica email ad acquirente e venditore con indirizzo del validatore
        // Mail::to($transaction->buyer)->send(new TradeAccepted($transaction));

        return redirect()->back()->with('success', 'Permuta accettata! Entrambi dovete spedire le carte al validatore.');
    }

    /**
     * L'utente conferma di aver spedito la propria carta al validatore.
     * Aggiorna il flag shipped nella transazione.
     */
    public function markShipped(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'tracking_number' => 'required|string|max:100',
        ]);

        // Determina se chi sta confermando è l'acquirente o il venditore
        $isBuyer = $transaction->buyer_id === Auth::id();
        $isSeller = $transaction->seller_id === Auth::id();

        abort_if(!$isBuyer && !$isSeller, 403);

        // Aggiorna il campo corretto in base al ruolo
        if ($isSeller) {
            $transaction->update([
                'seller_shipped' => true,
                'tracking_number' => $validated['tracking_number'],
            ]);
        } else {
            $transaction->update([
                'buyer_shipped' => true,
                'return_tracking_number' => $validated['tracking_number'],
            ]);
        }

        return redirect()->back()->with('success', 'Spedizione confermata. Il validatore riceverà la carta a breve.');
    }

    public function search(Request $request)
    {
        $query = $request->get('q');

        $cards = Card::with('owner')
            ->available()
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('set_name', 'like', "%{$query}%")
                    ->orWhere('tcg_category', 'like', "%{$query}%")
                    ->orWhere('rarity', 'like', "%{$query}%");
            })
            ->latest()
            ->paginate(24);

        return view('marketplace.search', compact('cards', 'query'));
    }
}
