<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Statistiche
        $stats = [
            'carte_pubblicate'  => Card::where('user_id', $user->id)->count(),
            'carte_disponibili' => Card::where('user_id', $user->id)->where('status', 'available')->count(),
            'vendite_attive'    => Transaction::where('seller_id', $user->id)->whereNotIn('status', ['completed', 'disputed'])->count(),
            'acquisti_attivi'   => Transaction::where('buyer_id', $user->id)->whereNotIn('status', ['completed', 'disputed'])->count(),
            'vendite_completate'=> Transaction::where('seller_id', $user->id)->where('status', 'completed')->count(),
            'guadagni_totali'   => Transaction::where('seller_id', $user->id)->where('status', 'completed')->sum('amount'),
            'spese_totali'      => Transaction::where('buyer_id', $user->id)->where('status', 'completed')->sum('amount'),
        ];

        // Ultime transazioni
        $ultimoVendite = Transaction::with(['card', 'buyer'])
            ->where('seller_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $ultimiAcquisti = Transaction::with(['card', 'seller'])
            ->where('buyer_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // Ultime carte
        $ultimeCarte = Card::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact('stats', 'ultimoVendite', 'ultimiAcquisti', 'ultimeCarte'));
    }
}