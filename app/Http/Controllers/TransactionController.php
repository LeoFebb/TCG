<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $selling = Transaction::with(['card', 'buyer', 'validator', 'buyerValidator'])
            ->where('seller_id', Auth::id())
            ->latest()
            ->get();
        $buying = Transaction::with(['card', 'seller', 'validator', 'buyerValidator'])
            ->where('buyer_id', Auth::id())
            ->latest()
            ->get();
        return view('transactions.index', compact('selling', 'buying'));
    }

    public function markShipped(Request $request, Transaction $transaction)
    {
        if ($transaction->type === 'trade') {
            abort_if($transaction->seller_id !== Auth::id() && $transaction->buyer_id !== Auth::id(), 403);
            abort_if(!in_array($transaction->status, ['accepted', 'pending']), 422);

            if ($transaction->seller_id === Auth::id()) {
                $transaction->update([
                    'seller_shipped' => true,
                    'tracking_number' => $request->tracking_number,
                ]);
            } else {
                $transaction->update([
                    'buyer_shipped' => true,
                    'return_tracking_number' => $request->tracking_number,
                ]);
            }

            // Se entrambi hanno spedito passa a in_validation
            $transaction->refresh();
            if ($transaction->seller_shipped && $transaction->buyer_shipped) {
                $transaction->update(['status' => 'in_validation']);
            }
        } else {
            abort_if($transaction->seller_id !== Auth::id(), 403);
            abort_if($transaction->status !== 'paid_escrow', 422);

            $transaction->update([
                'status' => 'in_validation',
                'tracking_number' => $request->tracking_number,
                'seller_shipped' => true,
            ]);
        }

        return redirect()->back()->with('success', 'Spedizione confermata!');
    }

    public function markCompleted(Transaction $transaction)
    {
        abort_if($transaction->buyer_id !== Auth::id(), 403);
        abort_if($transaction->status !== 'shipping', 422);

        $transaction->update(['status' => 'completed']);
        $transaction->card->update(['status' => 'sold']);

        return redirect()->back()->with('success', 'Hai confermato la ricezione della carta!');
    }

    public function acceptTrade(Request $request, Transaction $transaction)
    {
        abort_if($transaction->seller_id !== Auth::id(), 403);
        abort_if($transaction->status !== 'pending', 422);
        abort_if($transaction->type !== 'trade', 422);

        $request->validate([
            'validator_id' => 'required|exists:users,id',
        ]);

        $transaction->update([
            'status' => 'accepted',
            'validator_id' => $request->validator_id,
        ]);

        return redirect()->back()->with('success', 'Permuta accettata! Ora spedisci la tua carta al tuo validatore.');
    }

    public function rejectTrade(Transaction $transaction)
    {
        abort_if($transaction->seller_id !== Auth::id(), 403);
        abort_if($transaction->status !== 'pending', 422);

        $transaction->update(['status' => 'rejected']);
        $transaction->card->update(['status' => 'available']);

        return redirect()->back()->with('success', 'Permuta rifiutata.');
    }
}
