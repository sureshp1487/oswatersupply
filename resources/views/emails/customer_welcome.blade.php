<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Welcome to {{ $companyName }}</title>
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
                            <div style="display: inline-block; background-color: rgba(255, 255, 255, 0.2); border: 1px solid rgba(255, 255, 255, 0.35); border-radius: 50%; width: 56px; height: 56px; line-height: 56px; font-size: 28px; margin-bottom: 12px; text-align: center;">
                                💧
                            </div>
                            <h1 style="margin: 0; color: #ffffff; font-size: 26px; font-weight: 800; letter-spacing: -0.5px; line-height: 1.2;">
                                Welcome to {{ $companyName }}!
                            </h1>
                            <p style="margin: 8px 0 0 0; color: #e0f2fe; font-size: 15px; font-weight: 500;">
                                Pure & Fresh Doorstep Water Delivery
                            </p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 36px 32px 24px 32px;">
                            <!-- Personalized Greeting -->
                            <h2 style="margin: 0 0 14px 0; color: #0f172a; font-size: 20px; font-weight: 700;">
                                Hello {{ $user->name }},
                            </h2>
                            <p style="margin: 0 0 16px 0; color: #475569; font-size: 15px; line-height: 1.6;">
                                Welcome to <strong>{{ $companyName }}</strong>! Your account has been successfully created. Thank you for joining us. We are delighted to have you on board and are committed to keeping your daily hydration fresh, seamless, and hassle-free.
                            </p>

                            <!-- Account Details Card -->
                            <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 22px 24px; margin: 24px 0 28px 0;">
                                <div style="border-bottom: 1px solid #e2e8f0; padding-bottom: 10px; margin-bottom: 14px;">
                                    <span style="color: #0284c7; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px;">
                                        Your Login Details
                                    </span>
                                </div>
                                <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size: 14px; color: #334155;">
                                    <tr>
                                        <td style="padding: 6px 0; color: #64748b; font-weight: 500; width: 38%;">Username:</td>
                                        <td style="padding: 6px 0; font-weight: 700; color: #0f172a;">{{ $user->email }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 6px 0; color: #64748b; font-weight: 500;">Email:</td>
                                        <td style="padding: 6px 0; font-weight: 600; color: #0f172a;">{{ $user->email }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 6px 0; color: #64748b; font-weight: 500;">Account Status:</td>
                                        <td style="padding: 6px 0;">
                                            <span style="display: inline-block; background-color: #dcfce7; color: #15803d; font-size: 12px; font-weight: 700; padding: 3px 10px; border-radius: 9999px; text-transform: capitalize;">
                                                Active
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Security Reminder Note -->
                            <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 28px; background-color: #eff6ff; border-left: 4px solid #0284c7; border-radius: 0 8px 8px 0;">
                                <tr>
                                    <td style="padding: 12px 16px; color: #1e40af; font-size: 13px; line-height: 1.5;">
                                        🔒 <strong>Security Note:</strong> For your privacy, your password is never sent in emails. Please use the secure password you selected during registration to sign in.
                                    </td>
                                </tr>
                            </table>

                            <!-- Prominent CTA Button -->
                            <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="margin: 28px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $loginUrl }}" target="_blank" style="display: inline-block; background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); color: #ffffff; text-decoration: none; font-size: 15px; font-weight: 700; padding: 14px 36px; border-radius: 10px; box-shadow: 0 4px 14px rgba(2, 132, 199, 0.35); text-transform: uppercase; letter-spacing: 0.5px;">
                                            Login to Your Account &rarr;
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Warm Sign-off & Team -->
                            <p style="margin: 24px 0 6px 0; color: #475569; font-size: 15px; line-height: 1.6;">
                                We look forward to serving you!
                            </p>
                            <p style="margin: 0 0 24px 0; color: #1e293b; font-size: 15px; font-weight: 600;">
                                Best Regards,<br>
                                <span style="color: #0284c7;">{{ $companyName }} Team</span>
                            </p>

                            <!-- Help & Support Section -->
                            <div style="border-top: 1px solid #f1f5f9; padding-top: 18px; margin-top: 20px;">
                                <p style="margin: 0 0 4px 0; color: #64748b; font-size: 13px; font-weight: 600;">
                                    Need assistance with your delivery?
                                </p>
                                <p style="margin: 0; color: #64748b; font-size: 13px; line-height: 1.5;">
                                    Reach out to us anytime at <a href="mailto:{{ $supportEmail }}" style="color: #0284c7; text-decoration: none; font-weight: 600;">{{ $supportEmail }}</a>
                                    @if(!empty($supportPhone))
                                    or call us at <strong style="color: #334155;">{{ $supportPhone }}</strong>
                                    @endif.
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 22px 32px; text-align: center;">
                            <p style="margin: 0 0 6px 0; color: #94a3b8; font-size: 12px; line-height: 1.5;">
                                &copy; {{ date('Y') }} {{ $companyName }}. All rights reserved.
                            </p>
                            <p style="margin: 0; color: #94a3b8; font-size: 11px; line-height: 1.4;">
                                You are receiving this transactional email because an account was registered with your email address.
                            </p>
                        </td>
                    </tr>

                </table>
                <!-- End Main Container -->
            </td>
        </tr>
    </table>
</body>
</html>
