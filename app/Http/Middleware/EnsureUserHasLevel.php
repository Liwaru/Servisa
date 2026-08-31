<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasLevel
{
    public function handle(Request $request, Closure $next, int $level): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        if ((int) $request->user()->level !== $level) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
