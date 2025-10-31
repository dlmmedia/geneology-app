<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

final class AutoLoginDeveloper
{
    /**
     * Handle an incoming request.
     *
     * Automatically logs in as developer user if not authenticated.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only auto-login if not already authenticated
        if (!Auth::check()) {
            $developer = User::where('email', 'developer@genealogy.test')
                ->where('is_developer', true)
                ->first();

            if ($developer) {
                Auth::login($developer, true); // true = remember me
            }
        }

        return $next($request);
    }
}

