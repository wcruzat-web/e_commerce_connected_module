<?php
// AGNER — Google OAuth login via Socialite (ERPV0.2 / hardened ERPV3.3.4.2)

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

        $this->syncRedirectUri();

        return Socialite::driver('google')->redirect();
    }

    public function callback(): RedirectResponse
    {
        if (! $this->hasGoogleCredentials()) {
            return redirect()->route('login')
                ->with('error', 'Google login is not configured.');
        }

        $this->syncRedirectUri();

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable) {
            return redirect()->route('login')
                ->with('error', 'Google sign-in failed. Please try again.');
        }

        $email = $googleUser->getEmail();

        // A usable account requires a present, verified email. Phone-only or
        // unverified Google accounts are rejected instead of creating a broken
        // null-email record (which would collide every such login onto one row).
        // `email_verified` is only available on the raw response, not the mapped User.
        $emailVerified = ($googleUser->getRaw()['email_verified'] ?? false) === true;

        if (empty($email) || ! $emailVerified) {
            return redirect()->route('login')
                ->with('error', 'Your Google account email is missing or unverified. Please use a different account.');
        }

        $customer = Customer::updateOrCreate(
            ['email' => $email],
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

        // On subsequent logins only refresh volatile identity fields. We deliberately
        // do NOT overwrite first_name/last_name, so a name the user edited in our app
        // is preserved across Google sign-ins.
        if (! $customer->wasRecentlyCreated) {
            $customer->update([
                'profile_picture' => $googleUser->getAvatar(),
                'email_verified_at' => $customer->email_verified_at ?? now(),
                'last_login' => now(),
                'status' => $customer->status === 'Inactive' ? 'Active' : $customer->status,
            ]);
        }

        Auth::login($customer, true);
        session(['auth_via' => 'google']);

        return redirect()->route('home');
    }

    /**
     * Resolve the OAuth redirect URI used for both the redirect and the callback.
     *
     * Prefers an explicit GOOGLE_REDIRECT_URI (recommended for production). When none
     * is configured, it falls back to the current host so local dev works without
     * extra setup. This replaces the previous loopback bounce, which broke login on
     * any non-localhost host.
     */
    private function syncRedirectUri(): void
    {
        $configured = config('services.google.redirect');
        $default = config('app.url').'/auth/google/callback';

        // An explicitly set GOOGLE_REDIRECT_URI (different from the app.url default)
        // is trusted as-is. Note: an empty env var resolves to "" (not null), so the
        // services.php fallback never kicks in — `filled()` catches that and lets us
        // fall back to the current host for local dev.
        if (filled($configured) && $configured !== $default) {
            return;
        }

        config(['services.google.redirect' => request()->getSchemeAndHttpHost().'/auth/google/callback']);
    }

    private function hasGoogleCredentials(): bool
    {
        return ! empty(config('services.google.client_id'))
            && ! empty(config('services.google.client_secret'));
    }
}
