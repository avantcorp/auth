<?php

namespace Avant\Auth;

class EnsureSessionIsValid
{
    public function handle($request, $next)
    {
        if (auth()->user() && !auth()->user()->avant_auth_token) {
            auth()->logout();

            return redirect('/');
        }

        return $next($request);
    }
}