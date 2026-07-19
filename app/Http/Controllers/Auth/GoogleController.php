<?php

// [AGNER] — Google OAuth login
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleController extends Controller
{
    public function redirect(): RedirectResponse
    {
        if (! $this->hasGoogleCredentials()) {
            return redirect()->route('login')
                ->with('error', 'Google login is not configured.');
        }

        if (! $this->redirectUriIsAllowed()) {
            return redirect()->away($this->loopbackBaseUrl().'/auth/google');
        }

        $this->useCurrentHostRedirect();

        return Socialite::driver('google')->redirect();
    }

    public function callback(): RedirectResponse
    {
        if (! $this->hasGoogleCredentials()) {
            return redirect()->route('login')
                ->with('error', 'Google login is not configured.');
        }

        $this->useCurrentHostRedirect();

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable) {
            return redirect()->route('login')
                ->with('error', 'Google sign-in failed. Please try again.');
        }

        $customer = Customer::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'first_name' => $googleUser->getName() ?: 'Google',
                'last_name' => '',
                'password' => Hash::make(str()->random(24)),
                'status' => 'Active',
                'email_verified_at' => now(),
                'profile_picture' => $googleUser->getAvatar(),
                'last_login' => now(),
            ]
        );

        $customer->update([
            'first_name' => $googleUser->getName() ?: $customer->first_name,
            'profile_picture' => $googleUser->getAvatar(),
            'email_verified_at' => $customer->email_verified_at ?? now(),
            'last_login' => now(),
            'status' => $customer->status === 'Inactive' ? 'Active' : $customer->status,
        ]);

        Auth::login($customer, true);
        session(['auth_via' => 'google']);

        return redirect()->route('home');
    }

    private function useCurrentHostRedirect(): void
    {
        config(['services.google.redirect' => request()->getSchemeAndHttpHost().'/auth/google/callback']);
    }

    private function redirectUriIsAllowed(): bool
    {
        $host = request()->getHost();

        return $host === 'localhost'
            || $host === '127.0.0.1'
            || str_ends_with($host, '.localhost');
    }

    private function loopbackBaseUrl(): string
    {
        $configured = config('services.google.redirect');

        if ($configured) {
            $parts = parse_url($configured);

            return $parts['scheme'].'://'.$parts['host']
                .(isset($parts['port']) ? ':'.$parts['port'] : '');
        }

        return 'http://127.0.0.1:8000';
    }

    private function hasGoogleCredentials(): bool
    {
        return ! empty(config('services.google.client_id'))
            && ! empty(config('services.google.client_secret'));
    }
}
