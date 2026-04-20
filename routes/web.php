<?php

use App\Http\Controllers\EscrowController;
use App\Http\Controllers\ValidatorController;
use App\Http\Controllers\Auth\ValidatorRegisterController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\CardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ProfileValidatorController;
use App\Http\Controllers\MyCardsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\ChatController;

Route::middleware(['auth'])->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{room}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{room}/send', [ChatController::class, 'send'])->name('chat.send');
});

Route::get('/shipping-debt', function() {
    return view('shipping.debt');
})->name('shipping.debt')->middleware('auth');

// Etichette spedizione
Route::get('/transaction/{transaction}/label', [ShippingController::class, 'generateLabel'])->name('shipping.label');
Route::get('/transaction/{transaction}/return-label', [ShippingController::class, 'generateReturnLabel'])->name('shipping.return-label');

// Homepage
Route::get('/', function () {
    return view('home');
})->name('home');

// Aggiungi qui 👇
Route::get('/privacy', function () {
    return view('legal.privacy');
})->name('privacy');

Route::get('/termini', function () {
    return view('legal.terms');
})->name('terms');

// Aggiungi qui 👇
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Marketplace
Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace.index');
Route::get('/card/{card}', [MarketplaceController::class, 'show'])->name('marketplace.show');
Route::get('/cerca', [MarketplaceController::class, 'search'])->name('marketplace.search');
Route::get('/categoria/{category}', [MarketplaceController::class, 'category'])->name('marketplace.category');
Route::get('/validatori', [ValidatorController::class, 'list'])->name('validators.list');

