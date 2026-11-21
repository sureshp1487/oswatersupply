<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CustomerProfile;
use App\Models\Address;
use App\Mail\CustomerWelcomeNotification;
use App\Mail\AdminCustomerRegisteredNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
        ], [
            'login.required' => 'Email ID or mobile number is required.',
            'password.required' => 'Password is required.',
        ]);

        $loginVal = trim($credentials['login']);
        $loginType = filter_var($loginVal, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $user = User::where($loginType, $loginVal)->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'login' => ['Invalid email address or password.'],
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

    public function checkAccountAvailability(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string', 'regex:/^[6-9]\d{9}$/'],
            'email' => ['required', 'email:rfc,filter', 'max:255'],
        ], [
            'phone.required' => 'Mobile number is required.',
            'phone.regex' => 'Please enter a valid 10-digit mobile number starting with 6-9.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
        ]);

        $phone = trim($request->phone);
        $email = strtolower(trim($request->email));

        $phoneExists = User::where('phone', $phone)->exists();
        $emailExists = User::where('email', $email)->exists();

        if ($phoneExists || $emailExists) {
            $errors = [];
            if ($phoneExists) {
                $errors['phone'] = ['This mobile number is already registered. Please sign in or use another number.'];
            }
            if ($emailExists) {
                $errors['email'] = ['This email address is already in use.'];
            }
            return response()->json([
                'available' => false,
                'errors' => $errors
            ], 422);
        }

        return response()->json([
            'available' => true,
            'message' => 'Credentials available.'
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^[6-9]\d{9}$/', 'unique:users,phone'],
            'email' => ['required', 'email:rfc,filter', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            // Flexible map-pinpoint location onboarding fields
            'door_number' => ['nullable', 'string', 'max:100'],
            'house_or_building' => ['nullable', 'string', 'max:255'],
            'street' => ['nullable', 'string', 'max:255'],
            'area_locality' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'pincode' => ['nullable', 'string', 'max:20'],
            'delivery_instructions' => ['nullable', 'string', 'max:500'],
            'preferred_slot_id' => ['nullable', 'exists:delivery_slots,id'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ], [
            'name.required' => 'Customer full name is required.',
            'phone.required' => 'Mobile number is required.',
            'phone.regex' => 'Please enter a valid 10-digit mobile number starting with 6-9.',
            'phone.unique' => 'This mobile number is already registered. Please sign in or use another number.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already in use.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        try {
            [$user, $address] = DB::transaction(function () use ($validated) {
                $user = User::create([
                    'name' => trim($validated['name']),
                    'phone' => trim($validated['phone']),
                    'email' => strtolower(trim($validated['email'])),
                    'password' => $validated['password'], // User model casts password => hashed
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

                $door = !empty($validated['door_number']) ? trim($validated['door_number']) : null;
                $building = !empty($validated['house_or_building']) ? trim($validated['house_or_building']) : ($door ? 'Door ' . $door : 'My Pinned Location');
                $street = !empty($validated['street']) ? trim($validated['street']) : 'Near Pinned Location';
                $area = !empty($validated['area_locality']) ? trim($validated['area_locality']) : 'Chennai Area';
                $city = !empty($validated['city']) ? trim($validated['city']) : 'Chennai';
                $pincode = !empty($validated['pincode']) ? trim($validated['pincode']) : '600001';

                // Create Default Address from Map Pinpoint
                $address = Address::create([
                    'user_id' => $user->id,
                    'label' => 'Home',
                    'house_or_building' => $building,
                    'door_number' => $door ?: ($building ?: 'Main Entrance'),
                    'street' => $street,
                    'area_locality' => $area,
                    'city' => $city,
                    'pincode' => $pincode,
                    'delivery_instructions' => $validated['delivery_instructions'] ?? null,
                    'latitude' => $validated['latitude'] ?? null,
                    'longitude' => $validated['longitude'] ?? null,
                    'is_default' => true,
                ]);

                return [$user, $address];
            });

            // Dispatch Customer Welcome and Admin Notification emails (non-blocking)
            $this->sendRegistrationEmails($user, $address);

            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->route('customer.home')->with('success', 'Welcome to AquaFlow! Your account and primary delivery address are all set.');
        } catch (\Throwable $e) {
            Log::error('Customer registration transaction failed: ' . $e->getMessage(), [
                'exception' => $e,
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'] ?? null,
            ]);

            return back()->withInput()->withErrors([
                'general' => 'An unexpected error occurred while setting up your account. Please try again.',
            ]);
        }
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

    /**
     * Send Customer Welcome email and Admin + BCC Notification email.
     * Non-blocking: failures are logged and will not interrupt registration.
     */
    protected function sendRegistrationEmails(User $user, ?Address $address = null): void
    {
        // 1. Send Customer Welcome Email
        try {
            if (!empty($user->email)) {
                Mail::to($user->email)->send(new CustomerWelcomeNotification($user));
                Log::info("Customer welcome email successfully dispatched to {$user->email} for Customer ID #{$user->id}");
            }
        } catch (\Throwable $e) {
            Log::error("Failed to send customer welcome email to {$user->email}: " . $e->getMessage(), [
                'user_id' => $user->id,
                'email' => $user->email,
                'exception' => $e,
            ]);
        }

        // 2. Send Admin + BCC Customer Registration Notification
        try {
            $adminEmail = env('ADMIN_EMAIL') ?: env('MAIL_ADMIN') ?: env('ADMIN_NOTIFICATION_EMAIL') ?: config('mail.admin_notification_email');

            // Parse BCC emails dynamically from configuration
            $rawBccSources = array_filter([env('ADMIN_BCC_EMAILS'), env('MAIL_BCC')]);
            $bccEmails = [];
            foreach ($rawBccSources as $rawSource) {
                $parts = explode(',', (string) $rawSource);
                foreach ($parts as $part) {
                    $cleaned = trim($part);
                    if (filter_var($cleaned, FILTER_VALIDATE_EMAIL)) {
                        $bccEmails[] = $cleaned;
                    }
                }
            }
            $bccEmails = array_values(array_unique($bccEmails));

            if (!empty($adminEmail) && filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
                $mail = Mail::to($adminEmail);
                if (!empty($bccEmails)) {
                    $mail->bcc($bccEmails);
                }
                $mail->send(new AdminCustomerRegisteredNotification($user, $address));
                Log::info("Admin registration notification email successfully dispatched to {$adminEmail} (BCC count: " . count($bccEmails) . ") for Customer ID #{$user->id}");
            }
        } catch (\Throwable $e) {
            Log::error("Failed to send admin customer registration notification: " . $e->getMessage(), [
                'user_id' => $user->id,
                'exception' => $e,
            ]);
        }
    }
}
