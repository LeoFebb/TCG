<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role === 'admin') {
            if (!str_starts_with($request->path(), 'admin') && $request->path() !== 'logout') {
                return redirect()->route('admin.cards');
            }
        }
        return $next($request);
    }
}