// Dashboard (redirect alla home)
Route::get('/dashboard', function () {
    return redirect('/');
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Profile (richiesto da Breeze)
Route::middleware('auth')->group(function () {
    // Profile (richiesto da Breeze)
    Route::get('/profile', fn() => redirect('/'))->name('profile.edit');
    Route::patch('/profile', fn() => redirect('/'))->name('breeze.profile.update');
    Route::delete('/profile', fn() => redirect('/'))->name('profile.destroy');
});

// Registrazione Validatori
Route::middleware(['throttle:60,1'])->group(function () {
    Route::get('/register/validator', [ValidatorRegisterController::class, 'showForm'])->name('validator.register.form');
    Route::post('/register/validator', [ValidatorRegisterController::class, 'register'])->name('validator.register');
});

// Pagina attesa approvazione
Route::get('/validator/pending', function () {
    return view('validator.pending');
})
    ->middleware('auth')
    ->name('validator.pending');

// Dashboard Validatore
Route::middleware(['auth', 'tcg_validator'])
    ->prefix('validator')
    ->name('validator.')
    ->group(function () {
        Route::get('/dashboard', [ValidatorController::class, 'dashboard'])->name('dashboard');
        Route::get('/transaction/{transaction}', [ValidatorController::class, 'show'])->name('transaction.show');
        Route::post('/transaction/{transaction}/approve', [ValidatorController::class, 'approve'])->name('transaction.approve');
        Route::post('/transaction/{transaction}/reject', [ValidatorController::class, 'reject'])->name('transaction.reject');
        Route::post('/transaction/{transaction}/confirm-received', [ValidatorController::class, 'confirmReceived'])->name('transaction.confirm-received');
        Route::post('/transaction/{transaction}/mark-shipped', [ValidatorController::class, 'markShipped'])->name('mark-shipped');
        Route::post('/transaction/{transaction}/received', [ValidatorController::class, 'markReceived'])->name('transaction.received');
        Route::post('/validator/transaction/{transaction}/received', [ValidatorController::class, 'markReceived'])->name('validator.transaction.received');
    });

// Carte
Route::middleware(['auth', 'check_shipping_debt'])->group(function () {
    Route::get('/cards/create', [CardController::class, 'create'])->name('cards.create');
    Route::post('/cards', [CardController::class, 'store'])->name('cards.store');
    Route::delete('/cards/{card}', [CardController::class, 'destroy'])->name('cards.destroy');
    Route::get('/le-mie-carte', [MyCardsController::class, 'index'])->name('cards.my');
});

// Profilo utente
Route::middleware('auth')->group(function () {
    Route::get('/profilo', [UserProfileController::class, 'show'])->name('profile.show');
    Route::post('/profilo', [UserProfileController::class, 'update'])->name('user.profile.update');
});

// Transazioni e spedizioni
Route::middleware('auth')->group(function () {
    Route::get('/transazioni', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transaction/{transaction}/label', [ShippingController::class, 'generateLabel'])->name('shipping.label');
    Route::get('/transaction/{transaction}/return-label', [ShippingController::class, 'generateReturnLabel'])->name('shipping.return-label');
    Route::post('/transaction/{transaction}/mark-shipped', [TransactionController::class, 'markShipped'])->name('transaction.mark-shipped');
    Route::post('/transaction/{transaction}/completed', [TransactionController::class, 'markCompleted'])->name('transaction.mark-completed');
    Route::post('/transaction/{transaction}/choose-validator', [EscrowController::class, 'chooseValidator'])->name('transaction.choose-validator');
    Route::post('/transaction/{transaction}/accept-trade', [TransactionController::class, 'acceptTrade'])->name('transaction.accept-trade');
    Route::post('/transaction/{transaction}/reject-trade', [TransactionController::class, 'rejectTrade'])->name('transaction.reject-trade');
    Route::get('/shipping/{transaction}/buyer-trade-label', [ShippingController::class, 'generateBuyerTradeLabel'])->name('shipping.buyer-trade-label');
    Route::get('/shipping/{transaction}/seller-trade-label', [ShippingController::class, 'generateSellerTradeLabel'])->name('shipping.seller-trade-label');
});

// Acquisto / Permuta
// Permute (pubblica)
Route::get('/permute', [MarketplaceController::class, 'trades'])->name('marketplace.trades');
Route::get('/dashboard-utente', [DashboardController::class, 'index'])
    ->name('user.dashboard')
    ->middleware(['auth', 'not_validator']);

// Acquisto / Permuta (richiede auth)
Route::middleware(['auth', 'check_shipping_debt'])->group(function () {
    Route::match(['get', 'post'], '/checkout/{card}', [EscrowController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/{transaction}/update-shipping', [EscrowController::class, 'updateShipping'])->name('checkout.update-shipping');
    Route::get('/checkout/{transaction}/success', [EscrowController::class, 'checkoutSuccess'])->name('checkout.success');
    Route::post('/trade/offer', [MarketplaceController::class, 'createTradeOffer'])->name('trade.offer');
    Route::post('/trade/{transaction}/confirm', [MarketplaceController::class, 'confirmTrade'])->name('trade.confirm');
    Route::post('/transaction/{transaction}/shipped', [MarketplaceController::class, 'markShipped'])->name('transaction.shipped');
    Route::get('/validator/profile', [ProfileValidatorController::class, 'edit'])->name('validator.profile.edit');
    Route::post('/validator/profile', [ProfileValidatorController::class, 'update'])->name('validator.profile.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/shipping-debt/pay', [EscrowController::class, 'payShippingDebt'])->name('shipping.debt.pay');
    Route::post('/shipping-debt/pay', [EscrowController::class, 'processShippingDebtPayment'])->name('shipping.debt.pay.process');
    Route::get('/shipping-debt/success', [EscrowController::class, 'shippingDebtSuccess'])->name('shipping.debt.success');
});

// Webhook Stripe
Route::post('/stripe/webhook', [EscrowController::class, 'handleWebhook'])->name('stripe.webhook');

// Auth routes Breeze
require __DIR__ . '/auth.php';

Route::post('/catalog/add-card', function (\Illuminate\Http\Request $request) {
    if ($request->filled('card_name')) {
        \App\Models\TcgCardCatalog::firstOrCreate(
            [
                'name' => $request->card_name,
                'tcg_category' => $request->category ?? 'unknown',
            ],
            [
                'set_name' => $request->set_name ?? 'Personalizzata',
                'card_number' => null,
                'rarity' => null,
            ],
        );
    }
    return response()->json(['success' => true]);
})
    ->name('catalog.add-card')
    ->middleware('auth');
