<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $selling = Transaction::with(['card', 'buyer'])
            ->where('seller_id', Auth::id())
            ->latest()
            ->get();

        $buying = Transaction::with(['card', 'seller'])
            ->where('buyer_id', Auth::id())
            ->latest()
            ->get();

        return view('transactions.index', compact('selling', 'buying'));
    }

    public function markShipped(Request $request, Transaction $transaction)
    {
        // Solo il venditore può confermare la spedizione
        abort_if($transaction->seller_id !== Auth::id(), 403);
        abort_if($transaction->status !== 'paid_escrow', 422);

        $transaction->update([
            'status' => 'in_validation',
            'tracking_number' => $request->tracking_number,
            'seller_shipped' => true,
        ]);

        return redirect()->back()->with('success', 'Spedizione confermata! La carta è ora in attesa di validazione.');
    }

    public function markCompleted(Transaction $transaction)
    {
        abort_if($transaction->buyer_id !== Auth::id(), 403);
        abort_if($transaction->status !== 'shipping', 422);

        $transaction->update(['status' => 'completed']);
        $transaction->card->update(['status' => 'sold']);

        return redirect()->back()->with('success', 'Hai confermato la ricezione della carta!');
    }
}
