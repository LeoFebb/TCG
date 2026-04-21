<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{
    public function create()
    {
        return view('cards.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'set_name' => 'required|string|max:255',
            'card_number' => 'required|string|max:50',
            'tcg_category' => 'required|in:mtg,pokemon,yugioh,onepiece',
            'rarity' => 'required|string|max:100',
            'condition' => 'required|in:NM,LP,MP,HP,DMG',
            'price' => 'nullable|numeric|min:0.01',
            'available_for_trade' => 'boolean',
            'description' => 'nullable|string|max:2000',
            'images' => 'required|array|min:1|max:5',
            'images.*' => 'required|image|mimes:jpeg,jpg,png|max:3072',
        ]);

        $validated['is_validated'] = true;

        // Salva le immagini e raccoglie i percorsi
        $imagePaths = [];
        foreach ($request->file('images') as $image) {
            $imagePaths[] = $image->store('cards', 'public');
        }

        $validated['user_id'] = Auth::id();
        $validated['available_for_trade'] = $request->has('available_for_trade');
        $validated['status'] = 'available';
        $validated['images'] = json_encode($imagePaths);

        Card::create($validated);

        return redirect()->route('marketplace.index')->with('success', 'Carta pubblicata con successo!');
    }

    public function destroy(Card $card)
    {
        // Solo il proprietario può eliminare la carta
        abort_if($card->user_id !== Auth::id(), 403);
        abort_if($card->status !== 'available', 422, 'Non puoi eliminare una carta in trattativa.');

        $card->delete();

        return redirect()->route('cards.my')->with('success', 'Carta eliminata con successo!');
    }
}
