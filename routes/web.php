<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

Route::middleware(['web'])
    ->group(function () {
        Route::redirect('/login', '/auth/redirect')->name('login');

        Route::get('/auth/redirect', function () {
            return Socialite::driver('avant-auth')
                ->scopes([config('services.avant-auth.client_id')])
                ->redirect();
        });

        Route::get('/auth/callback', function (Request $request) {
            throw_if(
                $request->get('error') === 'invalid_scope',
                new AccessDeniedHttpException($request->get('message', ''))
            );

            $avantUser = Socialite::driver('avant-auth')->user();

            throw_unless(
                $avantUser->user['authorized'],
                new AccessDeniedHttpException('You are unauthorized to access this application')
            );

            $user = config('auth.providers.users.model')::query()
                ->updateOrCreate([
                    'avant_auth_id' => $avantUser->id,
                ], [
                    'name'                     => $avantUser->name,
                    'email'                    => $avantUser->email,
                    'avant_auth_token'         => $avantUser->token,
                    'avant_auth_refresh_token' => $avantUser->refreshToken,
                ]);

            Auth::login($user);

            return redirect(config('services.avant-auth.redirect_authenticated'));
        });
    });

Route::post('/auth/logout', function (Request $request) {
    config('auth.providers.users.model')::query()
        ->where('avant_auth_id', $request->collect('logout'))
        ->update([
            'avant_auth_token'         => null,
            'avant_auth_refresh_token' => null,
        ]);
});
