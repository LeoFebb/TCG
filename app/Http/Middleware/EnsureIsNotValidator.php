<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsNotValidator
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->role === 'validator') {
            abort(403, 'Accesso non consentito ai validatori.');
        }
        return $next($request);
    }
}