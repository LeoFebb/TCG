<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckShippingDebt
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->has_shipping_debt) {
            return redirect()->route('shipping.debt')->with('error', 
                'Hai un debito di spedizione di €' . number_format(auth()->user()->shipping_debt_amount, 2) . 
                '. Devi pagarlo prima di poter operare sul sito.');
        }

        return $next($request);
    }
}