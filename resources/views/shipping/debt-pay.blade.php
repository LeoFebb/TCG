@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto px-4 py-16">
    <div class="rounded-2xl border border-purple-900/50 p-8" style="background: rgba(45,17,84,0.2);">
        <h1 class="text-2xl font-black text-white mb-2">&#128176; Paga debito spedizione</h1>
        <p class="text-gray-400 text-sm mb-6">
            Importo da pagare: 
            <span class="text-white font-bold text-xl">€{{ number_format($amount, 2) }}</span>
        </p>

        <div id="payment-element" class="mb-6"></div>
        <div id="card-errors" class="text-red-400 text-sm mb-4 hidden"></div>

        <button id="submit-btn"
            class="w-full py-4 rounded-xl font-black text-black text-base"
            style="background: linear-gradient(135deg, #c9a84c, #e8c97e);">
            &#128274; Paga €{{ number_format($amount, 2) }}
        </button>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
const stripe = Stripe('{{ $stripePublicKey }}');
const elements = stripe.elements({
    clientSecret: '{{ $clientSecret }}',
    appearance: { theme: 'night', variables: { colorPrimary: '#a855f7' } }
});

const paymentElement = elements.create('payment');
paymentElement.mount('#payment-element');

document.getElementById('submit-btn').addEventListener('click', async () => {
    const { error } = await stripe.confirmPayment({
        elements,
        confirmParams: {
            return_url: '{{ route('shipping.debt.success') }}',
        },
    });
    if (error) {
        document.getElementById('card-errors').textContent = error.message;
        document.getElementById('card-errors').classList.remove('hidden');
    }
});
</script>
@endsection