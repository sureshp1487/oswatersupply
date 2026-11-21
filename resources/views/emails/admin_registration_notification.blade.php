<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>New Customer Registration Notification</title>
</head>
<body style="margin: 0; padding: 0; background-color: #0f172a; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1e293b; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;">
    <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #0f172a; padding: 36px 12px;">
        <tr>
            <td align="center">
                <!-- Main Container -->
                <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 640px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.2); border: 1px solid #1e293b;">
                    
                    <!-- Top Corporate Brand Header -->
                    <tr>
                        <td style="background-color: #1e293b; padding: 22px 32px; border-bottom: 3px solid #0284c7;">
                            <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td>
                                        <div style="font-size: 16px; font-weight: 800; color: #ffffff; letter-spacing: -0.3px; display: inline-flex; align-items: center;">
                                            💧 {{ $companyName }} <span style="margin-left: 8px; font-size: 11px; background-color: #0284c7; color: #ffffff; padding: 2px 8px; border-radius: 4px; font-weight: 700; text-transform: uppercase;">Admin Portal</span>
                                        </div>
                                    </td>
                                    <td align="right">
                                        <span style="font-size: 11px; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                            SYSTEM ALERT
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Notification Banner -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); padding: 28px 32px; border-bottom: 1px solid #e2e8f0;">
                            <div style="margin-bottom: 8px;">
                                <span style="background-color: #dbeafe; color: #1d4ed8; font-size: 11px; font-weight: 800; padding: 4px 10px; border-radius: 9999px; text-transform: uppercase; letter-spacing: 0.6px; border: 1px solid #bfdbfe;">
                                    ✦ New Customer Registered
                                </span>
                            </div>
                            <h1 style="margin: 0 0 6px 0; color: #0f172a; font-size: 22px; font-weight: 800; letter-spacing: -0.3px;">
                                New Customer Registration Alert
                            </h1>
                            <p style="margin: 0; color: #475569; font-size: 14px; line-height: 1.5;">
                                Hello <strong>Admin</strong>, a new customer has successfully registered on your platform.
                            </p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 28px 32px 20px 32px;">

                            <!-- Section 1: Customer Information Table -->
                            <div style="margin-bottom: 24px;">
                                <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 2px solid #0284c7; padding-bottom: 8px; margin-bottom: 12px;">
                                    <span style="font-size: 13px; font-weight: 800; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px;">
                                        👤 Customer Information
                                    </span>
                                </div>
                                <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; border-collapse: separate; font-size: 13px;">
                                    <tr style="background-color: #f8fafc;">
                                        <th align="left" style="padding: 10px 14px; color: #64748b; font-weight: 600; width: 34%; border-bottom: 1px solid #e2e8f0;">Customer ID</th>
                                        <td style="padding: 10px 14px; font-weight: 700; color: #0284c7; border-bottom: 1px solid #e2e8f0;">
                                            #CUST-{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th align="left" style="padding: 10px 14px; color: #64748b; font-weight: 600; border-bottom: 1px solid #e2e8f0;">Customer Name</th>
                                        <td style="padding: 10px 14px; font-weight: 700; color: #0f172a; border-bottom: 1px solid #e2e8f0;">{{ $user->name }}</td>
                                    </tr>
                                    <tr style="background-color: #f8fafc;">
                                        <th align="left" style="padding: 10px 14px; color: #64748b; font-weight: 600; border-bottom: 1px solid #e2e8f0;">Username</th>
                                        <td style="padding: 10px 14px; font-weight: 600; color: #334155; border-bottom: 1px solid #e2e8f0;">{{ $user->email }}</td>
                                    </tr>
                                    <tr>
                                        <th align="left" style="padding: 10px 14px; color: #64748b; font-weight: 600; border-bottom: 1px solid #e2e8f0;">Email</th>
                                        <td style="padding: 10px 14px; font-weight: 600; color: #0284c7; border-bottom: 1px solid #e2e8f0;">
                                            <a href="mailto:{{ $user->email }}" style="color: #0284c7; text-decoration: none;">{{ $user->email }}</a>
                                        </td>
                                    </tr>
                                    <tr style="background-color: #f8fafc;">
                                        <th align="left" style="padding: 10px 14px; color: #64748b; font-weight: 600; border-bottom: 1px solid #e2e8f0;">Mobile Number</th>
                                        <td style="padding: 10px 14px; font-weight: 700; color: #0f172a; border-bottom: 1px solid #e2e8f0;">
                                            <a href="tel:{{ $user->phone }}" style="color: #0f172a; text-decoration: none;">+91 {{ $user->phone }}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th align="left" style="padding: 10px 14px; color: #64748b; font-weight: 600; border-bottom: 1px solid #e2e8f0;">Registration Date</th>
                                        <td style="padding: 10px 14px; font-weight: 600; color: #334155; border-bottom: 1px solid #e2e8f0;">{{ $registrationDate }}</td>
                                    </tr>
                                    <tr style="background-color: #f8fafc;">
                                        <th align="left" style="padding: 10px 14px; color: #64748b; font-weight: 600; border-bottom: 1px solid #e2e8f0;">Registration Time</th>
                                        <td style="padding: 10px 14px; font-weight: 600; color: #334155; border-bottom: 1px solid #e2e8f0;">{{ $registrationTime }}</td>
                                    </tr>
                                    <tr>
                                        <th align="left" style="padding: 10px 14px; color: #64748b; font-weight: 600;">Account Status</th>
                                        <td style="padding: 10px 14px;">
                                            <span style="display: inline-block; background-color: #dcfce7; color: #15803d; font-size: 11px; font-weight: 800; padding: 2px 8px; border-radius: 9999px; text-transform: capitalize;">
                                                {{ $user->status ?? 'Active' }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Section 2: Customer Address Information Table -->
                            <div style="margin-bottom: 28px;">
                                <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 2px solid #0284c7; padding-bottom: 8px; margin-bottom: 12px;">
                                    <span style="font-size: 13px; font-weight: 800; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px;">
                                        📍 Customer Address Information
                                    </span>
                                </div>
                                <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; border-collapse: separate; font-size: 13px;">
                                    <tr style="background-color: #f8fafc;">
                                        <th align="left" style="padding: 10px 14px; color: #64748b; font-weight: 600; width: 34%; border-bottom: 1px solid #e2e8f0;">Address Label</th>
                                        <td style="padding: 10px 14px; font-weight: 700; color: #0f172a; border-bottom: 1px solid #e2e8f0;">
                                            {{ $address->label ?? 'Home' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th align="left" style="padding: 10px 14px; color: #64748b; font-weight: 600; border-bottom: 1px solid #e2e8f0;">House / Building</th>
                                        <td style="padding: 10px 14px; font-weight: 600; color: #334155; border-bottom: 1px solid #e2e8f0;">
                                            {{ $address->house_or_building ?? 'N/A' }}
                                        </td>
                                    </tr>
                                    <tr style="background-color: #f8fafc;">
                                        <th align="left" style="padding: 10px 14px; color: #64748b; font-weight: 600; border-bottom: 1px solid #e2e8f0;">Door Number</th>
                                        <td style="padding: 10px 14px; font-weight: 600; color: #334155; border-bottom: 1px solid #e2e8f0;">
                                            {{ $address->door_number ?? 'N/A' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th align="left" style="padding: 10px 14px; color: #64748b; font-weight: 600; border-bottom: 1px solid #e2e8f0;">Street</th>
                                        <td style="padding: 10px 14px; font-weight: 600; color: #334155; border-bottom: 1px solid #e2e8f0;">
                                            {{ $address->street ?? 'N/A' }}
                                        </td>
                                    </tr>
                                    <tr style="background-color: #f8fafc;">
                                        <th align="left" style="padding: 10px 14px; color: #64748b; font-weight: 600; border-bottom: 1px solid #e2e8f0;">Area / Locality</th>
                                        <td style="padding: 10px 14px; font-weight: 600; color: #334155; border-bottom: 1px solid #e2e8f0;">
                                            {{ $address->area_locality ?? 'N/A' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th align="left" style="padding: 10px 14px; color: #64748b; font-weight: 600; border-bottom: 1px solid #e2e8f0;">City</th>
                                        <td style="padding: 10px 14px; font-weight: 600; color: #334155; border-bottom: 1px solid #e2e8f0;">
                                            {{ $address->city ?? 'N/A' }}
                                        </td>
                                    </tr>
                                    <tr style="background-color: #f8fafc;">
                                        <th align="left" style="padding: 10px 14px; color: #64748b; font-weight: 600; border-bottom: 1px solid #e2e8f0;">Pincode</th>
                                        <td style="padding: 10px 14px; font-weight: 700; color: #0f172a; border-bottom: 1px solid #e2e8f0;">
                                            {{ $address->pincode ?? 'N/A' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th align="left" style="padding: 10px 14px; color: #64748b; font-weight: 600;">Delivery Instructions</th>
                                        <td style="padding: 10px 14px; font-weight: 500; color: {{ !empty($address->delivery_instructions) ? '#b45309' : '#94a3b8' }};">
                                            {{ $address->delivery_instructions ?? 'None provided' }}
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <!-- CTA Button Section -->
                            <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="margin: 28px 0 16px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $adminCustomerDetailsUrl }}" target="_blank" style="display: inline-block; background-color: #0f172a; color: #ffffff; text-decoration: none; font-size: 14px; font-weight: 700; padding: 14px 34px; border-radius: 8px; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.25); text-transform: uppercase; letter-spacing: 0.6px; border: 1px solid #334155;">
                                            View Customer Details &rarr;
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="text-align: center; margin: 0 0 24px 0; color: #64748b; font-size: 13px;">
                                A new customer has been successfully added to your database.
                            </p>

                            <!-- Sign-off -->
                            <div style="border-top: 1px solid #e2e8f0; padding-top: 16px;">
                                <p style="margin: 0; color: #475569; font-size: 13px;">
                                    Regards,<br>
                                    <strong style="color: #0f172a;">{{ $companyName }} Notification System</strong>
                                </p>
                            </div>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 18px 32px; text-align: center;">
                            <p style="margin: 0; color: #94a3b8; font-size: 11px; line-height: 1.4;">
                                This is an automated administrative notification dispatched by {{ $companyName }}.<br>
                                Confidential: Intended solely for designated administrative personnel.
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
