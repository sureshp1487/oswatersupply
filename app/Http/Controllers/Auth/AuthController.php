<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CustomerProfile;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginType = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $user = User::where($loginType, $credentials['login'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'login' => ['The provided credentials do not match our records.'],
            ]);
        }

        if ($user->status === 'blocked') {
            throw ValidationException::withMessages([
                'login' => ['Your account has been suspended. Please contact support.'],
            ]);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return $this->redirectBasedOnRole($user);
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^[6-9]\d{9}$/', 'unique:users,phone'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            // Quick onboarding address fields
            'house_or_building' => ['required', 'string', 'max:255'],
            'door_number' => ['required', 'string', 'max:100'],
            'street' => ['required', 'string', 'max:255'],
            'area_locality' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'pincode' => ['required', 'string', 'regex:/^\d{6}$/'],
            'delivery_instructions' => ['nullable', 'string', 'max:500'],
            'preferred_slot_id' => ['nullable', 'exists:delivery_slots,id'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
            'status' => 'active',
        ]);

        // Create Profile
        CustomerProfile::create([
            'user_id' => $user->id,
            'preferred_delivery_slot_id' => $validated['preferred_slot_id'] ?? 1,
            'total_due_balance' => 0.00,
            'total_empty_cans_held' => 0,
            'onboarding_completed' => true,
        ]);

        // Create Default Address
        Address::create([
            'user_id' => $user->id,
            'label' => 'Home',
            'house_or_building' => $validated['house_or_building'],
            'door_number' => $validated['door_number'],
            'street' => $validated['street'],
            'area_locality' => $validated['area_locality'],
            'city' => $validated['city'],
            'pincode' => $validated['pincode'],
            'delivery_instructions' => $validated['delivery_instructions'] ?? null,
            'is_default' => true,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('customer.home')->with('success', 'Welcome to AquaFlow! Your account and primary delivery address are all set.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'You have been logged out.');
    }

    protected function redirectBasedOnRole(User $user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isDriver()) {
            return redirect()->route('driver.dashboard');
        }
        return redirect()->route('customer.home');
    }
}
