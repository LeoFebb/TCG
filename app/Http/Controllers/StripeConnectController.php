<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StripeConnectController extends Controller
{
    public function onboard()
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        $user = Auth::user();

        // Crea account Stripe Connect se non esiste
        if (!$user->stripe_connect_id) {
            $account = \Stripe\Account::create([
                'type' => 'express',
                'country' => 'IT',
                'email' => $user->email,
                'capabilities' => [
                    'transfers' => ['requested' => true],
                ],
            ]);
            $user->update(['stripe_connect_id' => $account->id]);
        }

        // Crea link onboarding
        $accountLink = \Stripe\AccountLink::create([
            'account' => $user->stripe_connect_id,
            'refresh_url' => route('stripe.onboard'),
            'return_url' => route('stripe.onboard.complete'),
            'type' => 'account_onboarding',
        ]);

        return redirect($accountLink->url);
    }

    public function onboardComplete()
{
    \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
    $user = Auth::user();
    \Log::info('onboardComplete', ['stripe_connect_id' => $user->stripe_connect_id]);
    
    if ($user->stripe_connect_id) {
        $result = $user->update(['stripe_onboarding_complete' => true]);
        \Log::info('update result', ['result' => $result, 'onboarding_complete' => $user->fresh()->stripe_onboarding_complete]);
        return redirect()->route('profile.show')->with('success', 'Account bancario collegato con successo!');
    }

    return redirect()->route('stripe.onboard')->with('error', 'Onboarding non completato. Riprova.');
}

    public function payout()
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        $user = Auth::user();

        if (!$user->stripe_connect_id || !$user->stripe_onboarding_complete) {
            return redirect()->route('stripe.onboard')->with('error', 'Devi prima collegare il tuo account bancario.');
        }

        try {
            // Verifica il saldo disponibile
            $balance = \Stripe\Balance::retrieve([], ['stripe_account' => $user->stripe_connect_id]);
            $available = $balance->available[0]->amount ?? 0;

            if ($available < 100) {
                return redirect()->route('profile.show')->with('error', 'Saldo insufficiente per il payout (minimo €1.00).');
            }

            // Crea il payout
            \Stripe\Payout::create(
                [
                    'amount' => $available,
                    'currency' => 'eur',
                ],
                ['stripe_account' => $user->stripe_connect_id],
            );

            return redirect()->route('profile.show')->with('success', 'Payout richiesto con successo! Arriverà sul tuo conto in 1-3 giorni lavorativi.');
        } catch (\Exception $e) {
            return redirect()
                ->route('profile.show')
                ->with('error', 'Errore payout: ' . $e->getMessage());
        }
    }

    public function balance()
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        $user = Auth::user();

        if (!$user->stripe_connect_id || !$user->stripe_onboarding_complete) {
            return response()->json(['available' => 0, 'pending' => 0]);
        }

        try {
            $balance = \Stripe\Balance::retrieve([], ['stripe_account' => $user->stripe_connect_id]);
            return response()->json([
                'available' => ($balance->available[0]->amount ?? 0) / 100,
                'pending' => ($balance->pending[0]->amount ?? 0) / 100,
            ]);
        } catch (\Exception $e) {
            return response()->json(['available' => 0, 'pending' => 0]);
        }
    }
}
