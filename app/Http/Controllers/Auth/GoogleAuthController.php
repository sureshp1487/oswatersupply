<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\CustomerProfile;
use App\Models\Address;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to the Google OAuth consent screen.
     */
    public function redirect(Request $request)
    {
        $clientId = config('services.google.client_id');
        $redirectUri = config('services.google.redirect');

        if (empty($clientId) || empty(config('services.google.client_secret'))) {
            return redirect()->route('login')->withErrors([
                'general' => 'Google Sign-In is not configured yet. Please check your credentials.'
            ]);
        }

        $state = Str::random(40);
        $request->session()->put('google_oauth_state', $state);

        $queryParams = http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => 'openid profile email',
            'state' => $state,
            'access_type' => 'online',
            'prompt' => 'select_account',
        ]);

        return redirect('https://accounts.google.com/o/oauth2/v2/auth?' . $queryParams);
    }

    /**
     * Handle the callback returned from Google.
     */
    public function callback(Request $request)
    {
        if ($request->has('error')) {
            Log::warning('Google sign-in canceled or returned error: ' . $request->get('error'));
            return redirect()->route('login')->withErrors([
                'general' => 'Google sign-in was canceled or failed: ' . $request->get('error')
            ]);
        }

        $code = $request->get('code');
        if (empty($code)) {
            return redirect()->route('login')->withErrors([
                'general' => 'Google authorization code not found. Please try again.'
            ]);
        }

        try {
            $clientId = config('services.google.client_id');
            $clientSecret = config('services.google.client_secret');
            $redirectUri = config('services.google.redirect');

            // 1. Exchange authorization code for access token
            $tokenResponse = Http::asForm()->timeout(15)->post('https://oauth2.googleapis.com/token', [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'redirect_uri' => $redirectUri,
                'grant_type' => 'authorization_code',
                'code' => $code,
            ]);

            if (!$tokenResponse->successful()) {
                Log::error('Google OAuth token exchange failed', [
                    'status' => $tokenResponse->status(),
                    'body' => $tokenResponse->body()
                ]);

                return redirect()->route('login')->withErrors([
                    'general' => 'Unable to verify Google credentials. Please ensure authorized redirect URI matches in Google Cloud Console.'
                ]);
            }

            $tokenData = $tokenResponse->json();
            $accessToken = $tokenData['access_token'] ?? null;

            if (empty($accessToken)) {
                return redirect()->route('login')->withErrors([
                    'general' => 'Google access token missing. Please try again.'
                ]);
            }

            // 2. Fetch authenticated user profile details from Google
            $userInfoResponse = Http::withToken($accessToken)->timeout(10)->get('https://www.googleapis.com/oauth2/v3/userinfo');

            if (!$userInfoResponse->successful()) {
                Log::error('Google OAuth userinfo request failed', [
                    'status' => $userInfoResponse->status(),
                    'body' => $userInfoResponse->body()
                ]);

                return redirect()->route('login')->withErrors([
                    'general' => 'Unable to retrieve user details from Google.'
                ]);
            }

            $googleUser = $userInfoResponse->json();
            $googleId = $googleUser['sub'] ?? null;
            $email = isset($googleUser['email']) ? strtolower(trim($googleUser['email'])) : null;
            $name = $googleUser['name'] ?? ($email ? explode('@', $email)[0] : 'Valued Customer');

            if (empty($email)) {
                return redirect()->route('login')->withErrors([
                    'general' => 'Email address not provided by your Google account.'
                ]);
            }

            // 3. Find or create the user in our database
            $user = User::where('google_id', $googleId)
                ->orWhere('email', $email)
                ->first();

            if ($user) {
                // Link Google ID if not yet linked
                if (empty($user->google_id) && $googleId) {
                    $user->google_id = $googleId;
                    $user->save();
                }

                if ($user->status === 'blocked') {
                    return redirect()->route('login')->withErrors([
                        'general' => 'Your account has been deactivated. Please contact customer support.'
                    ]);
                }
            } else {
                // Create new Customer
                [$user, $address] = DB::transaction(function () use ($name, $email, $googleId) {
                    $newUser = User::create([
                        'name' => $name,
                        'email' => $email,
                        'google_id' => $googleId,
                        'role' => 'customer',
                        'status' => 'active',
                        'email_verified_at' => now(),
                    ]);

                    CustomerProfile::create([
                        'user_id' => $newUser->id,
                        'preferred_delivery_slot_id' => 1,
                        'total_due_balance' => 0.00,
                        'total_empty_cans_held' => 0,
                        'onboarding_completed' => true,
                    ]);

                    // Default location pinned address
                    $newAddress = Address::create([
                        'user_id' => $newUser->id,
                        'label' => 'Home',
                        'house_or_building' => 'My Pinned Location',
                        'door_number' => 'Main Entrance',
                        'street' => 'Near Pinned Location',
                        'area_locality' => 'Chennai Area',
                        'city' => 'Chennai',
                        'pincode' => '600001',
                        'latitude' => 13.0827,
                        'longitude' => 80.2707,
                        'is_default' => true,
                    ]);

                    return [$newUser, $newAddress];
                });
            }

            // 4. Authenticate & redirect
            Auth::login($user, true);
            $request->session()->regenerate();

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'driver') {
                return redirect()->route('driver.dashboard');
            }

            // Flash session flag so home page automatically prompts for GPS location
            $request->session()->flash('ask_gps_location', true);

            return redirect()->route('customer.home')->with('success', 'Successfully signed in with Google! Welcome to AquaFlow.');
        } catch (\Throwable $e) {
            Log::error('Exception in Google OAuth callback: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return redirect()->route('login')->withErrors([
                'general' => 'An error occurred during Google Sign-In. Please try again.'
            ]);
        }
    }
}
