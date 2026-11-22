<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Reset Your Password - {{ $companyName }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1e293b; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;">
    <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #f1f5f9; padding: 32px 12px;">
        <tr>
            <td align="center">
                <!-- Main Container -->
                <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 600px; background-color: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04); border: 1px solid #e2e8f0;">
                    
                    <!-- Header Banner -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); padding: 36px 32px; text-align: center;">
                            <div style="display: inline-block; background-color: rgba(255, 255, 255, 0.2); border: 1px solid rgba(255, 255, 255, 0.35); border-radius: 50%; width: 56px; height: 56px; line-height: 56px; font-size: 26px; margin-bottom: 12px; text-align: center;">
                                🔐
                            </div>
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 800; letter-spacing: -0.5px; line-height: 1.2;">
                                Password Reset Request
                            </h1>
                            <p style="margin: 8px 0 0 0; color: #e0f2fe; font-size: 14px; font-weight: 500;">
                                {{ $companyName }} &bull; Secure Account Access
                            </p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 36px 32px 24px 32px;">
                            <!-- Greeting -->
                            <h2 style="margin: 0 0 14px 0; color: #0f172a; font-size: 19px; font-weight: 700;">
                                Hello {{ $user->name }},
                            </h2>
                            <p style="margin: 0 0 16px 0; color: #475569; font-size: 15px; line-height: 1.6;">
                                We received a request to reset the password for your <strong>{{ $companyName }}</strong> account ({{ $user->email }}).
                            </p>
                            <p style="margin: 0 0 24px 0; color: #475569; font-size: 15px; line-height: 1.6;">
                                Click the button below to choose a new password. This reset link is valid for <strong>{{ $expireMinutes }} minutes</strong>.
                            </p>

                            <!-- CTA Button -->
                            <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="margin: 28px 0 32px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $resetUrl }}" target="_blank" style="display: inline-block; background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); color: #ffffff; text-decoration: none; padding: 14px 36px; border-radius: 12px; font-size: 15px; font-weight: 700; letter-spacing: 0.3px; box-shadow: 0 4px 14px rgba(2, 132, 199, 0.35);">
                                            Reset My Password &rarr;
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Expiration / Warning Box -->
                            <div style="background-color: #fffbeb; border: 1px solid #fef3c7; border-left: 4px solid #f59e0b; border-radius: 8px; padding: 14px 16px; margin-bottom: 24px;">
                                <p style="margin: 0; color: #92400e; font-size: 13px; line-height: 1.5;">
                                    <strong>Important Security Notice:</strong> If you did not request this password reset, you can safely ignore this email. Your password will remain unchanged, and your account is secure.
                                </p>
                            </div>

                            <!-- Alternate Raw Link -->
                            <p style="margin: 24px 0 6px 0; color: #64748b; font-size: 12px; line-height: 1.5;">
                                Having trouble with the button? Copy and paste the following URL into your web browser:
                            </p>
                            <p style="margin: 0 0 20px 0; word-break: break-all;">
                                <a href="{{ $resetUrl }}" style="color: #0284c7; font-size: 12px; text-decoration: underline;">
                                    {{ $resetUrl }}
                                </a>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 24px 32px; text-align: center;">
                            <p style="margin: 0 0 8px 0; color: #64748b; font-size: 13px;">
                                Need help? Contact our support team:
                            </p>
                            <p style="margin: 0 0 16px 0; font-size: 13px; color: #0f172a; font-weight: 600;">
                                <a href="mailto:{{ $supportEmail }}" style="color: #0284c7; text-decoration: none;">{{ $supportEmail }}</a>
                                &bull;
                                <a href="tel:{{ $supportPhone }}" style="color: #0284c7; text-decoration: none;">{{ $supportPhone }}</a>
                            </p>
                            <p style="margin: 0; color: #94a3b8; font-size: 11px;">
                                &copy; {{ date('Y') }} {{ $companyName }}. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
