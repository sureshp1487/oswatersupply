<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordNotification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    /**
     * Display the form to request a password reset link.
     */
    public function showForgotPassword()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.forgot-password');
    }

    /**
     * Send a password reset link to the given user.
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'login' => ['required', 'string'],
        ], [
            'login.required' => 'Please enter your registered email address or mobile number.',
        ]);

        $loginInput = trim($request->input('login'));
        $isEmail = filter_var($loginInput, FILTER_VALIDATE_EMAIL);

        $user = $isEmail
            ? User::where('email', $loginInput)->first()
            : User::where('phone', $loginInput)->first();

        if (!$user) {
            return back()->withErrors([
                'login' => 'We could not find an account matching that ' . ($isEmail ? 'email address' : 'mobile number') . '.',
            ])->withInput();
        }

        if (empty($user->email)) {
            return back()->withErrors([
                'login' => 'Your account does not have a linked email address to receive password reset instructions. Please contact customer support.',
            ])->withInput();
        }

        // Generate a cryptographically secure reset token
        $token = Str::random(64);

        // Store hashed token in password_reset_tokens table
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make($token),
                'created_at' => Carbon::now(),
            ]
        );

        // Generate reset password URL
        $resetUrl = route('password.reset', [
            'token' => $token,
            'email' => $user->email,
        ]);

        // Send email notification
        try {
            Mail::to($user->email)->send(new ResetPasswordNotification($user, $token, $resetUrl));
        } catch (\Exception $e) {
            Log::error('Failed to send password reset email: ' . $e->getMessage(), [
                'email' => $user->email,
            ]);

            return back()->withErrors([
                'login' => 'Unable to send password reset email at this moment. Please check mail settings or try again later.',
            ])->withInput();
        }

        return back()->with('status', 'A password reset link has been dispatched to ' . $user->email . '. Please check your inbox or spam folder.');
    }

    /**
     * Display the password reset view for the given token.
     */
    public function showResetForm(Request $request, $token = null)
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        $token = $token ?? $request->route('token') ?? $request->get('token');
        $email = $request->get('email');

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    /**
     * Reset the user's password.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'token.required' => 'The password reset token is required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'password.required' => 'Please enter your new password.',
            'password.min' => 'Password must be at least 6 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record) {
            return back()->withErrors([
                'email' => 'This password reset token is invalid or has already been used.',
            ])->withInput($request->only('email'));
        }

        // Verify expiration (default 60 minutes)
        $expireMinutes = (int) config('auth.passwords.users.expire', 60);
        if (Carbon::parse($record->created_at)->addMinutes($expireMinutes)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return back()->withErrors([
                'email' => 'This password reset link has expired. Please request a new one.',
            ])->withInput($request->only('email'));
        }

        // Verify token hash
        if (!Hash::check($request->token, $record->token)) {
            return back()->withErrors([
                'email' => 'This password reset token is invalid or corrupted.',
            ])->withInput($request->only('email'));
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'No active user found with this email address.',
            ])->withInput($request->only('email'));
        }

        // Update password (User model cast 'password' => 'hashed' auto-hashes it)
        $user->password = $request->password;
        $user->setRememberToken(Str::random(60));
        $user->save();

        // Delete used token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('status', 'Your password has been successfully reset! You can now log in with your new password.');
    }

    /**
     * Redirect authenticated user based on role.
     */
    protected function redirectBasedOnRole($user)
    {
        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'driver' => redirect()->route('driver.dashboard'),
            default => redirect()->route('customer.home'),
        };
    }
}
