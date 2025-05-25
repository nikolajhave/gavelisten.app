<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Redirect the user to the provider authentication page.
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirect(string $provider): RedirectResponse
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle the provider callback and authenticate the user.
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback(string $provider): RedirectResponse
    {
        try {
            $socialiteUser = Socialite::driver($provider)->user();

            // Find existing user or create a new one
            $user = User::where('provider', $provider)
                ->where('provider_id', $socialiteUser->getId())
                ->first();

            if (!$user) {
                // Check if a user with this email already exists
                $user = User::where('email', $socialiteUser->getEmail())->first();

                if (!$user) {
                    // Create a new user
                    $user = User::create([
                        'name' => $socialiteUser->getName(),
                        'email' => $socialiteUser->getEmail(),
                        'password' => Hash::make(Str::random(16)), // Random password
                        'provider' => $provider,
                        'provider_id' => $socialiteUser->getId(),
                        'avatar' => $socialiteUser->getAvatar(),
                    ]);
                } else {
                    // Update existing user with provider info
                    $user->update([
                        'provider' => $provider,
                        'provider_id' => $socialiteUser->getId(),
                        'avatar' => $socialiteUser->getAvatar(),
                    ]);
                }
            }

            // Log in the user
            Auth::login($user);

            return redirect()->intended(route('dashboard'));

        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Authentication failed. Please try again.');
        }
    }
}
