<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=initial-scale=1.0">
    <title>Reset Password - Shield Basketball</title>

    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 40px 20px;
            color: #334155;
            -webkit-font-smoothing: antialiased;
        }

        .wrapper {
            max-width: 540px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            overflow: hidden;
        }

        .header {
            padding: 32px 32px 24px;
            text-align: center;
            border-bottom: 1px solid #f1f5f9;
        }

        .logo {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin: 0;
        }

        .logo span {
            color: #0f172a;
        }

        .content {
            padding: 40px 32px;
            text-align: center;
        }

        h1 {
            font-size: 24px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 16px;
        }

        p {
            font-size: 15px;
            line-height: 1.6;
            color: #64748b;
            margin: 0 0 24px;
        }

        .button-container {
            margin: 32px 0;
        }

        .button {
            display: inline-block;
            padding: 14px 32px;
            background-color: #2a85ff;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 15px;
            letter-spacing: 0.3px;
            box-shadow: 0 10px 15px -3px rgba(42, 133, 255, 0.3);
            transition: background-color 0.2s ease;
        }

        .button:hover {
            background-color: #0069f6;
        }

        .divider {
            height: 1px;
            background-color: #f1f5f9;
            margin: 32px 0;
        }

        .footer {
            padding: 0 32px 32px;
            text-align: center;
        }

        .info-text {
            font-size: 13px;
            color: #94a3b8;
            line-height: 1.5;
            margin: 0;
        }

        .link-text {
            word-break: break-all;
            font-size: 12px;
            color: #94a3b8;
            margin-top: 16px;
        }

        .link-text a {
            color: #2a85ff;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <div class="wrapper">
        <div class="header">
            <img src="{{ $message->embed(public_path('img/logo/Logo SHIELD 2025.png')) }}" alt="Shield Basketball Logo"
                style="height: 36px; display: inline-block; vertical-align: middle; margin-right: 8px;">
            <h2 class="logo" style="display: inline-block; vertical-align: middle;">SHIELD <span>BASKETBALL CLUB</span>
            </h2>
        </div>

        <div class="content">
            <h1>Reset Password Request</h1>

            <p>
                We received a request to reset your account password. Click the button below to secure your account and
                set a new password.
            </p>

            <div class="button-container">
                <a href="{{ $frontendUrl }}" class="button" target="_blank">
                    Reset Password
                </a>
            </div>

            <p class="info-text">
                This password reset link is only valid for <strong>60 minutes</strong>.
            </p>

            <div class="divider"></div>

            <p class="info-text">
                If you did not request a password reset, please ignore this email or contact support if you have
                questions.
            </p>
        </div>
    </div>

    <div style="text-align: center; margin-top: 24px;">
        <p style="font-size: 12px; color: #94a3b8; margin: 0;">
            &copy; {{ date('Y') }} Shield Basketball. All rights reserved.
        </p>
    </div>

</body>

</html>