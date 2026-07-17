<?php

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
    /**
     * Redirect to Google for OAuth.
     * The callback URI is forced to the current host so Google login works
     * on any domain (127.0.0.1, the Laragon .test vhost, or production)
     * without a redirect_uri mismatch.
     */
    public function redirect(): RedirectResponse
    {
        if (! $this->hasGoogleCredentials()) {
            return redirect()->route('login')
                ->with('error', 'Google login is not configured.');
        }

        // Google rejects any redirect URI whose TLD is not on the public suffix
        // list — this includes reserved dev TLDs like `.test` / `.local`, even
        // over https. The ONLY hosts Google accepts for local dev are loopback
        // (localhost / 127.0.0.1). So if we're not already on a loopback host,
        // bounce the whole Google flow to the configured loopback address
        // instead of sending Google an invalid `.test` URI (which throws the
        // "doesn't comply with Google's OAuth 2.0 policy" error).
        if (! $this->redirectUriIsAllowed()) {
            return redirect()->away($this->loopbackBaseUrl().'/auth/google');
        }

        $this->useCurrentHostRedirect();

        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the Google OAuth callback and log the user in.
     *
     * The same Google email always maps to the same customer, and we keep
     * the profile in sync with Google on every login. So the profile shows
     * consistent, real details — there is no placeholder/"dummy" account and
     * the information can never silently change between logins.
     */
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

        // The same Google email always maps to the same customer.
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

        // Keep the profile in sync with the latest Google details.
        $customer->update([
            'first_name' => $googleUser->getName() ?: $customer->first_name,
            'profile_picture' => $googleUser->getAvatar(),
            'email_verified_at' => $customer->email_verified_at ?? now(),
            'last_login' => now(),
            'status' => $customer->status === 'Inactive' ? 'Active' : $customer->status,
        ]);

        // `true` = persistent "remember me" cookie so the session survives
        // closing the browser or shutting down the device.
        Auth::login($customer, true);
        session(['auth_via' => 'google']);

        return redirect()->route('home');
    }

    /**
     * Force Socialite's redirect URI to the host the request came in on,
     * preserving the scheme (http on loopback, https elsewhere) so it always
     * matches what is registered in the Google Cloud Console. On loopback hosts
     * Google allows plain http, so we keep the request's scheme as-is.
     */
    private function useCurrentHostRedirect(): void
    {
        $scheme = request()->getScheme();   // 'http' on loopback
        $httpHost = request()->getHttpHost(); // host:port, e.g. 127.0.0.1:8000

        config(['services.google.redirect' => $scheme.'://'.$httpHost.'/auth/google/callback']);
    }

    /**
     * Google only accepts redirect URIs on loopback hosts
     * (localhost / 127.0.0.1). Any other host — including reserved dev TLDs
     * like `.test` / `.local`, even over https — is rejected.
     */
    private function redirectUriIsAllowed(): bool
    {
        $host = request()->getHost();

        return $host === 'localhost'
            || $host === '127.0.0.1'
            || str_ends_with($host, '.localhost');
    }

    /**
     * The loopback base URL to bounce the Google flow to when the current host
     * is not loopback. Derived from the configured GOOGLE_REDIRECT_URI so it
     * always matches what is registered in the Google Cloud Console.
     */
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

    /**
     * True when both Google OAuth credentials are present in .env
     * (read via config/services.php).
     */
    private function hasGoogleCredentials(): bool
    {
        return ! empty(config('services.google.client_id'))
            && ! empty(config('services.google.client_secret'));
    }
}
