<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ValidatorRegistrationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

/**
 * ValidatorRegisterController
 *
 * Gestisce la registrazione dei validatori TCG.
 * Il rate limiting è applicato a livello di route (throttle:validator-registration).
 * L'account viene creato in stato "non verificato" e richiede approvazione dell'admin.
 */
class ValidatorRegisterController extends Controller
{
    public function showForm()
    {
        return view('auth.validator-register');
    }

    public function register(ValidatorRegistrationRequest $request)
    {
        // Salva il documento d'identità in storage privato (non accessibile pubblicamente)
        // Il nome del file include un hash per prevenire enumeration attacks
        $documentPath = $request->file('identity_document')->store(
            'validator-documents',
            'private', // Disco 'private' configurato in config/filesystems.php
        );

        // Crea l'utente con ruolo validator ma NON verificato
        // L'admin dovrà approvare manualmente la candidatura
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'validator',
            'is_verified_validator' => false, // Richiede approvazione admin
            'identity_document_path' => $documentPath,
            'tcg_categories' => $request->tcg_categories,
            'validation_notes' => $request->expertise_notes,
            'vat_number' => $request->vat_number,
        ]);

        // TODO: Notifica l'admin via email di una nuova candidatura
        // Notification::send(User::admins()->get(), new NewValidatorApplication($user));

        // Effettua il login immediato (ma l'utente vede la pagina "pending")
        auth()->login($user);

        return redirect()->route('validator.pending')->with('success', 'Candidatura inviata! Riceverai una email quando la tua richiesta sarà esaminata.');
    }
}

// ──────────────────────────────────────────────────────────────────────────────
// REGISTRAZIONE MIDDLEWARE E RATE LIMITING
// Aggiungere questi snippet nei file indicati
// ──────────────────────────────────────────────────────────────────────────────

/*
 * FILE: app/Http/Kernel.php
 * Aggiungere nel array $middlewareAliases:
 *
 * 'validator' => \App\Http\Middleware\EnsureIsValidator::class,
 */

/*
 * FILE: app/Providers/RouteServiceProvider.php (o bootstrap/app.php per Laravel 11+)
 * Configurare il rate limiter personalizzato per la registrazione validatori:
 *
 * RateLimiter::for('validator-registration', function (Request $request) {
 *     return Limit::perMinutes(15, 5)  // Max 5 tentativi ogni 15 minuti per IP
 *                 ->by($request->ip())
 *                 ->response(function () {
 *                     return response()->json([
 *                         'message' => 'Troppi tentativi di registrazione. Riprova tra 15 minuti.'
 *                     ], 429);
 *                 });
 * });
 */

/*
 * FILE: app/Http/Middleware/VerifyCsrfToken.php
 * Aggiungere l'endpoint webhook Stripe all'eccezione CSRF:
 *
 * protected $except = [
 *     'stripe/webhook',  // Stripe non può inviare token CSRF, verifica la firma invece
 * ];
 */

/*
 * FILE: config/services.php
 * Aggiungere la configurazione Stripe:
 *
 * 'stripe' => [
 *     'key'            => env('STRIPE_KEY'),
 *     'secret'         => env('STRIPE_SECRET'),
 *     'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
 * ],
 */

/*
 * FILE: .env
 * Variabili d'ambiente richieste:
 *
 * STRIPE_KEY=pk_live_...
 * STRIPE_SECRET=sk_live_...
 * STRIPE_WEBHOOK_SECRET=whsec_...
 *
 * PLATFORM_FEE_PERCENTAGE=8
 */
